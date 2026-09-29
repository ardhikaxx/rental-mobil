@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
    <x-page-header
        title="Audit Log"
        subtitle="Riwayat aktivitas penting pengguna: transaksi, pembayaran, status, dan pengaturan." />

    <form method="GET" action="{{ route('audit-logs.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="module">Modul</label>
                <select name="module" id="module" class="form-select">
                    <option value="">Semua modul</option>
                    @foreach ($modules as $module)
                        <option value="{{ $module }}" @selected($filterModule === $module)>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="activity">Aktivitas</label>
                <select name="activity" id="activity" class="form-select">
                    <option value="">Semua aktivitas</option>
                    @foreach ($activities as $activity)
                        <option value="{{ $activity }}" @selected($filterActivity === $activity)>{{ $activity }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="user_id">Pengguna</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">Semua pengguna</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected((string) $filterUser === (string) $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}" placeholder="Kata kunci">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                <a href="{{ route('audit-logs.index') }}" class="btn btn-outline-secondary"><i class="fa-solid fa-rotate-left"></i></a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($logs->isEmpty())
            <x-empty-state icon="fa-clock-rotate-left" title="Tidak ada aktivitas"
                           text="Belum ada audit log yang cocok dengan filter." />
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Pengguna</th>
                            <th>Modul</th>
                            <th>Aktivitas</th>
                            <th>Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($logs as $log)
                            <tr>
                                <td class="cell-sub" style="white-space:nowrap">{{ tanggal_waktu($log->created_at) }}</td>
                                <td>
                                    @if ($log->user)
                                        <span class="cell-title">{{ $log->user->name }}</span>
                                        <div class="cell-sub">{{ $log->user->role->label() }}</div>
                                    @else
                                        <span class="text-muted-2">Sistem</span>
                                    @endif
                                </td>
                                <td><span class="badge badge-secondary">{{ $log->module }}</span></td>
                                <td><span class="badge badge-info">{{ $log->activity }}</span></td>
                                <td style="font-size:.86rem">{{ $log->description }}
                                    @if ($log->ip_address)
                                        <div class="cell-sub">IP {{ $log->ip_address }}</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $logs->links('pagination::bootstrap-5') }}
@endsection
