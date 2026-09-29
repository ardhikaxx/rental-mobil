@extends('layouts.app')

@section('title', 'Laporan')

@section('content')
    <x-page-header
        title="Laporan Operasional & Keuangan"
        subtitle="Data faktual untuk evaluasi bisnis. Seluruh angka dihitung langsung dari database.">
        <x-slot name="actions">
            <a href="{{ route('reports.export', request()->query()) }}" class="btn btn-success">
                <i class="fa-solid fa-file-excel me-1"></i> Ekspor ke Excel (CSV)
            </a>
        </x-slot>
    </x-page-header>

    @php($tabs = [
        'income' => ['label' => 'Laporan Pemasukan', 'icon' => 'fa-money-bill-wave'],
        'vehicles' => ['label' => 'Performa Kendaraan', 'icon' => 'fa-car-side'],
        'customers' => ['label' => 'Laporan Pelanggan', 'icon' => 'fa-users'],
    ])

    <ul class="nav nav-pills mb-3 gap-2">
        @foreach ($tabs as $key => $tab)
            <li class="nav-item">
                <a class="nav-link {{ $type === $key ? 'active' : 'btn btn-outline-primary' }}"
                   style="{{ $type === $key ? '' : 'border:1px solid #2563eb' }}"
                   href="{{ route('reports.index', array_merge(request()->query(), ['type' => $key])) }}">
                    <i class="fa-solid {{ $tab['icon'] }} me-1"></i> {{ $tab['label'] }}
                </a>
            </li>
        @endforeach
    </ul>

    <form method="GET" action="{{ route('reports.index') }}" class="filter-bar">
        <input type="hidden" name="type" value="{{ $type }}">
        <div class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label" for="preset">Periode</label>
                <select name="preset" id="preset" class="form-select">
                    <option value="today" @selected($preset === 'today')>Hari ini</option>
                    <option value="week" @selected($preset === 'week')>Minggu ini</option>
                    <option value="month" @selected($preset === 'month')>Bulan ini</option>
                    <option value="custom" @selected($preset === 'custom')>Rentang kustom</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="from">Dari</label>
                <input type="date" name="from" id="from" class="form-control" value="{{ $from }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="to">Sampai</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ $to }}">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="vehicle_id">Kendaraan</label>
                <select name="vehicle_id" id="vehicle_id" class="form-select">
                    <option value="">Semua kendaraan</option>
                    @foreach ($vehicles as $vehicle)
                        <option value="{{ $vehicle->id }}" @selected((string) $filterVehicle === (string) $vehicle->id)>
                            {{ $vehicle->code }} — {{ $vehicle->license_plate }}
                        </option>
                    @endforeach
                </select>
            </div>
            @if ($type === 'income')
                <div class="col-md-2">
                    <label class="form-label" for="status">Status Transaksi</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Semua status</option>
                        @foreach (\App\Enums\TransactionStatus::filterOptions() as $value => $label)
                            <option value="{{ $value }}" @selected($filterStatus === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label" for="method">Metode</label>
                    <select name="method" id="method" class="form-select">
                        <option value="">Semua</option>
                        @foreach (\App\Enums\PaymentMethod::options() as $value => $label)
                            <option value="{{ $value }}" @selected($filterMethod === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            @endif
            @if ($type !== 'income')
                <div class="col-md-3">
                    <label class="form-label" for="search">Pencarian</label>
                    <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                           placeholder="{{ $type === 'vehicles' ? 'Kode unit, no. polisi, merek' : 'Nama, no. identitas, telepon' }}">
                </div>
            @endif
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                <a href="{{ route('reports.index', ['type' => $type]) }}" class="btn btn-outline-secondary" title="Reset"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>

    <div class="text-muted-2 mb-3" style="font-size:.85rem">
        Periode: <strong>{{ tanggal($from) }}</strong> sampai <strong>{{ tanggal($to) }}</strong>
        @if ($preset !== 'custom') · preset {{ $preset === 'today' ? 'hari ini' : ($preset === 'week' ? 'minggu ini' : 'bulan ini') }} @endif
    </div>

    @if ($type === 'income')
        <div class="row g-3 mb-3">
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-file-invoice" label="Total Transaksi" :value="$transactionCount" />
            </div>
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-chart-line" label="Omzet Rental" :value="rupiah($omzet)" />
            </div>
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-money-bill-transfer" label="Total Pembayaran" :value="rupiah($paymentsTotal)" />
            </div>
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-gavel" label="Denda Dibayar" :value="rupiah($dendaCollected)" />
            </div>
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-tags" label="Total Diskon" :value="rupiah($discountTotal)" />
            </div>
            <div class="col-6 col-xl-2">
                <x-stat icon="fa-file-circle-minus" label="Piutang Periode" :value="rupiah($outstanding)" />
            </div>
        </div>

        <div class="row g-3">
            <div class="col-lg-5">
                <x-panel title="Pemasukan Per Hari" :flush="true">
                    @if ($dailyPayments->isEmpty())
                        <x-empty-state icon="fa-calendar-xmark" title="Belum ada pemasukan"
                                       text="Tidak ada pembayaran tercatat pada periode ini." />
                    @else
                        <div class="table-responsive" style="max-height:340px;overflow-y:auto">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr><th>Tanggal</th><th class="text-end">Pemasukan</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($dailyPayments as $day => $total)
                                        <tr>
                                            <td>{{ tanggal($day) }}</td>
                                            <td class="text-end-tabular">{{ rupiah($total) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-panel>
            </div>

            <div class="col-lg-7">
                <x-panel title="Detail Transaksi Periode" :flush="true">
                    @if ($details->isEmpty())
                        <x-empty-state icon="fa-file-invoice" title="Tidak ada transaksi"
                                       text="Tidak ada transaksi dengan filter dan periode ini." />
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Transaksi</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-end">Dibayar</th>
                                        <th class="text-end">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $detail)
                                        @php($paid = (int) ($detail->paid_amount ?? 0))
                                        <tr>
                                            <td>
                                                <a href="{{ route('transactions.show', $detail) }}" class="cell-title">
                                                    {{ $detail->transaction_number }}
                                                </a>
                                                <div class="cell-sub">{{ $detail->customer?->name }} · {{ $detail->vehicle?->license_plate }}</div>
                                            </td>
                                            <td class="cell-sub">{{ tanggal($detail->start_at) }} — {{ tanggal($detail->end_at) }}</td>
                                            <td><x-status-badge kind="transaction" :value="$detail->status" /></td>
                                            <td class="text-end-tabular">{{ rupiah($detail->total) }}</td>
                                            <td class="text-end-tabular">{{ rupiah($paid) }}</td>
                                            <td class="text-end-tabular {{ $detail->total + $detail->late_fee - $paid > 0 ? 'text-danger' : '' }}">
                                                {{ rupiah(max(0, $detail->total + $detail->late_fee - $paid)) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="panel-body">{{ $details->links('pagination::bootstrap-5') }}</div>
                    @endif
                </x-panel>
            </div>
        </div>
    @elseif ($type === 'vehicles')
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <x-stat icon="fa-money-bill-wave" label="Total Pendapatan Armada" :value="rupiah($totalRevenue)" />
            </div>
            <div class="col-md-6">
                <x-stat icon="fa-wrench" label="Total Biaya Perawatan" :value="rupiah($totalMaintenanceCost)" />
            </div>
        </div>

        <x-panel :flush="true">
            @if ($vehicleRows->isEmpty())
                <x-empty-state icon="fa-car" title="Belum ada kendaraan" text="Belum ada data kendaraan untuk dilaporkan." />
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Kendaraan</th>
                                <th class="text-end">Kali Disewa</th>
                                <th class="text-end">Total Hari Sewa</th>
                                <th class="text-end">Pendapatan</th>
                                <th class="text-end">Kali Perawatan</th>
                                <th class="text-end">Biaya Perawatan</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vehicleRows as $row)
                                <tr>
                                    <td>
                                        <span class="cell-title">{{ $row['vehicle']->code }} — {{ $row['vehicle']->brand }} {{ $row['vehicle']->model }}</span>
                                        <div class="cell-sub">{{ $row['vehicle']->license_plate }}</div>
                                    </td>
                                    <td class="text-end-tabular">{{ $row['rentals'] }}x</td>
                                    <td class="text-end-tabular">{{ $row['days'] }} hari</td>
                                    <td class="text-end-tabular cell-title">{{ rupiah($row['revenue']) }}</td>
                                    <td class="text-end-tabular">{{ $row['maintenanceCount'] }}x</td>
                                    <td class="text-end-tabular">{{ rupiah($row['maintenanceCost']) }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('vehicles.show', $row['vehicle']) }}" class="btn btn-sm btn-outline-primary" title="Riwayat">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-panel>
    @else
        <x-panel :flush="true">
            @if ($customersReport->isEmpty())
                <x-empty-state icon="fa-users" title="Belum ada data pelanggan"
                               text="Belum ada pelanggan dengan riwayat transaksi." />
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Pelanggan</th>
                                <th>Telepon</th>
                                <th class="text-end">Transaksi</th>
                                <th class="text-end">Aktif</th>
                                <th class="text-end">Total Nilai</th>
                                <th>Terakhir Sewa</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($customersReport as $customer)
                                <tr>
                                    <td>
                                        <a href="{{ route('customers.show', $customer) }}" class="cell-title">{{ $customer->name }}</a>
                                        <div class="cell-sub">{{ $customer->id_number }}</div>
                                    </td>
                                    <td>{{ $customer->phone }}</td>
                                    <td class="text-end-tabular">{{ $customer->transactions_count }}x</td>
                                    <td class="text-end-tabular">
                                        @if ($customer->active_transactions > 0)
                                            <span class="badge badge-primary">{{ $customer->active_transactions }}</span>
                                        @else
                                            <span class="text-muted-2">0</span>
                                        @endif
                                    </td>
                                    <td class="text-end-tabular cell-title">{{ rupiah($customer->total_value ?? 0) }}</td>
                                    <td class="cell-sub">{{ $customer->last_rental ? tanggal($customer->last_rental) : '-' }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-sm btn-outline-primary" title="Riwayat">
                                            <i class="fa-solid fa-arrow-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="panel-body">{{ $customersReport->links('pagination::bootstrap-5') }}</div>
            @endif
        </x-panel>
    @endif
@endsection
