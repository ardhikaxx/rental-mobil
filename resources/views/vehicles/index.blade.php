@extends('layouts.app')

@section('title', 'Kendaraan')

@section('content')
    <x-page-header
        title="Kendaraan"
        subtitle="Kelola seluruh aset mobil rental.">
        <x-slot name="actions">
            @can('edit-vehicle')
                <a href="{{ route('vehicles.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Kendaraan
                </a>
            @endcan
            <a href="{{ route('inspections.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-clipboard-check me-1"></i> Pemeriksaan Baru
            </a>
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('vehicles.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control"
                       value="{{ $search }}" placeholder="Kode unit, nomor polisi, merek, model">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua status</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected($filterStatus === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="type">Tipe</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Semua tipe</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected($filterType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="sort">Urutkan</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="newest" @selected($sort === 'newest')>Terbaru</option>
                    <option value="code" @selected($sort === 'code')>Kode unit</option>
                    <option value="rate_desc" @selected($sort === 'rate_desc')>Tarif tertinggi</option>
                    <option value="rate_asc" @selected($sort === 'rate_asc')>Tarif terendah</option>
                    <option value="odometer_desc" @selected($sort === 'odometer_desc')>Kilometer terbesar</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fa-solid fa-filter me-1"></i> Terapkan
                </button>
                <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary" title="Reset filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($vehicles->isEmpty())
            <x-empty-state
                icon="fa-car-side"
                title="Tidak ada kendaraan ditemukan"
                text="{{ $search || $filterStatus ? 'Tidak ada kendaraan yang cocok dengan filter Anda.' : 'Belum ada kendaraan terdaftar. Tambahkan aset mobil pertama Anda.' }}">
                @if (! $search && ! $filterStatus)
                    <x-slot name="action">
                        @can('edit-vehicle')
                            <a href="{{ route('vehicles.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-plus me-1"></i> Tambah Kendaraan
                            </a>
                        @endcan
                    </x-slot>
                @endif
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kendaraan</th>
                            <th>No. Polisi</th>
                            <th>Status</th>
                            <th class="text-end">Tarif/Hari</th>
                            <th class="text-end">Kilometer</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vehicles as $vehicle)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar-circle" style="width:46px;height:34px;border-radius:6px">
                                            @if ($vehicle->photo)
                                                <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="{{ $vehicle->code }}">
                                            @else
                                                <i class="fa-solid fa-car-side"></i>
                                            @endif
                                        </span>
                                        <div>
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="cell-title">
                                                {{ $vehicle->code }} — {{ $vehicle->brand }} {{ $vehicle->model }}
                                            </a>
                                            <div class="cell-sub">{{ \App\Enums\VehicleType::tryFrom($vehicle->type)?->label() ?? $vehicle->type }} · {{ $vehicle->year }} · {{ $vehicle->color }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="cell-title">{{ $vehicle->license_plate }}</td>
                                <td><x-status-badge kind="vehicle" :value="$vehicle->status" /></td>
                                <td class="text-end-tabular">{{ rupiah($vehicle->daily_rate) }}</td>
                                <td class="text-end-tabular">{{ number_format($vehicle->odometer) }} km</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @can('update-vehicle-status')
                                            @if ($vehicle->status->value === 'tersedia' || $vehicle->status->value === 'siap_jalan')
                                                <form method="POST" action="{{ route('vehicles.mark-cleaning', $vehicle) }}"
                                                      data-confirm="Mulai pembersihan kendaraan {{ $vehicle->code }}?"
                                                      data-confirm-title="Mulai pembersihan"
                                                      data-confirm-button="Ya, mulai">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Mulai dibersihkan">
                                                        <i class="fa-solid fa-broom"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($vehicle->status->value === 'dibersihkan')
                                                <form method="POST" action="{{ route('vehicles.mark-ready', $vehicle) }}"
                                                      data-confirm="Tandai {{ $vehicle->code }} siap jalan?"
                                                      data-confirm-title="Siap jalan"
                                                      data-confirm-button="Ya, siap jalan">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai siap jalan">
                                                        <i class="fa-solid fa-gauge-high"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if ($vehicle->status->value === 'siap_jalan')
                                                <form method="POST" action="{{ route('vehicles.mark-available', $vehicle) }}"
                                                      data-confirm="Tandai {{ $vehicle->code }} kembali tersedia?"
                                                      data-confirm-title="Tandai tersedia"
                                                      data-confirm-button="Ya, tersedia">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai tersedia">
                                                        <i class="fa-solid fa-circle-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                        @can('edit-vehicle')
                                            <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
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

    {{ $vehicles->links('pagination::bootstrap-5') }}
@endsection
