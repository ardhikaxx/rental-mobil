@extends('layouts.app')

@section('title', 'Tambah Pelanggan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pelanggan', 'url' => route('customers.index')],
        ['label' => 'Tambah Pelanggan'],
    ]" />

    <x-page-header title="Tambah Pelanggan" subtitle="Catat identitas penyewa baru. Cek dulu apakah sudah terdaftar." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Identitas Pelanggan">
                <form method="POST" action="{{ route('customers.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" value="{{ old('name') }}" required placeholder="Sesuai KTP" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="id_number" label="Nomor Identitas (KTP)" value="{{ old('id_number') }}" required
                                     placeholder="3273010101900001" hint="Harus unik. Hanya angka atau huruf, 5-32 karakter." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Nomor Telepon" value="{{ old('phone') }}" required
                                     placeholder="081234567890" hint="Aktif untuk konfirmasi serah terima." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="email" label="Email" type="email" value="{{ old('email') }}" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="birth_date" label="Tanggal Lahir" type="date" value="{{ old('birth_date') }}" />
                        </div>
                        <div class="col-12">
                            <x-input name="address" label="Alamat" value="{{ old('address') }}" placeholder="Opsional" />
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan" type="textarea" value="{{ old('notes') }}"
                                     placeholder="Catatan komunikasi atau permintaan khusus (opsional)" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pelanggan
                        </button>
                        <a href="{{ route('customers.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Tips">
                <ul class="mb-0 ps-3" style="font-size:.87rem">
                    <li class="mb-2">Cari dulu pelanggan berdasarkan nama, nomor identitas, atau telepon sebelum menambah data baru — mencegah data ganda.</li>
                    <li class="mb-2">Cukup simpan data yang diperlukan untuk operasional rental. Jangan menyimpan dokumen sensitif lainnya.</li>
                    <li class="mb-2">Nomor telepon dipakai untuk koordinasi serah terima dan pengembalian kendaraan.</li>
                </ul>
            </x-panel>
        </div>
    </div>
@endsection
