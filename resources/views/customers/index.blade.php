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
                text="{{ $search ? 'Tidak ada pelanggan yang cocok dengan pencarian Anda.' : 'Belum ada pelanggan terdaftar.' }}" />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>No. Identitas</th>
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
                                <td>{{ $customer->id_number }}</td>
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
