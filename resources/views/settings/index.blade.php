@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <x-page-header
        title="Pengaturan Bisnis"
        subtitle="Konfigurasi identitas rental, format nomor, aturan DP, dan tarif denda." />

    <form method="POST" action="{{ route('settings.update') }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-6">
                <x-panel title="Identitas Rental">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="company_name" label="Nama Rental" :value="old('company_name', $values['company_name'])" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="company_phone" label="Telepon" :value="old('company_phone', $values['company_phone'])" />
                        </div>
                        <div class="col-12">
                            <x-input name="company_address" label="Alamat" type="textarea" :value="old('company_address', $values['company_address'])" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Format Nomor Dokumen">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="transaction_prefix" label="Prefix Nomor Transaksi" :value="old('transaction_prefix', $values['transaction_prefix'])"
                                     required hint="Contoh RNT → RNT-20260928-0001. Nomor lama tidak berubah." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="payment_prefix" label="Prefix Nomor Pembayaran" :value="old('payment_prefix', $values['payment_prefix'])"
                                     required hint="Contoh PAY → PAY-20260928-0001." />
                        </div>
                    </div>
                </x-panel>
            </div>

            <div class="col-lg-6">
                <x-panel title="Aturan DP Minimum">
                    <x-input name="min_dp_percent" label="Persentase DP Minimum (%)" type="number" step="0.5"
                             :value="old('min_dp_percent', $values['min_dp_percent'])" required
                             hint="Booking hanya dapat disetujui bila pembayaran minimal mencapai persentase ini dari total rental. Aturan dihitung ulang setiap kali." />
                </x-panel>

                <x-panel title="Tarif Denda Keterlambatan">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="late_fee_grace_minutes" label="Masa Tenggang (menit)" type="number"
                                     :value="old('late_fee_grace_minutes', $values['late_fee_grace_minutes'])" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="late_fee_mode" label="Mode Perhitungan" type="select"
                                     :options="['per_hour' => 'Per jam (dibulatkan ke atas)', 'per_day' => 'Per hari (dibulatkan ke atas)']"
                                     :value="old('late_fee_mode', $values['late_fee_mode'])" required placeholder="Pilih mode" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="late_fee_rate" label="Tarif Denda (Rp)" type="number"
                                     :value="old('late_fee_rate', $values['late_fee_rate'])" required />
                        </div>
                    </div>
                    <div class="form-hint">
                        Nilai denda tidak di-hard-code di aplikasi. Perubahan di halaman ini langsung dipakai pada proses pengembalian berikutnya.
                    </div>
                </x-panel>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"
                    data-confirm="Simpan pengaturan bisnis? Aturan DP dan denda akan berlaku untuk transaksi berikutnya."
                    data-confirm-title="Simpan pengaturan"
                    data-confirm-button="Ya, simpan">
                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pengaturan
            </button>
        </div>
    </form>
@endsection
