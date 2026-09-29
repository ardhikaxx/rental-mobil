@extends('layouts.app')

@section('title', 'Tambah Kendaraan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Kendaraan', 'url' => route('vehicles.index')],
        ['label' => 'Tambah Kendaraan'],
    ]" />

    <x-page-header title="Tambah Kendaraan" subtitle="Daftarkan aset mobil rental baru ke sistem." />

    <form method="POST" action="{{ route('vehicles.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="row g-3">
            <div class="col-lg-7">
                <x-panel title="Identitas Kendaraan">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="code" label="Kode Unit" value="{{ old('code') }}" required
                                     placeholder="VT-001" hint="Kode internal unik, contoh VT-001." />
                        </div>
                        <div class="col-md-4">
                            <x-input name="brand" label="Merek" value="{{ old('brand') }}" required placeholder="Toyota" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="model" label="Model" value="{{ old('model') }}" required placeholder="Avanza" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="type" label="Tipe Kendaraan" type="select" :options="$types"
                                     value="{{ old('type') }}" required placeholder="Pilih tipe" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="year" label="Tahun" type="number" value="{{ old('year') }}" required placeholder="2022" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="color" label="Warna" value="{{ old('color') }}" required placeholder="Putih" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="license_plate" label="Nomor Polisi" value="{{ old('license_plate') }}" required
                                     placeholder="B 1234 XYZ" hint="Harus unik di seluruh sistem." />
                        </div>
                        <div class="col-md-4">
                            <x-input name="chassis_number" label="Nomor Rangka" value="{{ old('chassis_number') }}" placeholder="Opsional" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="engine_number" label="Nomor Mesin" value="{{ old('engine_number') }}" placeholder="Opsional" />
                        </div>
                    </div>
                </x-panel>
            </div>

            <div class="col-lg-5">
                <x-panel title="Operasional">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="daily_rate" label="Tarif Sewa Harian" type="number" value="{{ old('daily_rate') }}"
                                     required placeholder="350000" hint="Dalam Rupiah per hari." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="fuel_level" label="Volume Bahan Bakar" type="select" :options="$fuelLevels"
                                     value="{{ old('fuel_level', 'full') }}" placeholder="Pilih level" />
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="photo">Foto Kendaraan <span class="text-muted-2 fw-normal">(opsional)</span></label>
                            <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                   name="photo" id="photo" accept="image/jpeg,image/png,image/webp">
                            <div class="form-hint">Format JPG/PNG/WebP, maksimal 2 MB.</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan Kendaraan" type="textarea" value="{{ old('notes') }}"
                                     placeholder="Catatan internal tentang kendaraan (opsional)" />
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       @checked(old('is_active', true))>
                                <label class="form-check-label" for="is_active" style="font-size:.88rem">
                                    Kendaraan aktif (dapat disewakan)
                                </label>
                            </div>
                            @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Kendaraan
                    </button>
                    <a href="{{ route('vehicles.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
