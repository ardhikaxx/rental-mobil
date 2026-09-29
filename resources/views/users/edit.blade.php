@extends('layouts.app')

@section('title', 'Ubah Pengguna')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pengguna', 'url' => route('users.index')],
        ['label' => $user->name],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header title="Ubah Pengguna" subtitle="Perbarui data, role, password, dan status akun." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Data Akun">
                <form method="POST" action="{{ route('users.update', $user) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" :value="$user->name" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="username" label="Username" :value="$user->username" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="email" label="Email" type="email" :value="$user->email" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Telepon" :value="$user->phone" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="password" label="Password Baru" type="password"
                                     hint="Kosongkan jika tidak ingin mengganti password." />
                        </div>
                        <div class="col-md-6">
                            <x-input name="password_confirmation" label="Ulangi Password Baru" type="password" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="role" label="Role" type="select" :options="$roles"
                                     :value="old('role', $user->role->value)" required placeholder="Pilih role" />
                        </div>
                        <div class="col-md-6 d-flex align-items-center">
                            <div class="form-check form-switch mt-4">
                                <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                       @checked(old('is_active', $user->is_active))>
                                <label class="form-check-label" for="is_active" style="font-size:.88rem">Akun aktif</label>
                            </div>
                        </div>
                    </div>

                    @error('user')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary"
                                data-confirm="Simpan perubahan data pengguna {{ $user->name }}?"
                                data-confirm-title="Simpan perubahan"
                                data-confirm-button="Ya, simpan">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Informasi Akun">
                <div class="kv"><span class="kv-label">Role saat ini</span><span class="kv-value"><x-status-badge kind="role" :value="$user->role" /></span></div>
                <div class="kv"><span class="kv-label">Status</span>
                    <span class="kv-value">
                        @if ($user->is_active)<span class="badge badge-success">Aktif</span>@else<span class="badge badge-secondary">Nonaktif</span>@endif
                    </span></div>
                <div class="kv"><span class="kv-label">Dibuat</span><span class="kv-value">{{ tanggal($user->created_at) }}</span></div>
                <div class="kv"><span class="kv-label">Jumlah transaksi dibuat</span><span class="kv-value">{{ $user->transactions()->count() }} transaksi</span></div>
            </x-panel>

            <x-panel title="Catatan Keamanan">
                <ul class="mb-0 ps-3" style="font-size:.87rem">
                    <li class="mb-2">Anda tidak dapat mengubah role atau menonaktifkan akun Anda sendiri.</li>
                    <li class="mb-2">Akun Super Admin terakhir yang aktif tidak dapat dinonaktifkan.</li>
                    <li class="mb-2">Password disimpan dengan hashing bcrypt dan tidak pernah ditampilkan di mana pun.</li>
                </ul>
            </x-panel>
        </div>
    </div>
@endsection
