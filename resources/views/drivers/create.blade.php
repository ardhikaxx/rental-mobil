@extends('layouts.app')

@section('title', 'Tambah Supir')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Supir', 'url' => route('drivers.index')],
        ['label' => 'Tambah Supir'],
    ]" />

    <x-page-header title="Tambah Supir / Driver" subtitle="Daftarkan pengemudi armada Jaya Trans baru." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Data Pengemudi">
                <form method="POST" action="{{ route('drivers.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="code" label="Kode Supir" :value="old('code', 'DRV-'.str_pad((string)(\App\Models\Driver::max('id') + 1), 3, '0', STR_PAD_LEFT))" required
                                     placeholder="Misal: DRV-007" hint="Harus unik, kode pengenal supir." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" :value="old('name')" required placeholder="Nama supir sesuai KTP" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Nomor Telepon / WhatsApp" :value="old('phone')" required
                                     placeholder="081234567890" hint="Harus nomor aktif untuk koordinasi sewa." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="sim_type" label="Golongan SIM" type="select"
                                     :options="[
                                         'SIM A' => 'SIM A (Mobil Pribadi / Rental)',
                                         'SIM B1' => 'SIM B1 (Mobil Penumpang Besar)',
                                         'SIM B1 Umum' => 'SIM B1 Umum (Angkutan Umum)',
                                         'SIM B2 Umum' => 'SIM B2 Umum',
                                     ]"
                                     :value="old('sim_type', 'SIM A')" required placeholder="Pilih golongan SIM" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="sim_number" label="Nomor SIM" :value="old('sim_number')"
                                     placeholder="Misal: 1234-5678-901234" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="daily_rate" label="Tarif Jasa Per Hari (Rp)" type="number"
                                     :value="old('daily_rate', 150000)" required hint="Standar Jaya Trans: Rp 150.000 - Rp 200.000 / hari." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="status" label="Status Awal" type="select"
                                     :options="[
                                         'available' => 'Tersedia (Siap Bertugas)',
                                         'busy' => 'Sedang Bertugas',
                                         'off' => 'Libur / Cuti',
                                         'inactive' => 'Nonaktif',
                                     ]"
                                     :value="old('status', 'available')" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="photo">Foto Profil Supir <span class="text-muted-2 fw-normal">(opsional)</span></label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                   name="photo" id="photo" accept="image/jpeg,image/png,image/webp">
                            <div class="form-hint">Format JPG/PNG/WebP, maksimal 2 MB.</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12 d-flex align-items-center">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', true))>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Supir Aktif Bertugas
                                </label>
                                <div class="form-hint">Dapat dipilih pada form pemesanan transaksi rental.</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan / Pengalaman Khusus" type="textarea" :value="old('notes')"
                                     placeholder="Misal: Hafal rute Jawa-Bali, berpengalaman matic & manual, sertifikat defensive driving." />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Supir
                        </button>
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Ketentuan Layanan Supir">
                <ul class="mb-0 ps-3" style="font-size:.87rem">
                    <li class="mb-2"><strong>Standar Kualifikasi:</strong> Supir wajib memiliki SIM A aktif atau golongan yang lebih tinggi, serta pengalaman mengemudi minimal 3 tahun.</li>
                    <li class="mb-2"><strong>Tarif Harian:</strong> Tarif supir akan dihitung otomatis dikalikan durasi hari sewa pada saat transaksi rental dibuat.</li>
                    <li class="mb-2"><strong>Status Tugas:</strong> Sistem akan menandai supir sebagai 'Sedang Bertugas' apabila terikat pada transaksi rental aktif.</li>
                </ul>
            </x-panel>
        </div>
    </div>
@endsection
