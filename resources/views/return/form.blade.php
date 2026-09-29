@extends('layouts.app')

@section('title', 'Pengembalian Kendaraan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pengembalian', 'url' => route('return.index')],
        ['label' => $transaction->transaction_number, 'url' => route('transactions.show', $transaction)],
        ['label' => 'Proses Pengembalian'],
    ]" />

    <x-page-header
        title="Pengembalian Kendaraan"
        subtitle="Periksa kondisi akhir kendaraan. Denda keterlambatan dihitung otomatis dari pengaturan tarif denda." />

    <div class="row g-3">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('return.store', $transaction) }}"
                  enctype="multipart/form-data"
                  data-late-fee-form
                  data-due-at="{{ $transaction->end_at->format('Y-m-d H:i:s') }}"
                  data-grace="{{ $lateFee['grace'] }}"
                  data-mode="{{ $lateFee['mode'] }}"
                  data-rate="{{ $lateFee['rate'] }}">
                @csrf

                <x-panel title="Waktu & Odometer">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="actual_return_at" label="Waktu Pengembalian Aktual" type="datetime-local"
                                     :value="old('actual_return_at', now()->format('Y-m-d\TH:i'))" required />
                            <div id="lateFeePreview" class="mb-3"></div>
                        </div>
                        <div class="col-md-4">
                            <x-input name="odometer" label="Odometer Akhir (km)" type="number"
                                     :value="old('odometer', $transaction->handoverInspection?->odometer ?? $transaction->vehicle?->odometer)" required
                                     hint="Odometer saat serah terima: {{ number_format((int) ($transaction->handoverInspection?->odometer ?? $transaction->vehicle?->odometer)) }} km" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="fuel_level" label="Volume Bahan Bakar Akhir" type="select" :options="$fuelLevels"
                                     :value="old('fuel_level', $transaction->vehicle?->fuel_level->value)" required placeholder="Pilih level" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Hasil Pemeriksaan Kondisi">
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
                            <x-input name="new_damage" label="Kerusakan Baru" type="textarea" :value="old('new_damage')"
                                     placeholder="Kerusakan yang tidak tercatat saat serah terima (kosongkan bila tidak ada)" />
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

                <x-panel title="Kondisi Akhir Kendaraan">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="next_status" label="Status Kendaraan Setelah Dikembalikan" type="select"
                                     :options="$nextStatuses"
                                     :value="old('next_status', 'tersedia')" required
                                     hint="Kendaraan tidak otomatis tersedia bila pemeriksaan menunjukkan masalah." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="problem_description" label="Deskripsi Masalah" type="textarea"
                                     :value="old('problem_description')"
                                     placeholder="Wajib diisi bila memilih status Perawatan" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary"
                                data-confirm="Konfirmasi pengembalian kendaraan {{ $transaction->vehicle?->code }}? Transaksi akan ditutup dan status kendaraan diperbarui sesuai hasil pemeriksaan."
                                data-confirm-title="Konfirmasi pengembalian"
                                data-confirm-button="Ya, proses pengembalian">
                            <i class="fa-solid fa-rotate-left me-1"></i> Konfirmasi Pengembalian
                        </button>
                        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </x-panel>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="panel" style="position:sticky;top:76px">
                <div class="panel-header"><h2>Transaksi</h2></div>
                <div class="panel-body">
                    <div class="kv"><span class="kv-label">No. transaksi</span>
                        <span class="kv-value">{{ $transaction->transaction_number }}</span></div>
                    <div class="kv"><span class="kv-label">Pelanggan</span><span class="kv-value">{{ $transaction->customer?->name }}</span></div>
                    <div class="kv"><span class="kv-label">Kendaraan</span>
                        <span class="kv-value">{{ $transaction->vehicle?->code }} — {{ $transaction->vehicle?->license_plate }}</span></div>
                    <div class="kv"><span class="kv-label">Tenggat kembali</span>
                        <span class="kv-value">{{ tanggal_waktu($transaction->end_at) }}</span></div>
                    <div class="kv"><span class="kv-label">Diserahkan</span>
                        <span class="kv-value">{{ $transaction->handover_at ? tanggal_waktu($transaction->handover_at) : '-' }}</span></div>
                    <div class="kv"><span class="kv-label">Total tagihan</span><span class="kv-value">{{ rupiah($transaction->totalPayable()) }}</span></div>
                    <div class="kv"><span class="kv-label">Sudah dibayar</span><span class="kv-value">{{ rupiah($transaction->paidAmount()) }}</span></div>
                    <div class="kv"><span class="kv-label">Sisa tagihan</span>
                        <span class="kv-value {{ $transaction->balance() > 0 ? 'text-danger' : 'text-success' }}">{{ rupiah($transaction->balance()) }}</span></div>

                    @if ($transaction->handoverInspection)
                        <div class="section-title mt-3">Kondisi Saat Serah Terima</div>
                        <div class="kv"><span class="kv-label">Eksterior</span>
                            <span class="kv-value">{{ $transaction->handoverInspection->exterior_condition?->label() }}</span></div>
                        <div class="kv"><span class="kv-label">Interior</span>
                            <span class="kv-value">{{ $transaction->handoverInspection->interior_condition?->label() }}</span></div>
                        <div class="kv"><span class="kv-label">Ban</span>
                            <span class="kv-value">{{ $transaction->handoverInspection->tire_condition?->label() }}</span></div>
                        @if ($transaction->handoverInspection->existing_damage)
                            <div class="form-hint mt-2"><strong>Kerusakan lama:</strong> {{ $transaction->handoverInspection->existing_damage }}</div>
                        @endif
                    @endif

                    <div class="form-hint mt-3">
                        <i class="fa-solid fa-circle-info me-1"></i>
                        Aturan denda: masa tenggang {{ $lateFee['grace'] }} menit,
                        dihitung {{ $lateFee['mode'] === 'per_day' ? 'per hari' : 'per jam' }}
                        sebesar {{ rupiah($lateFee['rate']) }}.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
