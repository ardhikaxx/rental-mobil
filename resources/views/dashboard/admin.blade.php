@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header
        title="Dashboard Operasional"
        subtitle="Aktivitas transaksi harian {{ tanggal_panjang(now()) }}.">
        <x-slot name="actions">
            <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-plus me-1"></i> Transaksi Baru
            </a>
            <a href="{{ route('calendar.index') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-calendar-days me-1"></i> Kalender Booking
            </a>
            <a href="{{ route('payments.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-money-bill-transfer me-1"></i> Catat Pembayaran
            </a>
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-key" label="Mulai Hari Ini" :value="$startsToday->count()" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-car-side" label="Harus Diserahkan" :value="$pendingHandover->count()" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-road" label="Sedang Disewa" :value="$rentedCount" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-rotate-left" label="Kembali Hari Ini" :value="$returnsToday->count()" />
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-4">
            <x-stat icon="fa-triangle-exclamation" label="Transaksi Terlambat" :value="$overdue->count()" />
        </div>
        <div class="col-6 col-xl-4">
            <x-stat icon="fa-file-circle-minus" label="Belum Lunas" :value="rupiah($unpaidTotal)"
                :sub="count($unpaid).' transaksi'" />
        </div>
        <div class="col-6 col-xl-4">
            <x-stat icon="fa-money-bill-wave" label="Pembayaran Terbaru" :value="count($latestPayments)" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-panel title="Jadwal Serah Terima Hari Ini" :flush="true">
                @if ($pendingHandover->isEmpty())
                    <x-empty-state icon="fa-calendar-check" title="Tidak ada jadwal"
                        text="Belum ada booking yang menunggu serah terima." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($pendingHandover as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">
                                                {{ $transaction->customer?->name }} · {{ $transaction->vehicle?->license_plate }}
                                            </div>
                                        </td>
                                        <td class="text-end-tabular">
                                            <div class="cell-title">{{ tanggal($transaction->start_at) }}</div>
                                            <div class="cell-sub">pukul {{ jam($transaction->start_at) }}</div>
                                        </td>
                                        <td class="text-end">
                                            <x-status-badge kind="transaction" :value="$transaction->status" />
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-6">
            <x-panel title="Pengembalian Hari Ini & Terlambat" :flush="true">
                @php($returns = $overdue->merge($returnsToday)->unique('id'))
                @if ($returns->isEmpty())
                    <x-empty-state icon="fa-circle-check" title="Tidak ada pengembalian"
                        text="Semua kendaraan yang disewa masih sesuai jadwal." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($returns as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">
                                                {{ $transaction->customer?->name }} · {{ $transaction->vehicle?->license_plate }}
                                            </div>
                                        </td>
                                        <td class="text-end-tabular">
                                            <div class="cell-title">{{ tanggal($transaction->end_at) }} {{ jam($transaction->end_at) }}</div>
                                            @if ($transaction->isOverdue())
                                                <span class="badge badge-danger mt-1">Lewat batas</span>
                                            @elseif ($transaction->isDueSoon())
                                                <span class="badge badge-warning mt-1">Segera berakhir</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if ($transaction->canBeReturned())
                                                <a href="{{ route('return.create', $transaction) }}" class="btn btn-sm btn-outline-primary">
                                                    Proses
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-panel title="Transaksi Belum Lunas" :flush="true">
                @if ($unpaid->isEmpty())
                    <x-empty-state icon="fa-circle-check" title="Semua lunas"
                        text="Tidak ada tagihan yang menunggu pembayaran." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($unpaid as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="text-end-tabular">
                                            <div class="cell-title text-danger">{{ rupiah($transaction->balance()) }}</div>
                                            <div class="cell-sub">dari {{ rupiah($transaction->totalPayable()) }}</div>
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('payments.create', ['transaction_id' => $transaction->id]) }}"
                                               class="btn btn-sm btn-outline-primary">Bayar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-6">
            <x-panel title="Pembayaran Terbaru" :flush="true">
                @if ($latestPayments->isEmpty())
                    <x-empty-state icon="fa-money-bill-wave" title="Belum ada pembayaran"
                        text="Pembayaran yang dicatat akan tampil di sini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($latestPayments as $payment)
                                    <tr>
                                        <td>
                                            <span class="cell-title">{{ $payment->payment_number }}</span>
                                            <div class="cell-sub">{{ $payment->transaction?->transaction_number }} · {{ $payment->transaction?->customer?->name }}</div>
                                        </td>
                                        <td>
                                            <x-status-badge kind="payment_type" :value="$payment->type" />
                                        </td>
                                        <td class="text-end-tabular">
                                            <div class="cell-title">{{ rupiah($payment->amount) }}</div>
                                            <div class="cell-sub">{{ tanggal($payment->paid_at) }} · {{ $payment->method->label() }}</div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>
    </div>

    <div class="mt-3">
        <x-panel title="Pintasan Operasional">
            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('customers.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari Pelanggan
                </a>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-list me-1"></i> Daftar Transaksi
                </a>
                <a href="{{ route('handover.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-key me-1"></i> Jadwal Serah Terima
                </a>
                <a href="{{ route('return.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-rotate-left me-1"></i> Proses Pengembalian
                </a>
            </div>
        </x-panel>
    </div>
@endsection
