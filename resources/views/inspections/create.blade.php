@extends('layouts.app')

@section('title', 'Pemeriksaan Baru')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pemeriksaan', 'url' => route('inspections.index')],
        ['label' => 'Pemeriksaan Baru'],
    ]" />

    <x-page-header
        title="Pemeriksaan Rutin"
        subtitle="Catat kondisi kendaraan, odometer, dan bahan bakar di luar proses rental." />

    <div class="row g-3">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('inspections.store') }}" enctype="multipart/form-data">
                @csrf

                <x-panel title="Kendaraan & Waktu">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <x-input name="vehicle_id" label="Kendaraan" type="select"
                                     :options="$vehicles->mapWithKeys(fn ($v) => [$v->id => $v->code.' — '.$v->brand.' '.$v->model.' ('.$v->license_plate.')'])->all()"
                                     :value="old('vehicle_id', $selectedVehicle)" required placeholder="Pilih kendaraan" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="inspected_at" label="Waktu Pemeriksaan" type="datetime-local"
                                     :value="old('inspected_at', now()->format('Y-m-d\TH:i'))" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="odometer" label="Odometer (km)" type="number" :value="old('odometer')" required
                                     hint="Tidak boleh kurang dari kilometer terakhir." />
                        </div>
                        <div class="col-md-4">
                            <x-input name="fuel_level" label="Volume Bahan Bakar" type="select" :options="$fuelLevels"
                                     :value="old('fuel_level', 'full')" required placeholder="Pilih level" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="completeness" label="Kelengkapan" type="select" :options="$completeness"
                                     :value="old('completeness', 'lengkap')" placeholder="Pilih kelengkapan" />
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
                            <x-input name="missing_items" label="Barang yang Kurang" :value="old('missing_items')" placeholder="Opsional" />
                        </div>
                        <div class="col-12">
                            <x-input name="existing_damage" label="Catatan Kerusakan" type="textarea" :value="old('existing_damage')"
                                     placeholder="Kerusakan yang ditemukan saat pemeriksaan" />
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan Pemeriksaan" type="textarea" :value="old('notes')" placeholder="Opsional" />
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dokumentasi Foto <span class="text-muted-2 fw-normal">(maks 5 foto)</span></label>
                            <input type="file" class="form-control @error('photos') is-invalid @enderror"
                                   name="photos[]" multiple accept="image/jpeg,image/png,image/webp">
                            @error('photos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @error('photos.*')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pemeriksaan
                    </button>
                    <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <x-panel title="Petunjuk">
                <ul class="mb-0 ps-3" style="font-size:.87rem">
                    <li class="mb-2">Pemeriksaan rutin dipakai untuk mencatat odometer dan bahan bakar terbaru tanpa transaksi rental.</li>
                    <li class="mb-2">Pemeriksaan serah terima dan pengembalian dibuat otomatis dari halaman transaksi.</li>
                    <li class="mb-2">Riwayat pemeriksaan menjadi dasar evaluasi kondisi kendaraan dari waktu ke waktu.</li>
                </ul>
            </x-panel>
        </div>
    </div>
@endsection
