@extends('layouts.app')

@section('title', 'Ubah Perawatan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Perawatan', 'url' => route('maintenances.index')],
        ['label' => $maintenance->vehicle?->code, 'url' => route('maintenances.show', $maintenance)],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header title="Ubah Perawatan" subtitle="Perbarui detail biaya, jadwal, dan keterangan perawatan." />

    <form method="POST" action="{{ route('maintenances.update', $maintenance) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-lg-8">
                <x-panel title="Kendaraan & Jadwal">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Kendaraan</label>
                                <input type="text" class="form-control" readonly
                                       value="{{ $maintenance->vehicle?->code }} — {{ $maintenance->vehicle?->brand }} {{ $maintenance->vehicle?->model }}">
                                <div class="form-hint">Kendaraan tidak dapat diubah. Buat catatan baru bila perlu.</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <x-input name="type" label="Jenis Perawatan" type="select" :options="$types"
                                     :value="old('type', $maintenance->type->value)" required placeholder="Pilih jenis" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="start_date" label="Tanggal Mulai" type="date"
                                     :value="old('start_date', $maintenance->start_date->toDateString())" required />
                        </div>
                        <div class="col-md-4">
                            <x-input name="end_date" label="Tanggal Selesai" type="date"
                                     :value="old('end_date', $maintenance->end_date?->toDateString())" />
                        </div>
                        <div class="col-md-4">
                            <x-input name="odometer" label="Kilometer" type="number" :value="old('odometer', $maintenance->odometer)" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="workshop" label="Bengkel / Vendor" :value="old('workshop', $maintenance->workshop)" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="cost" label="Biaya Perawatan (Rp)" type="number" :value="old('cost', $maintenance->cost)" />
                        </div>
                    </div>
                </x-panel>

                <x-panel title="Detail Masalah">
                    <x-input name="description" label="Deskripsi Masalah" type="textarea"
                             :value="old('description', $maintenance->description)" required />
                    <x-input name="notes" label="Catatan" type="textarea" :value="old('notes', $maintenance->notes)" />
                </x-panel>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-outline-secondary">Batal</a>
                </div>
            </div>

            <div class="col-lg-4">
                <x-panel title="Status Saat Ini">
                    <div class="mb-3"><x-status-badge kind="maintenance" :value="$maintenance->status" /></div>
                    <div class="form-hint">
                        Status perawatan diubah melalui aksi pada halaman detail
                        (mulai, selesaikan, atau batalkan) agar aturan transisi tetap terjaga.
                    </div>
                    <a href="{{ route('maintenances.show', $maintenance) }}" class="btn btn-outline-primary btn-sm mt-2">
                        <i class="fa-solid fa-arrow-right me-1"></i> Ke Halaman Detail
                    </a>
                </x-panel>
            </div>
        </div>
    </form>
@endsection
