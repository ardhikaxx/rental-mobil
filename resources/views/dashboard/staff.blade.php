@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <x-page-header
        title="Dashboard Staf Garasi"
        subtitle="Kondisi kendaraan dan aktivitas lapangan {{ tanggal_panjang(now()) }}.">
        <x-slot name="actions">
            <a href="{{ route('inspections.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-clipboard-check me-1"></i> Pemeriksaan Baru
            </a>
            <a href="{{ route('handover.index') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-key me-1"></i> Jadwal Serah Terima
            </a>
            <a href="{{ route('return.index') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-rotate-left me-1"></i> Pengembalian
            </a>
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-person-walking-luggage" label="Harus Disiapkan" :value="$toPrepare->count()" />
        </div>
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-broom" label="Sedang Dibersihkan" :value="$cleaningCount" />
        </div>
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-gauge-high" label="Siap Jalan" :value="$readyCount" />
        </div>
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-road" label="Sedang Disewa" :value="$rentedVehicles" />
        </div>
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-clipboard-check" label="Perlu Pemeriksaan" :value="$needsInspection->count()" />
        </div>
        <div class="col-6 col-xl-2">
            <x-stat icon="fa-wrench" label="Dalam Perawatan" :value="$activeMaintenance->count()" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-panel title="Kendaraan Harus Disiapkan (H+1)" :flush="true">
                @if ($toPrepare->isEmpty())
                    <x-empty-state icon="fa-circle-check" title="Tidak ada persiapan"
                        text="Tidak ada booking yang memerlukan persiapan dalam 24 jam ke depan." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($toPrepare as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }}
                                            </a>
                                            <div class="cell-sub">
                                                {{ $transaction->vehicle?->license_plate }} · {{ $transaction->customer?->name }}
                                            </div>
                                        </td>
                                        <td class="text-end-tabular">
                                            <div class="cell-title">{{ tanggal($transaction->start_at) }} {{ jam($transaction->start_at) }}</div>
                                            <div class="cell-sub">{{ $transaction->transaction_number }}</div>
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
            <x-panel title="Perlu Pemeriksaan Kondisi Awal" :flush="true">
                @if ($needsInspection->isEmpty())
                    <x-empty-state icon="fa-clipboard-check" title="Semua terpantau"
                        text="Semua transaksi siap diserahkan sudah diperiksa." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($needsInspection as $transaction)
                                    <tr>
                                        <td>
                                            <span class="cell-title">{{ $transaction->vehicle?->code }}</span>
                                            <div class="cell-sub">{{ $transaction->vehicle?->license_plate }} · {{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="text-end-tabular cell-sub">
                                            {{ tanggal($transaction->start_at) }} {{ jam($transaction->start_at) }}
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('handover.create', $transaction) }}" class="btn btn-sm btn-primary">
                                                Periksa & Serahkan
                                            </a>
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

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <x-panel title="Serah Terima Hari Ini" :flush="true">
                @if ($handoverToday->isEmpty())
                    <x-empty-state icon="fa-calendar" title="Tidak ada jadwal" text="Tidak ada serah terima terjadwal hari ini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($handoverToday as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->vehicle?->license_plate }} · {{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="text-end-tabular cell-sub">{{ jam($transaction->start_at) }}</td>
                                        <td class="text-end">
                                            @if ($transaction->canBeHandedOver())
                                                <a href="{{ route('handover.create', $transaction) }}" class="btn btn-sm btn-outline-primary">Proses</a>
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

        <div class="col-lg-6">
            <x-panel title="Pengembalian Hari Ini" :flush="true">
                @if ($returnsToday->isEmpty())
                    <x-empty-state icon="fa-circle-check" title="Tidak ada jadwal" text="Tidak ada kendaraan yang harus kembali hari ini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($returnsToday as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->vehicle?->license_plate }} · {{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="text-end-tabular cell-sub">{{ jam($transaction->end_at) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('return.create', $transaction) }}" class="btn btn-sm btn-outline-primary">Proses</a>
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

    <div class="row g-3 mt-1">
        <div class="col-lg-6">
            <x-panel title="Kesiapan Kendaraan" :flush="true">
                @php($cleaningReady = \App\Models\Vehicle::whereIn('status', ['dibersihkan', 'siap_jalan', 'tersedia'])->orderBy('code')->limit(8)->get())
                @if ($cleaningReady->isEmpty())
                    <x-empty-state icon="fa-car" title="Belum ada kendaraan" text="Kendaraan akan muncul di sini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($cleaningReady as $vehicle)
                                    <tr>
                                        <td>
                                            <a href="{{ route('vehicles.show', $vehicle) }}" class="cell-title">{{ $vehicle->code }}</a>
                                            <div class="cell-sub">{{ $vehicle->brand }} {{ $vehicle->model }} · {{ $vehicle->license_plate }}</div>
                                        </td>
                                        <td><x-status-badge kind="vehicle" :value="$vehicle->status" /></td>
                                        <td class="text-end">
                                            <div class="d-flex gap-1 justify-content-end">
                                                @can('update-vehicle-status')
                                                    @if ($vehicle->status->value === 'tersedia' || $vehicle->status->value === 'siap_jalan')
                                                        <form method="POST" action="{{ route('vehicles.mark-cleaning', $vehicle) }}"
                                                              data-confirm="Mulai pembersihan kendaraan {{ $vehicle->code }}?"
                                                              data-confirm-title="Mulai pembersihan"
                                                              data-confirm-button="Ya, mulai">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Mulai dibersihkan">
                                                                <i class="fa-solid fa-broom"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($vehicle->status->value === 'dibersihkan')
                                                        <form method="POST" action="{{ route('vehicles.mark-ready', $vehicle) }}"
                                                              data-confirm="Tandai {{ $vehicle->code }} sebagai siap jalan?"
                                                              data-confirm-title="Siap jalan"
                                                              data-confirm-button="Ya, siap jalan">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai siap jalan">
                                                                <i class="fa-solid fa-gauge-high"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    @if ($vehicle->status->value === 'siap_jalan')
                                                        <form method="POST" action="{{ route('vehicles.mark-available', $vehicle) }}"
                                                              data-confirm="Tandai {{ $vehicle->code }} kembali tersedia untuk disewakan?"
                                                              data-confirm-title="Tandai tersedia"
                                                              data-confirm-button="Ya, tersedia">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Tandai tersedia">
                                                                <i class="fa-solid fa-circle-check"></i>
                                                            </button>
                                                        </form>
                                                    @endif
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
        </div>

        <div class="col-lg-6">
            <x-panel title="Perawatan Berjalan" :flush="true">
                @if ($activeMaintenance->isEmpty())
                    <x-empty-state icon="fa-screwdriver-wrench" title="Tidak ada perawatan" text="Tidak ada kendaraan yang sedang dirawat." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($activeMaintenance as $maintenance)
                                    <tr>
                                        <td>
                                            <a href="{{ route('maintenances.show', $maintenance) }}" class="cell-title">
                                                {{ $maintenance->vehicle?->code }} — {{ $maintenance->type->label() }}
                                            </a>
                                            <div class="cell-sub">{{ \Illuminate\Support\Str::limit($maintenance->description, 60) }}</div>
                                        </td>
                                        <td><x-status-badge kind="maintenance" :value="$maintenance->status" /></td>
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
