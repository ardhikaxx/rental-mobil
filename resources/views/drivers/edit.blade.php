@extends('layouts.app')

@section('title', 'Ubah Supir')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Supir', 'url' => route('drivers.index')],
        ['label' => $driver->name, 'url' => route('drivers.edit', $driver)],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header title="Ubah Supir" subtitle="Perbarui data operasional pengemudi {{ $driver->name }}." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Data Pengemudi">
                <form method="POST" action="{{ route('drivers.update', $driver) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="code" label="Kode Supir" :value="old('code', $driver->code)" required
                                     placeholder="Misal: DRV-001" hint="Harus unik." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" :value="old('name', $driver->name)" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Nomor Telepon / WhatsApp" :value="old('phone', $driver->phone)" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="sim_type" label="Golongan SIM" type="select"
                                     :options="[
                                         'SIM A' => 'SIM A (Mobil Pribadi / Rental)',
                                         'SIM B1' => 'SIM B1 (Mobil Penumpang Besar)',
                                         'SIM B1 Umum' => 'SIM B1 Umum (Angkutan Umum)',
                                         'SIM B2 Umum' => 'SIM B2 Umum',
                                     ]"
                                     :value="old('sim_type', $driver->sim_type)" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="sim_number" label="Nomor SIM" :value="old('sim_number', $driver->sim_number)" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="daily_rate" label="Tarif Jasa Per Hari (Rp)" type="number"
                                     :value="old('daily_rate', $driver->daily_rate)" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="status" label="Status Tugas" type="select"
                                     :options="[
                                         'available' => 'Tersedia (Siap Bertugas)',
                                         'busy' => 'Sedang Bertugas',
                                         'off' => 'Libur / Cuti',
                                         'inactive' => 'Nonaktif',
                                     ]"
                                     :value="old('status', $driver->status)" required />
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" @checked(old('is_active', $driver->is_active))>
                                <label class="form-check-label fw-semibold" for="is_active">
                                    Supir Aktif Bertugas
                                </label>
                                <div class="form-hint">Dapat dipilih pada form pemesanan transaksi rental.</div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="photo">Foto Profil Supir</label>
                            <div class="d-flex align-items-center gap-3">
                                @if ($driver->photo)
                                    <div class="position-relative">
                                        <img src="{{ $driver->photo_url }}" alt="{{ $driver->name }}"
                                             class="rounded-circle border" style="width:64px;height:64px;object-fit:cover">
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                           name="photo" id="photo" accept="image/jpeg,image/png,image/webp">
                                    @if ($driver->photo)
                                        <div class="form-check mt-1">
                                            <input class="form-check-input" type="checkbox" name="delete_photo" id="delete_photo" value="1">
                                            <label class="form-check-label text-danger small" for="delete_photo">
                                                <i class="fa-solid fa-trash me-1"></i>Hapus foto supir saat ini
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-hint">Kosongkan jika tidak ingin mengganti foto. Format JPG/PNG/WebP, maks 2 MB.</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan / Pengalaman Khusus" type="textarea" :value="old('notes', $driver->notes)" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('drivers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Ringkasan Operasional">
                <div class="kv"><span class="kv-label">Total penugasan sewa</span><span class="kv-value">{{ $driver->transactions()->count() }}x</span></div>
                <div class="kv"><span class="kv-label">Tugas sewa aktif</span><span class="kv-value">{{ $driver->transactions()->whereIn('status', \App\Enums\TransactionStatus::blocking())->count() }}x</span></div>
                <div class="kv"><span class="kv-label">Terdaftar sejak</span><span class="kv-value">{{ tanggal($driver->created_at) }}</span></div>

                @if (! $driver->transactions()->exists())
                    <hr class="my-3">
                    <form method="POST" action="{{ route('drivers.destroy', $driver) }}"
                          data-confirm="Hapus data supir {{ $driver->name }}? Tindakan ini tidak dapat dibatalkan."
                          data-confirm-title="Hapus supir?"
                          data-confirm-icon="error"
                          data-confirm-button="Ya, hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                            <i class="fa-solid fa-trash me-1"></i> Hapus Supir Ini
                        </button>
                    </form>
                @endif
            </x-panel>
        </div>
    </div>
@endsection
