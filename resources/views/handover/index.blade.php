@extends('layouts.app')

@section('title', 'Serah Terima')

@section('content')
    <x-page-header
        title="Jadwal Serah Terima"
        subtitle="Booking yang menunggu pemeriksaan kondisi awal dan serah terima kendaraan." />

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <x-stat icon="fa-key" label="Menunggu Serah Terima" :value="$transactions->total()" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-calendar-check" label="Disetujui" :value="$bookedCount" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-clipboard-check" label="Siap Diserahkan" :value="$readyCount" />
        </div>
    </div>

    <form method="GET" action="{{ route('handover.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. transaksi, nama pelanggan, no. polisi">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Cari</button>
                <a href="{{ route('handover.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($transactions->isEmpty())
            <x-empty-state
                icon="fa-calendar-check"
                title="Tidak ada jadwal serah terima"
                text="Belum ada booking yang menunggu serah terima.">
                <x-slot name="action">
                    <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">
                        <i class="fa-solid fa-file-invoice me-1"></i> Lihat Transaksi
                    </a>
                </x-slot>
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Jadwal Mulai</th>
                            <th>Status</th>
                            <th>Pemeriksaan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                        {{ $transaction->transaction_number }}
                                    </a>
                                    <div class="cell-sub">{{ $transaction->rental_days }} hari</div>
                                </td>
                                <td>
                                    {{ $transaction->customer?->name }}
                                    <div class="cell-sub">{{ $transaction->customer?->phone }}</div>
                                </td>
                                <td>
                                    <span class="cell-title">{{ $transaction->vehicle?->code }}</span>
                                    <div class="cell-sub">{{ $transaction->vehicle?->license_plate }}</div>
                                </td>
                                <td class="cell-sub">
                                    {{ tanggal($transaction->start_at) }} {{ jam($transaction->start_at) }}
                                    @if ($transaction->start_at->isPast() && $transaction->status->value === 'booked')
                                        <span class="badge badge-warning mt-1">Lewat jadwal</span>
                                    @endif
                                </td>
                                <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                <td>
                                    @if (in_array($transaction->id, $inspectedIds->all()))
                                        <span class="badge badge-success"><i class="fa-solid fa-check"></i> Sudah diperiksa</span>
                                    @else
                                        <span class="badge badge-secondary">Belum</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('handover.create', $transaction) }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-key me-1"></i> Proses
                                        </a>
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $transactions->links('pagination::bootstrap-5') }}
@endsection
