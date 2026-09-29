@extends('layouts.app')

@section('title', 'Pemeriksaan Kendaraan')

@section('content')
    <x-page-header
        title="Pemeriksaan Kendaraan"
        subtitle="Riwayat pemeriksaan kondisi kendaraan: serah terima, pengembalian, dan rutin.">
        <x-slot name="actions">
            <a href="{{ route('inspections.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-clipboard-check me-1"></i> Pemeriksaan Baru
            </a>
        </x-slot>
    </x-page-header>

    <form method="GET" action="{{ route('inspections.index') }}" class="filter-bar">
        <div class="row g-2 align-items-end">
            <div class="col-md-3">
                <label class="form-label" for="type">Tipe Pemeriksaan</label>
                <select name="type" id="type" class="form-select">
                    <option value="">Semua tipe</option>
                    @foreach ($types as $value => $label)
                        <option value="{{ $value }}" @selected($filterType === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" for="search">Pencarian</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ $search }}"
                       placeholder="No. polisi, kode unit, no. transaksi">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-1"></i> Terapkan</button>
                <a href="{{ route('inspections.index') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        </div>
    </form>

    <x-panel :flush="true">
        @if ($inspections->isEmpty())
            <x-empty-state icon="fa-clipboard-check" title="Tidak ada pemeriksaan"
                           text="Belum ada pemeriksaan yang cocok dengan filter.">
                <x-slot name="action">
                    <a href="{{ route('inspections.create') }}" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-plus me-1"></i> Pemeriksaan Baru
                    </a>
                </x-slot>
            </x-empty-state>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Tipe</th>
                            <th>Kendaraan</th>
                            <th>Transaksi</th>
                            <th class="text-end">Odometer</th>
                            <th>Bahan Bakar</th>
                            <th>Petugas</th>
                            <th class="text-end"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($inspections as $inspection)
                            <tr>
                                <td>{{ tanggal_waktu($inspection->inspected_at) }}</td>
                                <td><x-status-badge kind="inspection" :value="$inspection->type" /></td>
                                <td>
                                    <a href="{{ route('vehicles.show', $inspection->vehicle) }}" class="cell-title">
                                        {{ $inspection->vehicle?->code }}
                                    </a>
                                    <div class="cell-sub">{{ $inspection->vehicle?->license_plate }}</div>
                                </td>
                                <td class="cell-sub">
                                    @if ($inspection->transaction)
                                        <a href="{{ route('transactions.show', $inspection->transaction) }}">
                                            {{ $inspection->transaction->transaction_number }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-end-tabular">{{ $inspection->odometer !== null ? number_format($inspection->odometer).' km' : '-' }}</td>
                                <td>{{ $inspection->fuel_level?->label() ?? '-' }}</td>
                                <td class="cell-sub">{{ $inspection->inspector?->name }}</td>
                                <td class="text-end">
                                    <a href="{{ route('inspections.show', $inspection) }}" class="btn btn-sm btn-outline-primary" title="Detail">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-panel>

    {{ $inspections->links('pagination::bootstrap-5') }}
@endsection
