@extends('layouts.app')

@section('title', 'Pengembalian')

@section('content')
    <x-page-header
        title="Jadwal Pengembalian"
        subtitle="Kendaraan yang sedang disewa, menunggu pemeriksaan kondisi akhir." />

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <x-stat icon="fa-road" label="Sedang Disewa" :value="$transactions->total()" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-calendar-day" label="Kembali Hari Ini" :value="$dueTodayCount" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-triangle-exclamation" label="Terlambat" :value="$overdueCount" />
        </div>
    </div>

    <form method="GET" action="{{ route('return.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. transaksi, nama pelanggan, no. polisi">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass me-1"></i> Cari</button>
                <a href="{{ route('return.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($transactions->isEmpty())
            <x-empty-state
                icon="fa-circle-check"
                title="Tidak ada kendaraan disewa"
                text="Semua kendaraan sudah kembali atau belum ada rental berjalan." />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Tenggat Kembali</th>
                            <th>Status</th>
                            <th class="text-end">Sisa Tagihan</th>
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
                                    <div class="cell-sub">Diserahkan {{ $transaction->handover_at ? tanggal_waktu($transaction->handover_at) : '-' }}</div>
                                </td>
                                <td>
                                    {{ $transaction->customer?->name }}
                                    <div class="cell-sub">{{ $transaction->customer?->phone }}</div>
                                </td>
                                <td>
                                    <span class="cell-title">{{ $transaction->vehicle?->code }}</span>
                                    <div class="cell-sub">{{ $transaction->vehicle?->license_plate }}</div>
                                </td>
                                <td>
                                    {{ tanggal($transaction->end_at) }} {{ jam($transaction->end_at) }}
                                    @if ($transaction->isOverdue())
                                        <div class="badge badge-danger mt-1">Terlambat</div>
                                    @elseif ($transaction->isDueSoon())
                                        <div class="badge badge-warning mt-1">Segera berakhir</div>
                                    @endif
                                </td>
                                <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                <td class="text-end-tabular">
                                    @php($balance = max(0, $transaction->totalPayable() - $transaction->paidAmount()))
                                    @if ($balance > 0)
                                        <span class="text-danger fw-semibold">{{ rupiah($balance) }}</span>
                                    @else
                                        <span class="text-success fw-semibold">Lunas</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('return.create', $transaction) }}" class="btn btn-sm btn-primary">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Proses
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
