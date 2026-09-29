@extends('layouts.app')

@section('title', 'Pelanggan')

@section('content')
    <x-page-header
        title="Pelanggan"
        subtitle="Data identitas penyewa dan riwayat rental mereka.">
        <x-slot name="actions">
            @can('manage-customers')
                <a href="{{ route('customers.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Tambah Pelanggan
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('customers.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control"
                       value="{{ $search }}" placeholder="Nama, nomor identitas, atau nomor telepon">
            </div>
            <div class="col-md-4">
                <label class="form-label" for="status">Status Verifikasi</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua Status Verifikasi</option>
                    <option value="verified" @selected($statusFilter === 'verified')>Terverifikasi</option>
                    <option value="pending" @selected($statusFilter === 'pending')>Menunggu Verifikasi</option>
                    <option value="rejected" @selected($statusFilter === 'rejected')>Ditolak</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($customers->isEmpty())
            <x-empty-state
                icon="fa-users"
                title="Tidak ada pelanggan ditemukan"
                text="{{ $search || $statusFilter ? 'Tidak ada pelanggan yang cocok dengan pencarian / filter Anda.' : 'Belum ada pelanggan terdaftar.' }}" />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. Identitas & SIM</th>
                            <th>Status Verifikasi</th>
                            <th>Telepon</th>
                            <th class="text-end">Transaksi</th>
                            <th class="text-end">Total Nilai</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>
                                    <a href="{{ route('customers.show', $customer) }}" class="cell-title">{{ $customer->name }}</a>
                                    <div class="cell-sub">{{ $customer->email ?: 'Tanpa email' }}</div>
                                </td>
                                <td>
                                    <div>{{ $customer->id_number }}</div>
                                    @if ($customer->sim_number)
                                        <div class="cell-sub"><i class="fa-solid fa-id-badge me-1"></i>SIM: {{ $customer->sim_number }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if ($customer->isVerified())
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">
                                            <i class="fa-solid fa-circle-check me-1"></i>Terverifikasi
                                        </span>
                                    @elseif ($customer->isPending())
                                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                            <i class="fa-solid fa-hourglass-half me-1"></i>Menunggu
                                        </span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="fa-solid fa-circle-xmark me-1"></i>Ditolak
                                        </span>
                                    @endif
                                </td>
                                <td>{{ $customer->phone }}</td>
                                <td class="text-end-tabular">{{ $customer->transactions_count }}x</td>
                                <td class="text-end-tabular">{{ rupiah($customer->total_spent ?? 0) }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @can('manage-customers')
                                            <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                            <form method="POST" action="{{ route('customers.destroy', $customer) }}"
                                                  data-confirm="Hapus data pelanggan {{ $customer->name }}? Tindakan ini tidak dapat dibatalkan."
                                                  data-confirm-title="Hapus pelanggan?"
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

    {{ $customers->links('pagination::bootstrap-5') }}
@endsection
