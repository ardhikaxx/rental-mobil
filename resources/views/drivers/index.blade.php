@extends('layouts.app')

@section('title', 'Supir / Driver')

@section('content')
    <x-page-header
        title="Supir / Driver"
        subtitle="Manajemen personil pengemudi armada Jaya Trans untuk sewa dengan supir.">
        <x-slot name="actions">
            @can('manage-customers')
                <a href="{{ route('drivers.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Supir
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-user-tie" label="Total Supir" :value="$stats['total']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-user-check" label="Supir Tersedia" :value="$stats['available']" sub="siap bertugas" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-car-side" label="Sedang Bertugas" :value="$stats['busy']" sub="dalam perjalanan" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-user-clock" label="Libur / Off" :value="$stats['off']" sub="istirahat / nonaktif" />
        </div>
    </div>

    <form method="GET" action="{{ route('drivers.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control"
                       value="{{ $search }}" placeholder="Kode supir, nama, atau nomor telepon">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="status">Status Tugas</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="available" @selected($statusFilter === 'available')>Tersedia (Ready)</option>
                    <option value="busy" @selected($statusFilter === 'busy')>Sedang Bertugas (Busy)</option>
                    <option value="off" @selected($statusFilter === 'off')>Libur / Cuti (Off)</option>
                    <option value="inactive" @selected($statusFilter === 'inactive')>Nonaktif (Inactive)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($drivers->isEmpty())
            <x-empty-state
                icon="fa-user-tie"
                title="Tidak ada supir ditemukan"
                text="{{ $search || $statusFilter ? 'Tidak ada supir yang cocok dengan pencarian / filter Anda.' : 'Belum ada supir terdaftar.' }}">
                @can('manage-customers')
                    <x-slot name="action">
                        <a href="{{ route('drivers.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> Tambah Supir
                        </a>
                    </x-slot>
                @endcan
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Kode & Nama</th>
                            <th>Telepon</th>
                            <th>Lisensi SIM</th>
                            <th>Tarif Harian</th>
                            <th>Status Tugas</th>
                            <th class="text-end">Riwayat Tugas</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($drivers as $driver)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar-circle" style="width:36px;height:36px;border-radius:50%;overflow:hidden">
                                            @if ($driver->photo)
                                                <img src="{{ $driver->photo_url }}" alt="{{ $driver->name }}" style="width:100%;height:100%;object-fit:cover">
                                            @else
                                                <i class="fa-solid fa-user-tie"></i>
                                            @endif
                                        </span>
                                        <div>
                                            <div class="cell-title">{{ $driver->name }}</div>
                                            <div class="cell-sub"><span class="badge bg-light text-dark border">{{ $driver->code }}</span> {{ $driver->notes ? '— '.$driver->notes : '' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $driver->phone }}</td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">{{ $driver->sim_type }}</span>
                                    @if ($driver->sim_number)
                                        <div class="cell-sub">{{ $driver->sim_number }}</div>
                                    @endif
                                </td>
                                <td class="fw-semibold text-dark">{{ rupiah($driver->daily_rate) }}/hari</td>
                                <td>
                                    @if ($driver->status === 'available')
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fa-solid fa-circle-check me-1"></i>Tersedia
                                        </span>
                                    @elseif ($driver->status === 'busy')
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="fa-solid fa-car me-1"></i>Bertugas
                                        </span>
                                    @elseif ($driver->status === 'off')
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="fa-solid fa-bed me-1"></i>Libur
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="fa-solid fa-ban me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end-tabular">{{ $driver->transactions_count }}x sewa</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        @can('manage-customers')
                                            <a href="{{ route('drivers.edit', $driver) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form method="POST" action="{{ route('drivers.destroy', $driver) }}"
                                                  data-confirm="Hapus data supir {{ $driver->name }}? Tindakan ini tidak dapat dibatalkan."
                                                  data-confirm-title="Hapus supir?"
                                                  data-confirm-icon="error"
                                                  data-confirm-button="Ya, hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
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

    {{ $drivers->links('pagination::bootstrap-5') }}
@endsection
