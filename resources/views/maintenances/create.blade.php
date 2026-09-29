@extends('layouts.app')

@section('title', 'Catat Perawatan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Perawatan', 'url' => route('maintenances.index')],
        ['label' => 'Catat Perawatan'],
    ]" />

    <x-page-header title="Catat Perawatan" subtitle="Kendaraan yang berstatus perawatan tidak dapat dipilih untuk transaksi baru." />

    <div class="row g-3">
        <div class="col-lg-8">
            <form method="POST" action="{{ route('maintenances.store') }}">
                @csrf

                <x-panel title="Kendaraan & Jadwal">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <x-input name="vehicle_id" label="Kendaraan" type="select"
                                     :options="$vehicles->mapWithKeys(fn ($v) => [$v->id => $v->code.' — '.$v->brand.' '.$v->model.' ('.$v->license_plate.')'])->all()"
                                     :value="old('vehicle_id', $selectedVehicle)" required placeholder="Pilih kendaraan" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="type" label="Jenis Perawatan" type="select" :options="$types"
                                     :value="old('type', 'routine')" required placeholder="Pilih jenis" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="start_date" label="Tanggal Mulai" type="date"
                                     :value="old('start_date', now()->toDateString())" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="end_date" label="Tanggal Selesai (rencana)" type="date" :value="old('end_date')" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="odometer" label="Kilometer Saat Perawatan" type="number" :value="old('odometer')" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="workshop" label="Bengkel / Vendor" :value="old('workshop')" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="cost" label="Biaya Perawatan (Rp)" type="number" :value="old('cost', 0)" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Detail Masalah">
                    <x-input name="description" label="Deskripsi Masalah" type="textarea" :value="old('description')" required
                             placeholder="Jelaskan kerusakan atau pekerjaan perawatan yang dilakukan" />
                    <x-input name="notes" label="Catatan" type="textarea" :value="old('notes')" placeholder="Opsional" />
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perawatan
                    </button>
                    <a href="{{ route('maintenances.index') }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <x-panel title="Status Perawatan">
                <div style="font-size:.87rem">
                    <p class="mb-2"><span class="badge badge-info">Terjadwal</span> — perawatan direncanakan, kendaraan belum masuk bengkel.</p>
                    <p class="mb-2"><span class="badge badge-warning">Sedang Dikerjakan</span> — kendaraan otomatis berstatus Perawatan dan tidak dapat disewakan.</p>
                    <p class="mb-2"><span class="badge badge-success">Selesai</span> — kendaraan otomatis kembali tersedia (atau dibooking bila ada jadwal).</p>
                    <p class="mb-0"><span class="badge badge-secondary">Dibatalkan</span> — perawatan batal dilaksanakan.</p>
                </div>
            </x-panel>
        </div>
    </div>
@endsection
