@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header
        title="Dashboard Pemilik"
        subtitle="Ringkasan bisnis dan kondisi operasional {{ tanggal_panjang(now()) }}." />

    {{-- Ringkasan operasional --}}
    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-car-side" label="Total Kendaraan" :value="$totalVehicles" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-circle-check" label="Kendaraan Tersedia" :value="$availableVehicles" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-road" label="Sedang Disewa" :value="$rentedVehicles" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-wrench" label="Dalam Perawatan" :value="$maintenanceVehicles" />
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-file-invoice" label="Transaksi Aktif" :value="$activeTransactions" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-circle-check" label="Transaksi Selesai" :value="$completedTransactions" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-money-bill-wave" label="Pemasukan Bulan Ini" :value="rupiah($monthIncome)" :sub="'Hari ini '.rupiah($todayIncome)" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-file-circle-minus" label="Piutang Belum Lunas" :value="rupiah($outstanding)" />
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <x-panel title="Pemasukan 6 Bulan Terakhir">
                <canvas id="incomeChart" height="220"></canvas>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Kendaraan Paling Sering Disewa" :flush="true">
                @if ($topRented->isEmpty())
                    <x-empty-state icon="fa-car" title="Belum ada data"
                        text="Belum ada transaksi yang tercatat untuk periode ini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kendaraan</th>
                                    <th class="text-end">Sewa</th>
                                    <th class="text-end">Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topRented as $row)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vehicles.show', $row->vehicle) }}" class="cell-title">
                                                {{ $row->vehicle?->brand }} {{ $row->vehicle?->model }}
                                            </a>
                                            <div class="cell-sub">{{ $row->vehicle?->license_plate }}</div>
                                        </td>
                                        <td class="text-end-tabular">{{ $row->total_rentals }}x</td>
                                        <td class="text-end-tabular">{{ rupiah($row->revenue) }}</td>
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
            <x-panel title="Kendaraan Sering Masuk Perawatan" :flush="true">
                @if ($frequentMaintenance->isEmpty())
                    <x-empty-state icon="fa-screwdriver-wrench" title="Tidak ada riwayat perawatan"
                        text="Semua kendaraan belum memiliki catatan perawatan." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kendaraan</th>
                                    <th class="text-end">Kali</th>
                                    <th class="text-end">Biaya</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($frequentMaintenance as $row)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vehicles.show', $row->vehicle) }}" class="cell-title">
                                                {{ $row->vehicle?->brand }} {{ $row->vehicle?->model }}
                                            </a>
                                            <div class="cell-sub">{{ $row->vehicle?->license_plate }}</div>
                                        </td>
                                        <td class="text-end-tabular">{{ $row->total }}x</td>
                                        <td class="text-end-tabular">{{ rupiah($row->total_cost) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-6">
            <x-panel title="Transaksi Terbaru" :flush="true">
                @if ($recentTransactions->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="Belum ada transaksi"
                        text="Transaksi rental akan muncul di sini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Pelanggan</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ tanggal($transaction->created_at) }}</div>
                                        </td>
                                        <td>{{ $transaction->customer?->name }}</td>
                                        <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                        <td class="text-end-tabular">{{ rupiah($transaction->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        const incomeData = @json($incomeChart);
        const ctx = document.getElementById('incomeChart');
        if (ctx) {
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: incomeData.map(item => item.label),
                    datasets: [{
                        label: 'Pemasukan',
                        data: incomeData.map(item => item.total),
                        backgroundColor: '#2563eb',
                        borderRadius: 4,
                        maxBarThickness: 42,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: context => 'Rp ' + Number(context.raw).toLocaleString('id-ID'),
                            },
                        },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => 'Rp ' + Number(value).toLocaleString('id-ID'),
                            },
                            grid: { color: '#eef0f3' },
                        },
                        x: { grid: { display: false } },
                    },
                },
            });
        }
    </script>
@endpush
