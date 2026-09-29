<?php

namespace App\Http\Controllers;

use App\Enums\MaintenanceStatus;
use App\Enums\MaintenanceType;
use App\Enums\VehicleStatus;
use App\Http\Requests\MaintenanceRequest;
use App\Models\Maintenance;
use App\Models\Vehicle;
use App\Services\VehicleStatusService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __construct(private VehicleStatusService $vehicleStatus) {}

    public function index(Request $request): View
    {
        $status = $request->query('status');

        $maintenances = Maintenance::query()
            ->with(['vehicle:id,code,brand,model,license_plate,status', 'recorder:id,name'])
            ->when($status !== null && $status !== '', fn ($query) => $query->where('status', $status))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where(function ($builder) use ($term) {
                    $builder->whereHas('vehicle', fn ($b) => $b->where('license_plate', 'like', "%{$term}%"))
                        ->orWhereHas('vehicle', fn ($b) => $b->where('code', 'like', "%{$term}%"))
                        ->orWhere('workshop', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest('start_date')
            ->paginate(12)
            ->withQueryString();

        $counts = Maintenance::query()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('maintenances.index', [
            'maintenances' => $maintenances,
            'statuses' => MaintenanceStatus::options(),
            'filterStatus' => $status,
            'search' => $request->query('search'),
            'counts' => $counts,
            'totalCost' => (int) Maintenance::sum('cost'),
        ]);
    }

    public function create(Request $request): View
    {
        $this->authorize('create-maintenance');

        return view('maintenances.create', [
            'vehicles' => Vehicle::query()->active()->orderBy('code')->get(['id', 'code', 'brand', 'model', 'license_plate', 'odometer']),
            'types' => MaintenanceType::options(),
            'statuses' => MaintenanceStatus::options(),
            'selectedVehicle' => $request->query('vehicle_id'),
        ]);
    }

    public function store(MaintenanceRequest $request): RedirectResponse
    {
        $this->authorize('create-maintenance');

        $data = $request->validated();
        $data['status'] = $data['status'] ?? MaintenanceStatus::Scheduled->value;
        $data['recorded_by'] = $request->user()->id;

        $maintenance = Maintenance::create($data);
        $vehicle = $maintenance->vehicle;

        if ($maintenance->status === MaintenanceStatus::InProgress && $vehicle->status->value !== 'disewa') {
            $vehicle->update(['status' => VehicleStatus::Maintenance]);
        }

        AuditLogger::log(
            'create',
            'maintenances',
            "Perawatan kendaraan {$vehicle->code} dicatat ({$maintenance->type->label()}).",
            $maintenance,
        );

        return redirect()->route('maintenances.show', $maintenance)
            ->with('success', "Catatan perawatan kendaraan {$vehicle->code} berhasil dibuat.");
    }

    public function show(Maintenance $maintenance): View
    {
        $maintenance->load(['vehicle.transactions' => fn ($query) => $query->latest()->limit(5)->with('customer:id,name'), 'recorder:id,name']);

        return view('maintenances.show', ['maintenance' => $maintenance]);
    }

    public function edit(Maintenance $maintenance): View
    {
        $this->authorize('manage-maintenance');

        return view('maintenances.edit', [
            'maintenance' => $maintenance,
            'vehicles' => Vehicle::query()->active()->orderBy('code')->get(['id', 'code', 'brand', 'model', 'license_plate']),
            'types' => MaintenanceType::options(),
        ]);
    }

    public function update(MaintenanceRequest $request, Maintenance $maintenance): RedirectResponse
    {
        $this->authorize('manage-maintenance');

        $data = $request->validated();
        unset($data['status'], $data['vehicle_id']);

        $maintenance->update($data);

        AuditLogger::log('update', 'maintenances', "Catatan perawatan kendaraan {$maintenance->vehicle->code} diubah.", $maintenance);

        return redirect()->route('maintenances.show', $maintenance)
            ->with('success', 'Catatan perawatan berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Maintenance $maintenance): RedirectResponse
    {
        $this->authorize('manage-maintenance');

        $request->validate([
            'status' => ['required', 'in:in_progress,completed,cancelled'],
            'end_date' => ['nullable', 'date'],
        ], [], ['status' => 'status perawatan', 'end_date' => 'tanggal selesai']);

        $target = MaintenanceStatus::from($request->input('status'));
        $vehicle = $maintenance->vehicle;

        if (! $this->canTransition($maintenance->status, $target)) {
            throw ValidationException::withMessages([
                'status' => "Perawatan berstatus {$maintenance->status->label()} tidak dapat diubah menjadi {$target->label()}.",
            ]);
        }

        if ($target === MaintenanceStatus::InProgress) {
            if ($vehicle->status->value === 'disewa') {
                throw ValidationException::withMessages([
                    'status' => 'Kendaraan sedang disewa dan tidak dapat masuk perawatan sebelum dikembalikan.',
                ]);
            }

            $vehicle->update(['status' => VehicleStatus::Maintenance]);
        }

        $maintenance->update([
            'status' => $target,
            'end_date' => $target === MaintenanceStatus::InProgress ? $maintenance->end_date : ($request->input('end_date') ?? now()->toDateString()),
        ]);

        if (in_array($target, [MaintenanceStatus::Completed, MaintenanceStatus::Cancelled], true)) {
            $otherActive = $vehicle->maintenances()
                ->whereKey('!=', $maintenance->id)
                ->whereIn('status', MaintenanceStatus::active())
                ->exists();

            if (! $otherActive && $vehicle->status->value === 'perawatan') {
                $this->vehicleStatus->afterMaintenanceCompleted($vehicle);
            }
        }

        AuditLogger::log(
            'status_change',
            'maintenances',
            "Perawatan kendaraan {$vehicle->code} diubah menjadi {$target->label()}.",
            $maintenance,
        );

        return redirect()->route('maintenances.show', $maintenance)
            ->with('success', "Status perawatan kendaraan {$vehicle->code} menjadi {$target->label()}.");
    }

    private function canTransition(MaintenanceStatus $from, MaintenanceStatus $to): bool
    {
        return match ($from) {
            MaintenanceStatus::Scheduled => in_array($to, [MaintenanceStatus::InProgress, MaintenanceStatus::Cancelled], true),
            MaintenanceStatus::InProgress => in_array($to, [MaintenanceStatus::Completed, MaintenanceStatus::Cancelled], true),
            default => false,
        };
    }
}
