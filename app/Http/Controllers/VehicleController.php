<?php

namespace App\Http\Controllers;

use App\Enums\FuelLevel;
use App\Enums\TransactionStatus;
use App\Enums\VehicleStatus;
use App\Enums\VehicleType;
use App\Http\Requests\VehicleRequest;
use App\Models\Vehicle;
use App\Services\ImageUploadService;
use App\Services\VehicleStatusService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function __construct(
        private VehicleStatusService $statusService,
        private ImageUploadService $imageService,
    ) {}

    public function index(Request $request): View
    {
        $sort = $request->query('sort', 'newest');
        $sortMap = [
            'newest' => ['created_at', 'desc'],
            'oldest' => ['created_at', 'asc'],
            'code' => ['code', 'asc'],
            'rate_desc' => ['daily_rate', 'desc'],
            'rate_asc' => ['daily_rate', 'asc'],
            'odometer_desc' => ['odometer', 'desc'],
        ];
        [$column, $direction] = $sortMap[$sort] ?? $sortMap['newest'];

        $vehicles = Vehicle::query()
            ->search($request->query('search'))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->query('status')))
            ->when($request->filled('type'), fn ($query) => $query->where('type', $request->query('type')))
            ->when($request->filled('active'), fn ($query) => $query->where('is_active', $request->query('active') === '1'))
            ->orderBy($column, $direction)
            ->paginate(12)
            ->withQueryString();

        return view('vehicles.index', [
            'vehicles' => $vehicles,
            'statuses' => VehicleStatus::options(),
            'types' => VehicleType::options(),
            'search' => $request->query('search'),
            'filterStatus' => $request->query('status'),
            'filterType' => $request->query('type'),
            'filterActive' => $request->query('active'),
            'sort' => $sort,
        ]);
    }

    public function create(): View
    {
        return view('vehicles.create', [
            'types' => VehicleType::options(),
            'fuelLevels' => FuelLevel::options(),
        ]);
    }

    public function store(VehicleRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['odometer'] = 0;
        $data['status'] = VehicleStatus::Available;

        if ($request->hasFile('photo')) {
            $data['photo'] = $this->imageService->upload($request->file('photo'), 'vehicles', 'vehicle_');
        }

        $vehicle = Vehicle::create($data);

        AuditLogger::log('create', 'vehicles', "Kendaraan {$vehicle->code} ({$vehicle->license_plate}) ditambahkan.", $vehicle);

        return redirect()->route('vehicles.show', $vehicle)->with('success', "Kendaraan {$vehicle->code} berhasil ditambahkan.");
    }

    public function show(Vehicle $vehicle): View
    {
        $vehicle->load([
            'transactions' => fn ($query) => $query->with(['customer:id,name'])->latest()->limit(10),
            'inspections' => fn ($query) => $query->with(['inspector:id,name', 'transaction:id,transaction_number'])->latest()->limit(10),
            'maintenances' => fn ($query) => $query->with('recorder:id,name')->latest(),
        ]);

        $stats = [
            'totalRentals' => $vehicle->transactions()
                ->whereIn('status', ['booked', 'ready_for_handover', 'rented', 'completed'])
                ->count(),
            'revenue' => (int) $vehicle->transactions()
                ->whereIn('status', ['booked', 'ready_for_handover', 'rented', 'completed'])
                ->sum('total'),
            'maintenanceCount' => $vehicle->maintenances()->count(),
            'maintenanceCost' => (int) $vehicle->maintenances()->sum('cost'),
            'activeBooking' => $vehicle->transactions()
                ->whereIn('status', TransactionStatus::blocking())
                ->orderBy('start_at')
                ->first(),
        ];

        return view('vehicles.show', [
            'vehicle' => $vehicle,
            'stats' => $stats,
            'types' => VehicleType::options(),
        ]);
    }

    public function edit(Vehicle $vehicle): View
    {
        return view('vehicles.edit', [
            'vehicle' => $vehicle,
            'types' => VehicleType::options(),
            'fuelLevels' => FuelLevel::options(),
        ]);
    }

    public function update(VehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if ($request->boolean('delete_photo')) {
            if ($vehicle->photo !== null) {
                $this->imageService->delete($vehicle->photo, 'vehicles');
            }
            $data['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($vehicle->photo !== null) {
                $this->imageService->delete($vehicle->photo, 'vehicles');
            }
            $data['photo'] = $this->imageService->upload($request->file('photo'), 'vehicles', 'vehicle_');
        } else {
            unset($data['photo']);
        }

        if (! $request->boolean('is_active')) {
            $hasActive = $vehicle->transactions()
                ->whereIn('status', TransactionStatus::blocking())
                ->exists();

            if ($hasActive) {
                throw ValidationException::withMessages([
                    'is_active' => 'Kendaraan memiliki booking/rental aktif dan tidak dapat dinonaktifkan.',
                ]);
            }
        }

        $vehicle->update($data);

        AuditLogger::log('update', 'vehicles', "Data kendaraan {$vehicle->code} diubah.", $vehicle);

        return redirect()->route('vehicles.show', $vehicle)->with('success', "Data kendaraan {$vehicle->code} berhasil diperbarui.");
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $hasActive = $vehicle->transactions()
            ->whereIn('status', TransactionStatus::blocking())
            ->exists();

        if ($hasActive) {
            throw ValidationException::withMessages([
                'vehicle' => 'Kendaraan memiliki booking/rental aktif dan tidak dapat dihapus.',
            ]);
        }

        if ($vehicle->photo !== null) {
            $this->imageService->delete($vehicle->photo, 'vehicles');
        }

        $code = $vehicle->code;
        $vehicle->delete();

        AuditLogger::log('delete', 'vehicles', "Kendaraan {$code} dihapus.", $vehicle);

        return redirect()->route('vehicles.index')->with('success', "Kendaraan {$code} berhasil dihapus.");
    }

    /**
     * Super admin: manual status override with confirmation on the frontend.
     */
    public function updateStatus(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(VehicleStatus::options()))],
        ], [], ['status' => 'status kendaraan']);

        $target = VehicleStatus::from($request->input('status'));
        $this->statusService->changeStatus($vehicle, $target, $request->user());

        AuditLogger::log('status_change', 'vehicles', "Status kendaraan {$vehicle->code} diubah manual menjadi {$target->label()}.", $vehicle);

        return back()->with('success', "Status kendaraan {$vehicle->code} diubah menjadi {$target->label()}.");
    }

    public function markCleaning(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->statusService->markCleaning($vehicle, $request->user());

        AuditLogger::log('status_change', 'vehicles', "Kendaraan {$vehicle->code} mulai dibersihkan.", $vehicle);

        return back()->with('success', "Kendaraan {$vehicle->code} ditandai sedang dibersihkan.");
    }

    public function markReady(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->statusService->markReady($vehicle, $request->user());

        AuditLogger::log('status_change', 'vehicles', "Kendaraan {$vehicle->code} ditandai siap jalan.", $vehicle);

        return back()->with('success', "Kendaraan {$vehicle->code} ditandai siap jalan.");
    }

    public function markAvailable(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $this->statusService->markAvailable($vehicle, $request->user());

        AuditLogger::log('status_change', 'vehicles', "Kendaraan {$vehicle->code} ditandai tersedia.", $vehicle);

        return back()->with('success', "Kendaraan {$vehicle->code} ditandai tersedia.");
    }
}
