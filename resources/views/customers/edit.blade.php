@extends('layouts.app')

@section('title', 'Ubah Pelanggan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pelanggan', 'url' => route('customers.index')],
        ['label' => $customer->name, 'url' => route('customers.show', $customer)],
        ['label' => 'Ubah'],
    ]" />

    <x-page-header title="Ubah Pelanggan" subtitle="Perbarui data {{ $customer->name }}." />

    <div class="row g-3">
        <div class="col-lg-7">
            <x-panel title="Identitas Pelanggan">
                <form method="POST" action="{{ route('customers.update', $customer) }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" :value="$customer->name" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="id_number" label="Nomor Identitas (KTP)" :value="$customer->id_number" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="phone" label="Nomor Telepon" :value="$customer->phone" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="email" label="Email" type="email" :value="$customer->email" placeholder="Opsional" />
                        </div>
                        <div class="col-md-6">
                            <x-input name="birth_date" label="Tanggal Lahir" type="date" :value="$customer->birth_date?->toDateString()" />
                        </div>
                        <div class="col-12">
                            <x-input name="address" label="Alamat" :value="$customer->address" placeholder="Opsional" />
                        </div>
                        <div class="col-12">
                            <x-input name="notes" label="Catatan" type="textarea" :value="$customer->notes" />
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan
                        </button>
                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-outline-secondary">Batal</a>
                    </div>
                </form>
            </x-panel>
        </div>

        <div class="col-lg-5">
            <x-panel title="Ringkasan">
                <div class="kv"><span class="kv-label">Transaksi</span><span class="kv-value">{{ $customer->transactions()->count() }}x</span></div>
                <div class="kv"><span class="kv-label">Transaksi aktif</span><span class="kv-value">{{ $customer->transactions()->whereIn('status', \App\Enums\TransactionStatus::blocking())->count() }}x</span></div>
                <div class="kv"><span class="kv-label">Terdaftar sejak</span><span class="kv-value">{{ tanggal($customer->created_at) }}</span></div>
            </x-panel>
        </div>
    </div>
@endsection
