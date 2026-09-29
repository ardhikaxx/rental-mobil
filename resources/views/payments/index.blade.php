@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <x-page-header
        title="Pembayaran"
        subtitle="Riwayat seluruh pembayaran yang tercatat di sistem.">
        <x-slot name="actions">
            @can('manage-payments')
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Catat Pembayaran
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('payments.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. bayar, no. transaksi, nama">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="method">Metode</label>
                <select name="method" id="method" class="form-select">
                    <option value="">Semua metode</option>
                    @foreach ($methods as $value => $label)
                        <option value="{{ $value }}" @selected($filterMethod === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="type">Jenis</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Semua jenis</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected($filterType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="from">Dari tanggal</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ $filterFrom }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="to">Sampai</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ $filterTo }}">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter"></i></button>
                <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>

    <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted-2" style="font-size:.85rem">Total pembayaran tersaring</span>
        <strong style="font-size:1.05rem">{{ rupiah($totalFiltered) }}</strong>
    </div>

    <x-panel :flush="true">
        @if ($payments->isEmpty())
            <x-empty-state
                icon="fa-money-bill-wave"
                title="Tidak ada pembayaran"
                text="{{ $search || $filterMethod || $filterType ? 'Tidak ada pembayaran yang cocok dengan filter.' : 'Belum ada pembayaran tercatat.' }}">
                @can('manage-payments')
                    <x-slot name="action">
                        <a href="{{ route('payments.create') }}" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-plus me-1"></i> Catat Pembayaran
                        </a>
                    </x-slot>
                @endcan
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No. Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Transaksi</th>
                            <th>Jenis</th>
                            <th>Metode</th>
                            <th>Petugas</th>
                            <th class="text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="cell-title">{{ $payment->payment_number }}</td>
                                <td>{{ tanggal($payment->paid_at) }}</td>
                                <td>
                                    <a href="{{ route('transactions.show', $payment->transaction) }}" class="cell-title">
                                        {{ $payment->transaction?->transaction_number }}
                                    </a>
                                    <div class="cell-sub">{{ $payment->transaction?->customer?->name }} · {{ $payment->transaction?->vehicle?->license_plate }}</div>
                                </td>
                                <td><x-status-badge kind="payment_type" :value="$payment->type" /></td>
                                <td><x-status-badge kind="payment_method" :value="$payment->method" /></td>
                                <td class="cell-sub">{{ $payment->recorder?->name }}</td>
                                <td class="text-end-tabular cell-title">{{ rupiah($payment->amount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $payments->links('pagination::bootstrap-5') }}
@endsection
