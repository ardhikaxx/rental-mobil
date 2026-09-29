@extends('layouts.app')

@section('title', 'Pengguna')

@section('content')
    <x-page-header
        title="Manajemen Pengguna"
        subtitle="Kelola akun, role, dan status aktif pengguna sistem.">
        <x-slot name="actions">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-user-plus me-1"></i> Tambah Pengguna
            </a>
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <x-stat icon="fa-users" label="Total Pengguna" :value="$totalCount" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-user-check" label="Akun Aktif" :value="$activeCount" />
        </div>
        <div class="col-md-4">
            <x-stat icon="fa-user-slash" label="Akun Nonaktif" :value="$totalCount - $activeCount" />
        </div>
    </div>

    <form method="GET" action="{{ route('users.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="Nama, username, email">
            </div>
            <div class="col-md-2">
                <label class="form-label" for="role">Role</label>
                <select name="role" id="role" class="form-select">
                    <option value="">Semua role</option>
                    @foreach ($roles as $value => $label)
                        <option value="{{ $value }}" @selected($filterRole === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="status">Status</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="active" @selected($filterStatus === 'active')>Aktif</option>
                    <option value="inactive" @selected($filterStatus === 'inactive')>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($users->isEmpty())
            <x-empty-state icon="fa-user-slash" title="Tidak ada pengguna" text="Tidak ada pengguna yang cocok dengan filter." />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Pengguna</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Terakhir Dibuat</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="avatar-circle">{{ substr($user->name, 0, 1) }}</span>
                                        <div>
                                            <span class="cell-title">{{ $user->name }}</span>
                                            <div class="cell-sub">{{ '@'.$user->username }} · {{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><x-status-badge kind="role" :value="$user->role" /></td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-secondary"><i class="fa-solid fa-circle-minus"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td class="cell-sub">{{ tanggal($user->created_at) }}</td>
                                <td class="text-end">
                                    <div class="d-flex gap-1 justify-content-end">
                                        <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Ubah">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.toggle', $user) }}"
                                                  data-confirm="{{ $user->is_active ? 'Nonaktifkan akun '.$user->name.'? Pengguna yang login akan keluar dari sistem.' : 'Aktifkan kembali akun '.$user->name.'?' }}"
                                                  data-confirm-title="{{ $user->is_active ? 'Nonaktifkan akun?' : 'Aktifkan akun?' }}"
                                                  data-confirm-icon="{{ $user->is_active ? 'warning' : 'question' }}"
                                                  data-confirm-button="{{ $user->is_active ? 'Ya, nonaktifkan' : 'Ya, aktifkan' }}">
                                                @csrf
                                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}"
                                                        title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-user-slash' : 'fa-user-check' }}"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $users->links('pagination::bootstrap-5') }}
@endsection
