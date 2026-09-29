@extends('layouts.app')

@section('title', 'Ubah Kendaraan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Kendaraan', 'url' => route('vehicles.index')],
        ['label' => $vehicle->code, 'url' => route('vehicles.show', $vehicle)],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header title="Ubah Kendaraan" subtitle="Perbarui data {{ $vehicle->code }} ({{ $vehicle->license_plate }})." />

    <form method="POST" action="{{ route('vehicles.update', $vehicle) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-7">
                <x-panel title="Identitas Kendaraan">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <x-input name="code" label="Kode Unit" :value="$vehicle->code" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="brand" label="Merek" :value="$vehicle->brand" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="model" label="Model" :value="$vehicle->model" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="type" label="Tipe Kendaraan" type="select" :options="$types"
                                     :value="$vehicle->type" required placeholder="Pilih tipe" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="year" label="Tahun" type="number" :value="$vehicle->year" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="color" label="Warna" :value="$vehicle->color" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="license_plate" label="Nomor Polisi" :value="$vehicle->license_plate" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="chassis_number" label="Nomor Rangka" :value="$vehicle->chassis_number" placeholder="Opsional" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="engine_number" label="Nomor Mesin" :value="$vehicle->engine_number" placeholder="Opsional" />
                        </div>
                    </div>
                </x-panel>
            </div>

            <div class="col-lg-5">
                <x-panel title="Operasional">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="daily_rate" label="Tarif Sewa Harian" type="number" :value="$vehicle->daily_rate" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="fuel_level" label="Volume Bahan Bakar" type="select" :options="$fuelLevels"
                                     :value="$vehicle->fuel_level->value" placeholder="Pilih level" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Foto Kendaraan</label>
                            <div class="d-flex align-items-center gap-3">
                                @if ($vehicle->photo)
                                    <img src="{{ asset('storage/'.$vehicle->photo) }}" alt="{{ $vehicle->code }}"
                                         class="vehicle-thumb" style="width:96px;height:64px">
                                @endif
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                       name="photo" accept="image/jpeg,image/png,image/webp">
                            </div>
                            <div class="form-hint">Kosongkan jika tidak ingin mengganti foto. Format JPG/PNG/WebP, maks 2 MB.</div>
                            @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan Kendaraan" type="textarea" :value="$vehicle->notes" />
                        </div>
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       @checked(old('is_active', $vehicle->is_active))>
                                <label class="form-check-label" for="is_active" style="font-size:.88rem">
                                    Kendaraan aktif (dapat disewakan)
                                </label>
                            </div>
                            @error('is_active')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <div class="form-hint">
                                Status operasional tidak dapat diubah dari halaman ini. Gunakan aksi status pada halaman detail kendaraan.
                            </div>
                        </div>
                    </div>
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>
        </div>
    </form>
@endsection
