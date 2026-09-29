@extends('layouts.app')

@section('title', 'Detail Kendaraan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Kendaraan', 'url' => route('vehicles.index')],
        ['label' => $vehicle->code],
    ]" />

    <x-page-header
        :title="$vehicle->code.' — '.$vehicle->brand.' '.$vehicle->model"
        :subtitle="\App\Enums\VehicleType::tryFrom($vehicle->type)?->label().' · '.$vehicle->year.' · '.$vehicle->color.' · '.($vehicle->is_active ? 'Aktif' : 'Nonaktif')">
        <x-slot name="actions">
            @can('edit-vehicle')
                <a href="{{ route('vehicles.edit', $vehicle) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen me-1"></i> Ubah
                </a>
            @endcan
            <a href="{{ route('inspections.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-clipboard-check me-1"></i> Pemeriksaan Baru
            </a>
            @can('create-maintenance')
                <a href="{{ route('maintenances.create', ['vehicle_id' => $vehicle->id]) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-wrench me-1"></i> Catat Perawatan
                </a>
            @endcan
            @if ($stats['activeBooking'])
                <a href="{{ route('transactions.show', $stats['activeBooking']) }}" class="btn btn-primary">
                    <i class="fa-solid fa-file-invoice me-1"></i> Transaksi Aktif
                </a>
            @endif
        </x-slot>
    </x-page-header>

    @php($staffActions = auth()->user()->hasRole(\App\Enums\UserRole::SuperAdmin, \App\Enums\UserRole::Staff))

    @if ($vehicle->status->value === 'dibersihkan' && $staffActions)
        <div class="panel mb-3" style="border-left:3px solid #d97706">
            <div class="panel-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <strong>Kendaraan sedang dibersihkan.</strong>
                    <span class="text-muted-2">Tandai siap jalan setelah pembersihan selesai.</span>
                </div>
                <form method="POST" action="{{ route('vehicles.mark-ready', $vehicle) }}"
                      data-confirm="Tandai {{ $vehicle->code }} siap jalan?"
                      data-confirm-title="Siap jalan"
                      data-confirm-button="Ya, siap jalan">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-gauge-high me-1"></i> Tandai Siap Jalan
                    </button>
                </form>
            </div>
        </div>
    @endif

    @if ($vehicle->status->value === 'siap_jalan' && $staffActions)
        <div class="panel mb-3" style="border-left:3px solid #16a34a">
            <div class="panel-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <strong>Kendaraan siap jalan.</strong>
                    <span class="text-muted-2">Tandai tersedia bila siap disewakan kembali.</span>
                </div>
                <form method="POST" action="{{ route('vehicles.mark-available', $vehicle) }}"
                      data-confirm="Tandai {{ $vehicle->code }} tersedia untuk disewakan?"
                      data-confirm-title="Tandai tersedia"
                      data-confirm-button="Ya, tersedia">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-circle-check me-1"></i> Tandai Tersedia
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-file-invoice" label="Total Rental" :value="$stats['totalRentals']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-money-bill-wave" label="Pendapatan" :value="rupiah($stats['revenue'])" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-wrench" label="Kali Perawatan" :value="$stats['maintenanceCount']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-receipt" label="Biaya Perawatan" :value="rupiah($stats['maintenanceCost'])" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <x-panel title="Informasi Kendaraan">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="avatar-circle" style="width:64px;height:48px;border-radius:8px">
                        @if ($vehicle->photo)
                            <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="{{ $vehicle->code }}">
                        @else
                            <i class="fa-solid fa-car-side"></i>
                        @endif
                    </span>
                    <div>
                        <x-status-badge kind="vehicle" :value="$vehicle->status" />
                        <div class="cell-sub mt-1">{{ $vehicle->is_active ? 'Kendaraan aktif' : 'Kendaraan nonaktif' }}</div>
                    </div>
                </div>

                <div class="kv"><span class="kv-label">Nomor polisi</span><span class="kv-value">{{ $vehicle->license_plate }}</span></div>
                <div class="kv"><span class="kv-label">Tipe</span><span class="kv-value">{{ \App\Enums\VehicleType::tryFrom($vehicle->type)?->label() ?? $vehicle->type }}</span></div>
                <div class="kv"><span class="kv-label">Tahun</span><span class="kv-value">{{ $vehicle->year }}</span></div>
                <div class="kv"><span class="kv-label">Warna</span><span class="kv-value">{{ $vehicle->color }}</span></div>
                <div class="kv"><span class="kv-label">Nomor rangka</span><span class="kv-value">{{ $vehicle->chassis_number ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Nomor mesin</span><span class="kv-value">{{ $vehicle->engine_number ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Tarif harian</span><span class="kv-value">{{ rupiah($vehicle->daily_rate) }}</span></div>
                <div class="kv"><span class="kv-label">Kilometer terakhir</span><span class="kv-value">{{ number_format($vehicle->odometer) }} km</span></div>
                <div class="kv"><span class="kv-label">Bahan bakar</span><span class="kv-value">{{ $vehicle->fuel_level->label() }}</span></div>

                @if ($vehicle->notes)
                    <div class="form-hint mt-3">{{ $vehicle->notes }}</div>
                @endif
            </x-panel>

            @can('edit-vehicle')
                <x-panel title="Ubah Status Manual">
                    <form method="POST" action="{{ route('vehicles.status', $vehicle) }}"
                          data-confirm="Ubah status kendaraan {{ $vehicle->code }}?"
                          data-confirm-title="Konfirmasi ubah status"
                          data-confirm-button="Ya, ubah status">
                        @csrf
                        <div class="d-flex gap-2 align-items-end">
                            <div class="flex-fill">
                                <label class="form-label" for="status">Status baru</label>
                                <select name="status" id="status" class="form-select">
                                    @foreach (\App\Enums\VehicleStatus::options() as $value => $label)
                                        <option value="{{ $value }}" @selected($vehicle->status->value === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Ubah
                            </button>
                        </div>
                        <div class="form-hint">
                            Status "Dibooking" dan "Sedang Disewa" hanya dihasilkan otomatis dari proses transaksi.
                        </div>
                    </form>
                </x-panel>
            @endcan
        </div>

        <div class="col-lg-7">
            <x-panel title="Transaksi Terakhir" :flush="true">
                @if ($vehicle->transactions->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="Belum ada transaksi"
                                   text="Kendaraan ini belum pernah disewakan." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vehicle->transactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="cell-sub">
                                            {{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}
                                        </td>
                                        <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                        <td class="text-end-tabular">{{ rupiah($transaction->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>

            <x-panel title="Riwayat Pemeriksaan" :flush="true">
                @if ($vehicle->inspections->isEmpty())
                    <x-empty-state icon="fa-clipboard-check" title="Belum ada pemeriksaan"
                                   text="Pemeriksaan kondisi kendaraan akan tercatat di sini." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($vehicle->inspections as $inspection)
                                    <tr>
                                        <td>
                                            <a href="{{ route('inspections.show', $inspection) }}" class="cell-title">
                                                {{ $inspection->type->label() }}
                                            </a>
                                            <div class="cell-sub">{{ tanggal_waktu($inspection->inspected_at) }} · {{ $inspection->inspector?->name }}</div>
                                        </td>
                                        <td class="cell-sub">
                                            {{ number_format((int) $inspection->odometer) }} km · {{ $inspection->fuel_level?->label() }}
                                        </td>
                                        <td class="text-end">
                                            @if ($inspection->exterior_condition)
                                                <x-status-badge kind="condition" :value="$inspection->exterior_condition" />
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>

            <x-panel title="Riwayat Perawatan" :flush="true">
                @if ($vehicle->maintenances->isEmpty())
                    <x-empty-state icon="fa-screwdriver-wrench" title="Belum ada perawatan"
                                   text="Kendaraan belum memiliki catatan perawatan." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($vehicle->maintenances as $maintenance)
                                    <tr>
                                        <td>
                                            <a href="{{ route('maintenances.show', $maintenance) }}" class="cell-title">
                                                {{ $maintenance->type->label() }}
                                            </a>
                                            <div class="cell-sub">{{ tanggal($maintenance->start_date) }} — {{ $maintenance->end_date ? tanggal($maintenance->end_date) : 'berjalan' }}</div>
                                        </td>
                                        <td><x-status-badge kind="maintenance" :value="$maintenance->status" /></td>
                                        <td class="text-end-tabular">{{ rupiah($maintenance->cost) }}</td>
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
