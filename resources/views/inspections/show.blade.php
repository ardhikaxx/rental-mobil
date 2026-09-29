@extends('layouts.app')

@section('title', 'Detail Pemeriksaan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pemeriksaan', 'url' => route('inspections.index')],
        ['label' => 'Detail Pemeriksaan'],
    ]" />

    <x-page-header
        title="Detail Pemeriksaan"
        :subtitle="$inspection->type->label().' · '.tanggal_waktu($inspection->inspected_at)">
        <x-slot name="actions">
            <x-status-badge kind="inspection" :value="$inspection->type" />
            @if ($inspection->transaction)
                <a href="{{ route('transactions.show', $inspection->transaction) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-file-invoice me-1"></i> Lihat Transaksi
                </a>
            @endif
            <a href="{{ route('vehicles.show', $inspection->vehicle) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-car-side me-1"></i> Kendaraan
            </a>
        </x-slot>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-panel title="Kondisi Kendaraan">
                <div class="kv"><span class="kv-label">Kendaraan</span>
                    <span class="kv-value">{{ $inspection->vehicle?->code }} — {{ $inspection->vehicle?->brand }} {{ $inspection->vehicle?->model }}</span></div>
                <div class="kv"><span class="kv-label">Nomor polisi</span><span class="kv-value">{{ $inspection->vehicle?->license_plate }}</span></div>
                <div class="kv"><span class="kv-label">Transaksi</span>
                    <span class="kv-value">{{ $inspection->transaction?->transaction_number ?? '-' }}</span></div>
                <div class="kv"><span class="kv-label">Waktu pemeriksaan</span><span class="kv-value">{{ tanggal_waktu($inspection->inspected_at) }}</span></div>
                <div class="kv"><span class="kv-label">Petugas</span><span class="kv-value">{{ $inspection->inspector?->name ?? '-' }}</span></div>
                <div class="kv"><span class="kv-label">Odometer</span>
                    <span class="kv-value">{{ $inspection->odometer !== null ? number_format($inspection->odometer).' km' : '-' }}</span></div>
                <div class="kv"><span class="kv-label">Bahan bakar</span><span class="kv-value">{{ $inspection->fuel_level?->label() ?? '-' }}</span></div>
            </x-panel>
        </div>

        <div class="col-lg-6">
            <x-panel title="Hasil Pemeriksaan">
                <div class="kv"><span class="kv-label">Eksterior</span>
                    <span class="kv-value">
                        @if ($inspection->exterior_condition)<x-status-badge kind="condition" :value="$inspection->exterior_condition" />@else - @endif
                    </span></div>
                <div class="kv"><span class="kv-label">Interior</span>
                    <span class="kv-value">
                        @if ($inspection->interior_condition)<x-status-badge kind="condition" :value="$inspection->interior_condition" />@else - @endif
                    </span></div>
                <div class="kv"><span class="kv-label">Ban</span><span class="kv-value">{{ $inspection->tire_condition?->label() ?? '-' }}</span></div>
                <div class="kv"><span class="kv-label">Kelengkapan</span><span class="kv-value">{{ $inspection->completeness ? \App\Enums\Completeness::tryFrom($inspection->completeness)?->label() : '-' }}</span></div>
                @if ($inspection->missing_items)
                    <div class="form-hint mt-2"><strong>Barang kurang:</strong> {{ $inspection->missing_items }}</div>
                @endif
                @if ($inspection->existing_damage)
                    <div class="form-hint mt-2"><strong>Kerusakan lama:</strong> {{ $inspection->existing_damage }}</div>
                @endif
                @if ($inspection->new_damage)
                    <div class="form-hint mt-2 text-danger"><strong>Kerusakan baru:</strong> {{ $inspection->new_damage }}</div>
                @endif
                @if ($inspection->vehicle_status_after)
                    <div class="kv mt-2"><span class="kv-label">Status kendaraan setelah pemeriksaan</span>
                        <span class="kv-value">{{ \App\Enums\VehicleStatus::tryFrom($inspection->vehicle_status_after)?->label() }}</span></div>
                @endif
                @if ($inspection->notes)
                    <div class="form-hint mt-2">{{ $inspection->notes }}</div>
                @endif
            </x-panel>
        </div>
    </div>

    <x-panel title="Dokumentasi Foto" :flush="true">
        @if ($inspection->photos->isEmpty())
            <x-empty-state icon="fa-camera" title="Tidak ada foto" text="Pemeriksaan ini tidak menyertakan dokumentasi foto." />
        @else
            <div class="panel-body">
                <div class="photo-grid">
                    @foreach ($inspection->photos as $photo)
                        <a href="{{ route('media.inspection-photo', $photo) }}" target="_blank">
                            <img src="{{ route('media.inspection-photo', $photo) }}" alt="foto pemeriksaan">
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </x-panel>
@endsection
