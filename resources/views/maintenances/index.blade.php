@extends('layouts.app')

@section('title', 'Perawatan Kendaraan')

@section('content')
    <x-page-header
        title="Perawatan Kendaraan"
        subtitle="Catatan perawatan, biaya, dan riwayat kerusakan armada.">
        <x-slot name="actions">
            @can('create-maintenance')
                <a href="{{ route('maintenances.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Catat Perawatan
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-md-3">
            <x-stat icon="fa-calendar" label="Terjadwal" :value="$counts[\App\Enums\MaintenanceStatus::Scheduled->value] ?? 0" />
        </div>
        <div class="col-md-3">
            <x-stat icon="fa-spinner" label="Berjalan" :value="$counts[\App\Enums\MaintenanceStatus::InProgress->value] ?? 0" />
        </div>
        <div class="col-md-3">
            <x-stat icon="fa-circle-check" label="Selesai" :value="$counts[\App\Enums\MaintenanceStatus::Completed->value] ?? 0" />
        </div>
        <div class="col-md-3">
            <x-stat icon="fa-coins" label="Total Biaya Perawatan" :value="rupiah($totalCost)" />
        </div>
    </div>

    <form method="GET" action="{{ route('maintenances.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filterStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. polisi, kode unit, bengkel">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($maintenances->isEmpty())
            <x-empty-state icon="fa-screwdriver-wrench" title="Tidak ada perawatan"
                           text="{{ $search || $filterStatus ? 'Tidak ada catatan perawatan yang cocok dengan filter.' : 'Belum ada catatan perawatan kendaraan.' }}">
                @can('create-maintenance')
                    <x-slot name="action">
                        <a href="{{ route('maintenances.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> Catat Perawatan
                        </a>
                    </x-slot>
                @endcan
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kendaraan</th>
                            <th>Jenis</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Bengkel</th>
                            <th class="text-end">Biaya</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($maintenances as $maintenance)
                            <tr>
                                <td>
                                    <a href="{{ route('vehicles.show', $maintenance->vehicle) }}" class="cell-title">
                                        {{ $maintenance->vehicle?->code }}
                                    </a>
                                    <div class="cell-sub">{{ $maintenance->vehicle?->license_plate }}</div>
                                </td>
                                <td>
                                    {{ $maintenance->type->label() }}
                                    <div class="cell-sub">{{ \Illuminate\Support\Str::limit($maintenance->description, 50) }}</div>
                                </td>
                                <td class="cell-sub">
                                    {{ tanggal($maintenance->start_date) }} — {{ $maintenance->end_date ? tanggal($maintenance->end_date) : 'berjalan' }}
                                </td>
                                <td><x-status-badge kind="maintenance" :value="$maintenance->status" /></td>
                                <td class="cell-sub">{{ $maintenance->workshop ?: '-' }}</td>
                                <td class="text-end-tabular">{{ rupiah($maintenance->cost) }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @can('manage-maintenance')
                                            <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $maintenances->links('pagination::bootstrap-5') }}
@endsection
