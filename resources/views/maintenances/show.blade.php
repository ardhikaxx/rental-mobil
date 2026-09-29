@extends('layouts.app')

@section('title', 'Detail Perawatan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Perawatan', 'url' => route('maintenances.index')],
        ['label' => 'Detail Perawatan'],
    ]" />

    <x-page-header
        :title="$maintenance->type->label().' — '.$maintenance->vehicle?->code"
        :subtitle="$maintenance->vehicle?->brand.' '.$maintenance->vehicle?->model.' · '.tanggal($maintenance->start_date)">
        <x-slot name="actions">
            <x-status-badge kind="maintenance" :value="$maintenance->status" />
            @can('manage-maintenance')
                <a href="{{ route('maintenances.edit', $maintenance) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen me-1"></i> Ubah
                </a>
            @endcan
            <a href="{{ route('vehicles.show', $maintenance->vehicle) }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-car-side me-1"></i> Kendaraan
            </a>
        </x-slot>
    </x-page-header>

    <div class="row g-3">
        <div class="col-lg-6">
            <x-panel title="Informasi Perawatan">
                <div class="kv"><span class="kv-label">Kendaraan</span>
                    <span class="kv-value">{{ $maintenance->vehicle?->code }} — {{ $maintenance->vehicle?->license_plate }}</span></div>
                <div class="kv"><span class="kv-label">Jenis</span><span class="kv-value">{{ $maintenance->type->label() }}</span></div>
                <div class="kv"><span class="kv-label">Tanggal mulai</span><span class="kv-value">{{ tanggal($maintenance->start_date) }}</span></div>
                <div class="kv"><span class="kv-label">Tanggal selesai</span><span class="kv-value">{{ $maintenance->end_date ? tanggal($maintenance->end_date) : '-' }}</span></div>
                <div class="kv"><span class="kv-label">Kilometer</span>
                    <span class="kv-value">{{ $maintenance->odometer !== null ? number_format($maintenance->odometer).' km' : '-' }}</span></div>
                <div class="kv"><span class="kv-label">Bengkel</span><span class="kv-value">{{ $maintenance->workshop ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Biaya</span><span class="kv-value">{{ rupiah($maintenance->cost) }}</span></div>
                <div class="kv"><span class="kv-label">Dicatat oleh</span><span class="kv-value">{{ $maintenance->recorder?->name ?? '-' }}</span></div>

                <div class="section-title mt-3">Deskripsi Masalah</div>
                <p style="font-size:.9rem" class="mb-0">{{ $maintenance->description }}</p>
                @if ($maintenance->notes)
                    <div class="form-hint mt-2">{{ $maintenance->notes }}</div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-6">
            @can('manage-maintenance')
                <x-panel title="Perbarui Status Perawatan">
                    @if (in_array($maintenance->status->value, ['completed', 'cancelled'], true))
                        <div class="form-hint">
                            Perawatan sudah berstatus {{ $maintenance->status->label() }} dan tidak dapat diubah lagi.
                        </div>
                    @else
                        <form method="POST" action="{{ route('maintenances.status', $maintenance) }}"
                              data-confirm="Perbarui status perawatan kendaraan {{ $maintenance->vehicle?->code }}?"
                              data-confirm-title="Konfirmasi perubahan status"
                              data-confirm-button="Ya, perbarui">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <x-input name="status" label="Status Baru" type="select"
                                             :options="array_filter([
                                                 'in_progress' => 'Sedang Dikerjakan',
                                                 'completed' => 'Selesai',
                                                 'cancelled' => 'Dibatalkan',
                                             ])"
                                             value="{{ old('status', $maintenance->status === \App\Enums\MaintenanceStatus::Scheduled ? 'in_progress' : 'completed') }}"
                                             required placeholder="Pilih status" />
                                </div>
                                <div class="col-md-6">
                                    <x-input name="end_date" label="Tanggal Selesai" type="date"
                                             :value="old('end_date', now()->toDateString())" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-arrows-rotate me-1"></i> Perbarui Status
                            </button>
                            <div class="form-hint mt-2">
                                Status "Selesai" akan mengembalikan kendaraan ke kondisi tersedia/dibooking.
                            </div>
                        </form>
                    @endif
                </x-panel>
            @endcan

            <x-panel title="Riwayat Rental Kendaraan" :flush="true">
                @if ($maintenance->vehicle->transactions->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="Belum ada transaksi"
                                   text="Kendaraan ini belum pernah disewakan." />
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach ($maintenance->vehicle->transactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->customer?->name }}</div>
                                        </td>
                                        <td class="cell-sub">{{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}</td>
                                        <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
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
