<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Services\ImageUploadService;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DriverController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}

    public function index(Request $request): View
    {
        $drivers = Driver::query()
            ->search($request->query('search'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->query('status')))
            ->withCount('transactions')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $stats = [
            'total' => Driver::count(),
            'available' => Driver::where('status', 'available')->where('is_active', true)->count(),
            'busy' => Driver::where('status', 'busy')->count(),
            'off' => Driver::where('status', 'off')->count(),
        ];

        return view('drivers.index', [
            'drivers' => $drivers,
            'stats' => $stats,
            'search' => $request->query('search'),
            'statusFilter' => $request->query('status'),
        ]);
    }

    public function create(): View
    {
        return view('drivers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', 'unique:drivers,code'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:25'],
            'sim_number' => ['nullable', 'string', 'max:50'],
            'sim_type' => ['required', 'string', 'in:SIM A,SIM B1,SIM B1 Umum,SIM B2 Umum'],
            'daily_rate' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:available,busy,off,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->imageService->upload($request->file('photo'), 'drivers', 'driver_');
        }

        $driver = Driver::create($validated);

        AuditLogger::log('create', 'drivers', "Driver {$driver->name} ({$driver->code}) ditambahkan.", $driver);

        return redirect()->route('drivers.index')->with('success', "Driver {$driver->name} berhasil ditambahkan.");
    }

    public function edit(Driver $driver): View
    {
        return view('drivers.edit', ['driver' => $driver]);
    }

    public function update(Request $request, Driver $driver): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('drivers', 'code')->ignore($driver->id)],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:25'],
            'sim_number' => ['nullable', 'string', 'max:50'],
            'sim_type' => ['required', 'string', 'in:SIM A,SIM B1,SIM B1 Umum,SIM B2 Umum'],
            'daily_rate' => ['required', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:available,busy,off,inactive'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'delete_photo' => ['nullable', 'boolean'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->has('is_active');

        if ($request->boolean('delete_photo')) {
            if ($driver->photo !== null) {
                $this->imageService->delete($driver->photo, 'drivers');
            }
            $validated['photo'] = null;
        } elseif ($request->hasFile('photo')) {
            if ($driver->photo !== null) {
                $this->imageService->delete($driver->photo, 'drivers');
            }
            $validated['photo'] = $this->imageService->upload($request->file('photo'), 'drivers', 'driver_');
        }

        $driver->update($validated);

        AuditLogger::log('update', 'drivers', "Data driver {$driver->name} ({$driver->code}) diperbarui.", $driver);

        return redirect()->route('drivers.index')->with('success', "Data driver {$driver->name} berhasil diperbarui.");
    }

    public function destroy(Driver $driver): RedirectResponse
    {
        if ($driver->transactions()->exists()) {
            throw ValidationException::withMessages([
                'driver' => 'Driver memiliki riwayat transaksi dan tidak dapat dihapus. Nonaktifkan statusnya jika sudah tidak bertugas.',
            ]);
        }

        if ($driver->photo !== null) {
            $this->imageService->delete($driver->photo, 'drivers');
        }

        $name = $driver->name;
        $driver->delete();

        AuditLogger::log('delete', 'drivers', "Driver {$name} dihapus.", $driver);

        return redirect()->route('drivers.index')->with('success', "Driver {$name} berhasil dihapus.");
    }
}
