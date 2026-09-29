@extends('layouts.app')

@section('title', 'Ubah Transaksi')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Transaksi', 'url' => route('transactions.index')],
        ['label' => $transaction->transaction_number, 'url' => route('transactions.show', $transaction)],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header
        title="Ubah Transaksi"
        subtitle="Hanya transaksi Draft atau Menunggu Pembayaran yang dapat diubah. Periode baru akan dicek ulang terhadap bentrok jadwal." />

    <form method="POST" action="{{ route('transactions.update', $transaction) }}"
          id="transactionCostForm"
          data-rates='@json([$transaction->vehicle_id => $transaction->daily_rate])'
          data-rate="{{ $transaction->daily_rate }}"
          data-driver-rates='@json($drivers->pluck('daily_rate', 'id'))'
          data-discount="{{ $transaction->discount }}"
          data-dp="{{ $transaction->paidAmount() }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <x-panel title="Informasi Transaksi">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pelanggan</label>
                                <input type="text" class="form-control" value="{{ $transaction->customer?->name }} — {{ $transaction->customer?->phone }}" readonly>
                                <div class="form-hint">Pelanggan tidak dapat diubah. Batalkan lalu buat transaksi baru bila perlu mengganti pelanggan.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Kendaraan</label>
                                <input type="text" class="form-control"
                                       value="{{ $transaction->vehicle?->code }} — {{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }} ({{ $transaction->vehicle?->license_plate }})" readonly>
                                <div class="form-hint">Tarif yang berlaku: {{ rupiah($transaction->daily_rate) }}/hari.</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <x-input name="booking_source" label="Sumber Booking" type="select" :options="$bookingSources"
                                     :value="old('booking_source', $transaction->booking_source->value)" required placeholder="Pilih sumber" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="discount" label="Diskon (Rp)" type="number" :value="old('discount', $transaction->discount)" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="start_at" label="Waktu Mulai Rental" type="datetime-local"
                                     :value="old('start_at', $transaction->start_at->format('Y-m-d\TH:i'))" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="end_at" label="Rencana Pengembalian" type="datetime-local"
                                     :value="old('end_at', $transaction->end_at->format('Y-m-d\TH:i'))" required />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Layanan Supir / Driver">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="withDriverSwitch" name="with_driver" value="1" @checked(old('with_driver', $transaction->with_driver))>
                        <label class="form-check-label fw-semibold" for="withDriverSwitch">
                            Sewa Termasuk Supir / Driver
                        </label>
                        <div class="form-hint">Pilih jika penyewa membutuhkan layanan pengemudi profesional dari Jaya Trans.</div>
                    </div>
                    <div id="driverSelectionArea" style="{{ old('with_driver', $transaction->with_driver) ? '' : 'display:none' }}" class="mt-3">
                        <x-input name="driver_id" label="Pilih Supir" type="select"
                                 :options="$drivers->mapWithKeys(fn ($d) => [$d->id => $d->name.' ('.$d->code.') — '.rupiah($d->daily_rate).'/hari — Telp: '.$d->phone])->all()"
                                 :value="old('driver_id', $transaction->driver_id)"
                                 placeholder="Pilih supir yang tersedia"
                                 hint="Biaya supir akan otomatis dihitung ke tagihan sewa." />
                    </div>
                </x-panel>

                <x-panel title="Jaminan / Security Deposit">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="deposit_type" label="Bentuk Jaminan" type="select"
                                     :options="[
                                         'tunai' => 'Uang Tunai (Cash)',
                                         'transfer' => 'Transfer Bank',
                                         'ktp_motor' => 'KTP Asli + Titip Motor & STNK',
                                         'ktp_ijazah' => 'KTP Asli + Ijazah / KK',
                                         'lainnya' => 'Bentuk Jaminan Lainnya',
                                     ]"
                                     :value="old('deposit_type', $transaction->deposit_type ?? 'tunai')"
                                     placeholder="Pilih bentuk jaminan" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="deposit_amount" label="Nominal Uang Jaminan (Rp)" type="number"
                                     :value="old('deposit_amount', $transaction->deposit_amount ?? 500000)"
                                     hint="Nominal deposit uang (bisa diisi 0 jika hanya jaminan fisik)." />
                        </div>
                        <div class="col-12">
                            <x-input name="deposit_notes" label="Catatan Fisik Titipan Jaminan"
                                     :value="old('deposit_notes', $transaction->deposit_notes)"
                                     placeholder="Misal: KTP Asli ditahan garasi + Motor Honda Vario P 1234 XY" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Catatan">
                    <x-input name="notes" label="Catatan Transaksi" type="textarea" :value="old('notes', $transaction->notes)" />
                </x-panel>
            </div>

            <div class="col-lg-4">
                <div class="panel" style="position:sticky;top:76px">
                    <div class="panel-header"><h2>Ringkasan Biaya</h2></div>
                    <div class="panel-body">
                        <div class="kv"><span class="kv-label">No. transaksi</span><span class="kv-value">{{ $transaction->transaction_number }}</span></div>
                        <div class="kv"><span class="kv-label">Status</span><span class="kv-value"><x-status-badge kind="transaction" :value="$transaction->status" /></span></div>
                        <div class="kv"><span class="kv-label">Tarif harian</span><span class="kv-value" id="previewRate">-</span></div>
                        <div class="kv"><span class="kv-label">Durasi rental</span><span class="kv-value" id="previewDays">-</span></div>
                        <div class="kv"><span class="kv-label">Biaya supir</span><span class="kv-value text-primary" id="previewDriverFee">Rp 0</span></div>
                        <div class="kv"><span class="kv-label">Subtotal</span><span class="kv-value" id="previewSubtotal">-</span></div>
                        <div class="kv"><span class="kv-label">Diskon</span><span class="kv-value text-danger" id="previewDiscount">-</span></div>
                        <div class="kv"><span class="kv-label fw-bold text-dark">Total Rental</span><span class="kv-value" id="previewTotal" style="font-size:1.05rem">-</span></div>
                        <div class="kv"><span class="kv-label">Sudah dibayar</span><span class="kv-value" id="previewDp">{{ rupiah($transaction->paidAmount()) }}</span></div>
                        <div class="kv"><span class="kv-label">Sisa pembayaran</span><span class="kv-value text-danger" id="previewBalance">-</span></div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    <script src="{{ asset('js/transaction-form.js') }}"></script>
@endpush
