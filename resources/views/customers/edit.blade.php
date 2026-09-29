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
                <form method="POST" action="{{ route('customers.update', $customer) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <x-input name="name" label="Nama Lengkap" :value="$customer->name" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="id_number" label="Nomor Identitas (NIK/KTP)" :value="$customer->id_number" required />
                        </div>
                        <div class="col-md-6">
                            <x-input name="sim_number" label="Nomor SIM A" :value="$customer->sim_number" placeholder="Misal: 1234-5678-901234" />
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="ktp_photo">Foto / Dokumen KTP</label>
                                @if ($customer->ktp_photo)
                                    <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light border rounded">
                                        <img src="{{ route('media.customer-ktp', $customer) }}" alt="KTP"
                                             class="rounded border" style="width:60px;height:40px;object-fit:cover">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('media.customer-ktp', $customer) }}" target="_blank" class="small fw-semibold text-primary">
                                                <i class="fa-solid fa-up-right-from-square me-1"></i>Lihat KTP
                                            </a>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" name="delete_ktp_photo" id="delete_ktp_photo" value="1">
                                                <label class="form-check-label text-danger small" for="delete_ktp_photo">
                                                    <i class="fa-solid fa-trash me-1"></i>Hapus KTP
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="ktp_photo" id="ktp_photo" class="form-control @error('ktp_photo') is-invalid @enderror" accept="image/*">
                                <div class="form-hint">
                                    {{ $customer->ktp_photo ? 'Pilih file baru jika ingin mengganti KTP.' : 'Format JPG/PNG/WebP, maks. 5MB.' }}
                                </div>
                                @error('ktp_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="sim_photo">Foto / Dokumen SIM A</label>
                                @if ($customer->sim_photo)
                                    <div class="d-flex align-items-center gap-2 mb-2 p-2 bg-light border rounded">
                                        <img src="{{ route('media.customer-sim', $customer) }}" alt="SIM"
                                             class="rounded border" style="width:60px;height:40px;object-fit:cover">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('media.customer-sim', $customer) }}" target="_blank" class="small fw-semibold text-primary">
                                                <i class="fa-solid fa-up-right-from-square me-1"></i>Lihat SIM A
                                            </a>
                                            <div class="form-check mt-1">
                                                <input class="form-check-input" type="checkbox" name="delete_sim_photo" id="delete_sim_photo" value="1">
                                                <label class="form-check-label text-danger small" for="delete_sim_photo">
                                                    <i class="fa-solid fa-trash me-1"></i>Hapus SIM A
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <input type="file" name="sim_photo" id="sim_photo" class="form-control @error('sim_photo') is-invalid @enderror" accept="image/*">
                                <div class="form-hint">
                                    {{ $customer->sim_photo ? 'Pilih file baru jika ingin mengganti SIM A.' : 'Format JPG/PNG/WebP, maks. 5MB.' }}
                                </div>
                                @error('sim_photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
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
