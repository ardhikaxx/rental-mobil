@extends('layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pengguna', 'url' => route('users.index')],
        ['label' => 'Tambah Pengguna'],
    ]" />

    <x-page-header title="Tambah Pengguna" subtitle="Buat akun baru untuk karyawan dengan role yang sesuai." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Data Akun">
                <form method="POST" action="{{ route('users.store') }}">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" value="{{ old('name') }}" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="username" label="Username" value="{{ old('username') }}" required
                                     hint="Digunakan untuk login. Huruf, angka, dan dash/underscore." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="email" label="Email" type="email" value="{{ old('email') }}" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Telepon" value="{{ old('phone') }}" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="password" label="Password" type="password" required minlength="8"
                                     hint="Minimal 8 karakter. Password disimpan ter-hash, tidak pernah plaintext." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="password_confirmation" label="Ulangi Password" type="password" required minlength="8" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="role" label="Role" type="select" :options="$roles"
                                     value="{{ old('role', 'staff') }}" required placeholder="Pilih role" />
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" @checked(old('is_active', true))>
                                <label class="form-check-label" for="is_active" style="font-size:.88rem">Akun aktif</label>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-user-plus me-1"></i> Simpan Pengguna
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            @foreach ($roles as $value => $label)
                <x-panel>
                    <div class="d-flex gap-2">
                        <x-status-badge kind="role" :value="$value" />
                        <div style="font-size:.86rem">{{ \App\Enums\UserRole::from($value)->description() }}</div>
                    </div>
                </x-panel>
            @endforeach
        </div>
    </div>
@endsection
