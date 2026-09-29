@extends('layouts.app')

@section('title', 'Serah Terima Kendaraan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Serah Terima', 'url' => route('handover.index')],
        ['label' => $transaction->transaction_number, 'url' => route('transactions.show', $transaction)],
        ['label' => 'Proses Serah Terima'],
    ]" />

    <x-page-header
        title="Serah Terima Kendaraan"
        subtitle="Periksa kondisi awal sebelum kendaraan diserahkan kepada pelanggan." />

    <div class="row g-3">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('handover.store', $transaction) }}" enctype="multipart/form-data">
                @csrf

                <x-panel title="Waktu & Odometer">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="handover_at" label="Waktu Serah Terima" type="datetime-local"
                                     :value="old('handover_at', now()->format('Y-m-d\TH:i'))" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="odometer" label="Odometer Awal (km)" type="number"
                                     :value="old('odometer', $transaction->vehicle?->odometer)" required
                                     hint="Kilometer terakhir: {{ number_format((int) $transaction->vehicle?->odometer) }} km" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="fuel_level" label="Volume Bahan Bakar Awal" type="select" :options="$fuelLevels"
                                     :value="old('fuel_level', $transaction->vehicle?->fuel_level->value)" required placeholder="Pilih level" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Kondisi Kendaraan">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="exterior_condition" label="Kondisi Eksterior" type="select" :options="$conditions"
                                     :value="old('exterior_condition', 'good')" required placeholder="Pilih kondisi" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="interior_condition" label="Kondisi Interior" type="select" :options="$conditions"
                                     :value="old('interior_condition', 'good')" required placeholder="Pilih kondisi" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="tire_condition" label="Kondisi Ban" type="select" :options="$tires"
                                     :value="old('tire_condition', 'good')" required placeholder="Pilih kondisi" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="completeness" label="Kelengkapan Kendaraan" type="select" :options="$completeness"
                                     :value="old('completeness', 'lengkap')" required placeholder="Pilih kelengkapan" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="missing_items" label="Barang yang Kurang" :value="old('missing_items')"
                                     placeholder="Wajib diisi bila kelengkapan tidak lengkap" />
                        </div>
                        <div class="col-12">
                            <x-input name="existing_damage" label="Catatan Kerusakan yang Sudah Ada" type="textarea"
                                     :value="old('existing_damage')"
                                     placeholder="Dokumentasikan kerusakan lama agar dapat dibedakan saat pengembalian" />
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan Pemeriksaan" type="textarea" :value="old('notes')"
                                     placeholder="Opsional" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dokumentasi Foto <span class="text-muted-2 fw-normal">(maks 5 foto)</span></label>
                            <input type="file" class="form-control @error('photos') is-invalid @enderror"
                                   name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
                            <div class="form-hint">Format JPG/PNG/WebP, maksimal 2 MB per foto.</div>
                            @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @error('photos.*')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary"
                            data-confirm="Konfirmasi serah terima kendaraan {{ $transaction->vehicle?->code }} kepada {{ $transaction->customer?->name }}? Status transaksi menjadi Sedang Disewa dan kendaraan terkunci."
                            data-confirm-title="Konfirmasi serah terima"
                            data-confirm-button="Ya, serahkan">
                        <i class="fa-solid fa-key me-1"></i> Konfirmasi Serah Terima
                    </button>
                    <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="panel" style="position:sticky;top:76px">
                <div class="panel-header"><h2>Transaksi</h2></div>
                <div class="panel-body">
                    <div class="kv"><span class="kv-label">No. transaksi</span>
                        <span class="kv-value">{{ $transaction->transaction_number }}</span></div>
                    <div class="kv"><span class="kv-label">Status</span>
                        <span class="kv-value"><x-status-badge kind="transaction" :value="$transaction->status" /></span></div>
                    <div class="kv"><span class="kv-label">Pelanggan</span><span class="kv-value">{{ $transaction->customer?->name }}</span></div>
                    <div class="kv"><span class="kv-label">Telepon</span><span class="kv-value">{{ $transaction->customer?->phone }}</span></div>
                    <div class="kv"><span class="kv-label">Kendaraan</span>
                        <span class="kv-value">{{ $transaction->vehicle?->code }} — {{ $transaction->vehicle?->license_plate }}</span></div>
                    <div class="kv"><span class="kv-label">Periode</span>
                        <span class="kv-value">{{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}</span></div>
                    <div class="kv"><span class="kv-label">Total tagihan</span><span class="kv-value">{{ rupiah($transaction->totalPayable()) }}</span></div>
                    <div class="kv"><span class="kv-label">Sudah dibayar</span><span class="kv-value">{{ rupiah($transaction->paidAmount()) }}</span></div>
                    <div class="kv"><span class="kv-label">Sisa tagihan</span>
                        <span class="kv-value {{ $transaction->balance() > 0 ? 'text-danger' : 'text-success' }}">{{ rupiah($transaction->balance()) }}</span></div>

                    @if ($transaction->balance() > 0)
                        <div class="form-hint mt-2">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Sisa tagihan masih terbuka. Serah tetap dapat dilakukan sesuai kebijakan operasional.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
