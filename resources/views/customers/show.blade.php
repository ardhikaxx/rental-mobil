@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pelanggan', 'url' => route('customers.index')],
        ['label' => $customer->name],
    ]" />

    <x-page-header
        :title="$customer->name"
        :subtitle="$customer->id_number.' · '.$customer->phone.($customer->email ? ' · '.$customer->email : '')">
        <x-slot name="actions">
            @can('manage-customers')
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen me-1"></i> Ubah
                </a>
                <a href="{{ route('transactions.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Transaksi Baru
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-file-invoice" label="Total Transaksi" :value="$stats['totalTransactions']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-spinner" label="Transaksi Aktif" :value="$stats['activeTransactions']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-money-bill-wave" label="Total Nilai Rental" :value="rupiah($stats['totalValue'])" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-calendar" label="Transaksi Terakhir"
                :value="$stats['lastTransaction'] ? tanggal($stats['lastTransaction']->start_at) : '-'"
                sub="periode mulai" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <x-panel title="Informasi Pelanggan">
                <div class="kv"><span class="kv-label">Nomor identitas</span><span class="kv-value">{{ $customer->id_number }}</span></div>
                <div class="kv"><span class="kv-label">Telepon</span><span class="kv-value">{{ $customer->phone }}</span></div>
                <div class="kv"><span class="kv-label">Email</span><span class="kv-value">{{ $customer->email ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Tanggal lahir</span><span class="kv-value">{{ $customer->birth_date ? tanggal($customer->birth_date) : '-' }}</span></div>
                <div class="kv"><span class="kv-label">Alamat</span><span class="kv-value">{{ $customer->address ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Terdaftar</span><span class="kv-value">{{ tanggal($customer->created_at) }}</span></div>

                @if ($customer->notes)
                    <div class="form-hint mt-3">{{ $customer->notes }}</div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-8">
            <x-panel title="Riwayat Rental" :flush="true">
                @if ($transactions->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="Belum ada transaksi"
                                   text="Pelanggan ini belum pernah melakukan rental.">
                        @can('manage-customers')
                            <x-slot name="action">
                                <a href="{{ route('transactions.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1"></i> Buat Transaksi
                                </a>
                            </x-slot>
                        @endcan
                    </x-empty-state>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Kendaraan</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->booking_source->label() }}</div>
                                        </td>
                                        <td class="cell-sub">
                                            {{ $transaction->vehicle?->code }}<br>{{ $transaction->vehicle?->license_plate }}
                                        </td>
                                        <td class="cell-sub">
                                            {{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}
                                        </td>
                                        <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                        <td class="text-end-tabular">{{ rupiah($transaction->total) }}</td>
                                        <td class="text-end-tabular">
                                            @php($balance = $transaction->totalPayable() - (int) ($transaction->paid_amount ?? $transaction->payments()->sum('amount')))
                                            @if ($transaction->status->value === 'cancelled')
                                                <span class="text-muted-2">-</span>
                                            @elseif ($balance > 0)
                                                <span class="text-danger">{{ rupiah($balance) }}</span>
                                            @else
                                                <span class="text-success">Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>

            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
