<?php

namespace App\Http\Controllers;

use App\Enums\Completeness;
use App\Enums\ConditionLevel;
use App\Enums\FuelLevel;
use App\Enums\InspectionType;
use App\Enums\TireCondition;
use App\Http\Requests\StoreInspectionRequest;
use App\Models\Inspection;
use App\Models\Vehicle;
use App\Services\PhotoStorage;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InspectionController extends Controller
{
    public function index(Request $request): View
    {
        $type = $request->query('type');

        $inspections = Inspection::query()
            ->with(['vehicle:id,code,brand,model,license_plate', 'transaction:id,transaction_number', 'inspector:id,name'])
            ->when($type !== null && $type !== '', fn ($query) => $query->where('type', $type))
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = trim((string) $request->query('search'));
                $query->where(function ($builder) use ($term) {
                    $builder->whereHas('vehicle', fn ($b) => $b->where('license_plate', 'like', "%{$term}%"))
                        ->orWhereHas('vehicle', fn ($b) => $b->where('code', 'like', "%{$term}%"))
                        ->orWhereHas('transaction', fn ($b) => $b->where('transaction_number', 'like', "%{$term}%"));
                });
            })
            ->latest('inspected_at')
            ->paginate(12)
            ->withQueryString();

        $counts = Inspection::query()
            ->selectRaw('type, COUNT(*) as total')
            ->groupBy('type')
            ->pluck('total', 'type');

        return view('inspections.index', [
            'inspections' => $inspections,
            'types' => InspectionType::options(),
            'filterType' => $type,
            'search' => $request->query('search'),
            'counts' => $counts,
        ]);
    }

    public function create(Request $request): View
    {
        return view('inspections.create', [
            'vehicles' => Vehicle::query()->active()->orderBy('code')->get(['id', 'code', 'brand', 'model', 'license_plate', 'odometer', 'fuel_level']),
            'selectedVehicle' => $request->query('vehicle_id'),
            'fuelLevels' => FuelLevel::options(),
            'conditions' => ConditionLevel::options(),
            'tires' => TireCondition::options(),
            'completeness' => Completeness::options(),
        ]);
    }

    public function store(StoreInspectionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        $inspection = Inspection::create([
            'vehicle_id' => $vehicle->id,
            'transaction_id' => null,
            'type' => InspectionType::Routine,
            'inspected_at' => $data['inspected_at'],
            'inspected_by' => $request->user()->id,
            'odometer' => $data['odometer'],
            'fuel_level' => $data['fuel_level'],
            'exterior_condition' => $data['exterior_condition'],
            'interior_condition' => $data['interior_condition'],
            'tire_condition' => $data['tire_condition'],
            'completeness' => $data['completeness'] ?? null,
            'missing_items' => $data['missing_items'] ?? null,
            'existing_damage' => $data['existing_damage'] ?? null,
            'notes' => $data['notes'] ?? null,
        ]);

        if (isset($data['photos'])) {
            app(PhotoStorage::class)->store($inspection, $data['photos']);
        }

        $vehicle->update([
            'odometer' => max((int) $vehicle->odometer, (int) $data['odometer']),
            'fuel_level' => $data['fuel_level'],
        ]);

        AuditLogger::log(
            'create',
            'inspections',
            "Pemeriksaan rutin kendaraan {$vehicle->code} dicatat (odometer {$data['odometer']} km).",
            $inspection,
        );

        return redirect()->route('inspections.show', $inspection)
            ->with('success', "Pemeriksaan kendaraan {$vehicle->code} berhasil dicatat.");
    }

    public function show(Inspection $inspection): View
    {
        $inspection->load([
            'vehicle',
            'transaction.customer',
            'inspector:id,name',
            'photos',
        ]);

        return view('inspections.show', ['inspection' => $inspection]);
    }
}
