@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <x-page-header
        title="Transaksi Rental"
        subtitle="Seluruh booking dan transaksi rental dalam sistem.">
        <x-slot name="actions">
            @can('manage-transactions')
                <a href="{{ route('transactions.create') }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Transaksi Baru
                </a>
            @endcan
            @can('view-calendar')
                <a href="{{ route('calendar.index') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-calendar-days me-1"></i> Kalender
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('transactions.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. transaksi, nama, no. polisi">
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
                <label class="form-label" for="payment">Pembayaran</label>
                <select name="payment" id="payment" class="form-select">
                    <option value="">Semua</option>
                    <option value="unpaid" @selected($filterPayment === 'unpaid')>Belum lunas</option>
                    <option value="paid" @selected($filterPayment === 'paid')>Sudah lunas</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="from">Mulai rental dari</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ $filterFrom }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="to">Sampai</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ $filterTo }}">
            </div>
            <div class="col-md-1 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">
                    <i class="fa-solid fa-filter me-1"></i>
                </button>
                <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary" title="Reset">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-3">
                <select name="sort" class="form-select" onchange="this.form.submit()">
                    <option value="newest" @selected($sort === 'newest')>Terbaru dibuat</option>
                    <option value="oldest" @selected($sort === 'oldest')>Terlama dibuat</option>
                    <option value="start_desc" @selected($sort === 'start_desc')>Rental terbaru</option>
                    <option value="start_asc" @selected($sort === 'start_asc')>Rental terlama</option>
                    <option value="total_desc" @selected($sort === 'total_desc')>Total terbesar</option>
                </select>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($transactions->isEmpty())
            <x-empty-state
                icon="fa-file-invoice"
                title="Tidak ada transaksi"
                text="{{ $search || $filterStatus || $filterPayment ? 'Tidak ada transaksi yang cocok dengan filter Anda.' : 'Belum ada transaksi rental. Mulai dengan membuat transaksi baru.' }}">
                @if (! $search && ! $filterStatus && ! $filterPayment)
                    <x-slot name="action">
                        @can('manage-transactions')
                            <a href="{{ route('transactions.create') }}" class="btn btn-primary btn-sm">
                                <i class="fa-solid fa-plus me-1"></i> Transaksi Baru
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
                            <th>No. Transaksi</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-end">Total</th>
                            <th class="text-end">Sisa Tagihan</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transactions as $transaction)
                            @php($paid = (int) ($transaction->paid_amount ?? 0))
                            @php($balance = $transaction->status->value === 'cancelled' ? 0 : max(0, $transaction->total + $transaction->late_fee - $paid))
                            <tr>
                                <td>
                                    <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                        {{ $transaction->transaction_number }}
                                    </a>
                                    <div class="cell-sub">
                                        <i class="fa-solid {{ $transaction->booking_source->icon() }} me-1"></i>
                                        {{ $transaction->booking_source->label() }} · {{ tanggal($transaction->created_at) }}
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ route('customers.show', $transaction->customer) }}" class="cell-title">
                                        {{ $transaction->customer?->name }}
                                    </a>
                                    <div class="cell-sub">{{ $transaction->customer?->phone }}</div>
                                </td>
                                <td>
                                    <span class="cell-title">{{ $transaction->vehicle?->code }}</span>
                                    <div class="cell-sub">{{ $transaction->vehicle?->license_plate }}</div>
                                </td>
                                <td class="cell-sub">
                                    {{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}
                                    <br>{{ $transaction->rental_days }} hari
                                    @if ($transaction->isOverdue())
                                        <span class="badge badge-danger mt-1">Terlambat</span>
                                    @elseif ($transaction->isDueSoon())
                                        <span class="badge badge-warning mt-1">Segera berakhir</span>
                                    @endif
                                </td>
                                <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                <td class="text-end-tabular">{{ rupiah($transaction->total) }}</td>
                                <td class="text-end-tabular">
                                    @if ($transaction->status->value === 'cancelled')
                                        <span class="text-muted-2">-</span>
                                    @elseif ($balance > 0)
                                        <span class="text-danger fw-semibold">{{ rupiah($balance) }}</span>
                                    @else
                                        <span class="text-success fw-semibold">Lunas</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        @if ($transaction->canBeHandedOver())
                                            <a href="{{ route('handover.create', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Serah terima">
                                                <i class="fa-solid fa-key"></i>
                                            </a>
                                        @endif
                                        @if ($transaction->canBeReturned())
                                            <a href="{{ route('return.create', $transaction) }}" class="btn btn-sm btn-outline-primary" title="Pengembalian">
                                                <i class="fa-solid fa-rotate-left"></i>
                                            </a>
                                        @endif
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
