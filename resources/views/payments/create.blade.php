@extends('layouts.app')

@section('title', 'Catat Pembayaran')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pembayaran', 'url' => route('payments.index')],
        ['label' => 'Catat Pembayaran'],
    ]" />

    <x-page-header title="Catat Pembayaran" subtitle="Pembayaran hanya dapat dicatat untuk transaksi yang masih memiliki sisa tagihan." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Detail Pembayaran">
                @if ($transactions->isEmpty())
                    <x-empty-state icon="fa-circle-check" title="Semua transaksi lunas"
                                   text="Tidak ada transaksi dengan sisa tagihan saat ini." />
                @else
                    <form method="POST" action="{{ route('payments.store') }}">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <x-input name="transaction_id" label="Transaksi" type="select"
                                         :options="$transactions->mapWithKeys(fn ($t) => [$t->id => $t->transaction_number.' — '.$t->customer?->name.' — sisa '.rupiah($t->balance())])->all()"
                                         :value="old('transaction_id', $selectedTransaction)"
                                         required placeholder="Pilih transaksi" />
                            </div>
                            <div class="col-md-6">
                                <x-input name="amount" label="Nominal (Rp)" type="number" :value="old('amount')" required />
                            </div>
                            <div class="col-md-6">
                                <x-input name="type" label="Jenis Pembayaran" type="select" :options="$types"
                                         :value="old('type', 'dp')" required placeholder="Pilih jenis" />
                            </div>
                            <div class="col-md-6">
                                <x-input name="method" label="Metode Pembayaran" type="select" :options="$methods"
                                         :value="old('method', 'cash')" required placeholder="Pilih metode" />
                            </div>
                            <div class="col-md-6">
                                <x-input name="paid_at" label="Tanggal Pembayaran" type="date"
                                         :value="old('paid_at', now()->toDateString())" required />
                            </div>
                            <div class="col-12">
                                <x-input name="notes" label="Catatan" :value="old('notes')" placeholder="Opsional" />
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Pembayaran
                            </button>
                            <a href="{{ route('payments.index') }}" class="btn btn-outline-secondary">Batal</a>
                        </div>
                    </form>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Aturan Pencatatan">
                <ul class="mb-0 ps-3" style="font-size:.87rem">
                    <li class="mb-2">Nominal tidak boleh melebihi sisa tagihan transaksi (termasuk denda).</li>
                    <li class="mb-2">Pembayaran DP, cicilan, pelunasan, dan denda dicatat dengan jenis yang berbeda agar laporan akurat.</li>
                    <li class="mb-2">Setiap pembayaran menghasilkan nomor pembayaran unik dan tercatat pada timeline transaksi beserta petugas yang mencatat.</li>
                </ul>
            </x-panel>
        </div>
    </div>
@endsection
