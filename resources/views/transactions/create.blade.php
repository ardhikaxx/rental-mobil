@extends('layouts.app')

@section('title', 'Transaksi Baru')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Transaksi', 'url' => route('transactions.index')],
        ['label' => 'Transaksi Baru'],
    ]" />

    <x-page-header title="Transaksi Baru" subtitle="Buat booking rental. Ketersediaan kendaraan diperiksa ulang oleh server saat disimpan." />

    @php
        $defaultStart = $prefill['start_at'] ?? now()->addDay()->setTime(9, 0)->format('Y-m-d\TH:i');
        $defaultEnd = $prefill['end_at'] ?? now()->addDays(3)->setTime(17, 0)->format('Y-m-d\TH:i');
        $rates = $vehicles->mapWithKeys(fn ($vehicle) => [$vehicle->id => $vehicle->daily_rate])->all();
        $driverRates = $drivers->mapWithKeys(fn ($driver) => [$driver->id => $driver->daily_rate])->all();
    @endphp

    <form method="POST" action="{{ route('transactions.store') }}"
          id="transactionCostForm"
          data-rates='@json($rates)'
          data-driver-rates='@json($driverRates)'>
        @csrf

        <div class="row g-3">
            <div class="col-lg-8">
                <x-panel title="Data Pelanggan">
                    <div class="d-flex gap-2 align-items-start">
                        <div class="flex-fill">
                            <x-input name="customer_id" label="Pelanggan" type="select"
                                     :options="$customers->mapWithKeys(fn ($c) => [$c->id => $c->name.' — '.$c->phone.' ('.$c->id_number.')'])->all()"
                                     :value="old('customer_id', $prefill['customer_id'])"
                                     required
                                     placeholder="Pilih pelanggan"
                                     hint="Belum terdaftar? Tambahkan dulu di menu Pelanggan." />
                        </div>
                        <a href="{{ route('customers.create') }}" class="btn btn-outline-primary mt-4" style="white-space:nowrap">
                            <i class="fa-solid fa-plus me-1"></i> Baru
                        </a>
                    </div>

                    <div class="row g-3 mt-0">
                        <div class="col-md-6">
                            <x-input name="booking_source" label="Sumber Booking" type="select" :options="$bookingSources"
                                     :value="old('booking_source', 'walk_in')" required placeholder="Pilih sumber" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Kendaraan & Periode Rental">
                    <div class="row g-3">
                        <div class="col-12">
                            <x-input name="vehicle_id" label="Kendaraan" type="select"
                                     :options="$vehicles->mapWithKeys(fn ($v) => [$v->id => $v->code.' — '.$v->brand.' '.$v->model.' ('.$v->license_plate.') — '.rupiah($v->daily_rate).'/hari'])->all()"
                                     :value="old('vehicle_id', $prefill['vehicle_id'])"
                                     required
                                     placeholder="Pilih kendaraan"
                                     hint="Kendaraan yang sedang terikat booking pada periode yang sama tidak ditampilkan. Pastikan tanggal sudah diisi untuk filter akurat." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="start_at" label="Waktu Mulai Rental" type="datetime-local"
                                     :value="old('start_at', $defaultStart)" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="end_at" label="Rencana Pengembalian" type="datetime-local"
                                     :value="old('end_at', $defaultEnd)" required hint="Durasi dibulatkan ke atas per hari." />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Layanan Supir / Driver">
                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="withDriverSwitch" name="with_driver" value="1" @checked(old('with_driver'))>
                        <label class="form-check-label fw-semibold" for="withDriverSwitch">
                            Sewa Termasuk Supir / Driver
                        </label>
                        <div class="form-hint">Pilih jika penyewa membutuhkan layanan pengemudi profesional dari Jaya Trans.</div>
                    </div>
                    <div id="driverSelectionArea" style="{{ old('with_driver') ? '' : 'display:none' }}" class="mt-3">
                        <x-input name="driver_id" label="Pilih Supir" type="select"
                                 :options="$drivers->mapWithKeys(fn ($d) => [$d->id => $d->name.' ('.$d->code.') — '.rupiah($d->daily_rate).'/hari — Telp: '.$d->phone])->all()"
                                 :value="old('driver_id')"
                                 placeholder="Pilih supir yang tersedia"
                                 hint="Biaya supir akan otomatis ditambahkan ke tagihan sewa." />
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
                                     :value="old('deposit_type', 'tunai')"
                                     placeholder="Pilih bentuk jaminan" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="deposit_amount" label="Nominal Uang Jaminan (Rp)" type="number"
                                     :value="old('deposit_amount', 500000)"
                                     hint="Nominal deposit uang (bisa diisi 0 jika hanya jaminan fisik)." />
                        </div>
                        <div class="col-12">
                            <x-input name="deposit_notes" label="Catatan Fisik Titipan Jaminan"
                                     :value="old('deposit_notes')"
                                     placeholder="Misal: KTP Asli ditahan garasi + Motor Honda Vario P 1234 XY" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Biaya & Pembayaran">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="discount" label="Diskon (Rp)" type="number" :value="old('discount', 0)" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="dp_amount" label="Uang Muka Dibayar (Rp)" type="number" :value="old('dp_amount', 0)"
                                     hint="Isi 0 bila DP dibayar terpisah." />
                        </div>
                        <div class="col-md-4">
                            <x-input name="dp_method" label="Metode Pembayaran DP" type="select" :options="$paymentMethods"
                                     :value="old('dp_method', 'cash')" required placeholder="Pilih metode" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Catatan">
                    <x-input name="notes" label="Catatan Transaksi" type="textarea" :value="old('notes')"
                             placeholder="Permintaan khusus, catatan komunikasi, atau informasi lain (opsional)" />
                </x-panel>
            </div>

            <div class="col-lg-4">
                <div class="panel" style="position:sticky;top:76px">
                    <div class="panel-header"><h2>Ringkasan Biaya</h2></div>
                    <div class="panel-body">
                        <div class="kv"><span class="kv-label">Tarif harian mobil</span><span class="kv-value" id="previewRate">-</span></div>
                        <div class="kv"><span class="kv-label">Durasi rental</span><span class="kv-value" id="previewDays">-</span></div>
                        <div class="kv"><span class="kv-label">Biaya supir</span><span class="kv-value text-primary" id="previewDriverFee">Rp 0</span></div>
                        <div class="kv"><span class="kv-label">Subtotal</span><span class="kv-value" id="previewSubtotal">-</span></div>
                        <div class="kv"><span class="kv-label">Diskon</span><span class="kv-value text-danger" id="previewDiscount">- Rp 0</span></div>
                        <div class="kv"><span class="kv-label fw-bold text-dark">Total Rental</span><span class="kv-value" id="previewTotal" style="font-size:1.05rem">Rp 0</span></div>
                        <div class="kv"><span class="kv-label">Uang muka</span><span class="kv-value" id="previewDp">Rp 0</span></div>
                        <div class="kv"><span class="kv-label">Sisa pembayaran</span><span class="kv-value text-danger" id="previewBalance">Rp 0</span></div>

                        <div class="d-grid gap-2 mt-3">
                            <button type="submit" class="btn btn-primary" name="save_as_draft" value="0">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Transaksi
                            </button>
                            <button type="submit" class="btn btn-outline-primary" name="save_as_draft" value="1"
                                    data-confirm="Simpan sebagai draft? Transaksi belum mengunci kendaraan sampai disetujui."
                                    data-confirm-title="Simpan draft"
                                    data-confirm-button="Simpan draft">
                                <i class="fa-solid fa-file-pen me-1"></i> Simpan sebagai Draft
                            </button>
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>

                        <div class="form-hint mt-3">
                            <i class="fa-solid fa-circle-info me-1"></i>
                            Setelah disimpan, transaksi harus disetujui agar kendaraan terkunci untuk periode ini (dicegah double booking oleh server).
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
