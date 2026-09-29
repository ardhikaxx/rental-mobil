@extends('layouts.app')

@section('title', 'Detail Pelanggan')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Pelanggan', 'url' => route('customers.index')],
        ['label' => $customer->name],
    ]" />

    <x-page-header
        :title="$customer->name"
        :subtitle="$customer->id_number.' · '.$customer->phone.($customer->email ? ' · '.$customer->email : '')">
        <x-slot name="actions">
            @can('manage-customers')
                <a href="{{ route('customers.edit', $customer) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen me-1"></i> Ubah
                </a>
                <a href="{{ route('transactions.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary">
                    <i class="fa-solid fa-plus me-1"></i> Transaksi Baru
                </a>
            @endcan
        </x-slot>
    </x-page-header>

    <div class="row g-3 mb-3">
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-file-invoice" label="Total Transaksi" :value="$stats['totalTransactions']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-spinner" label="Transaksi Aktif" :value="$stats['activeTransactions']" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-money-bill-wave" label="Total Nilai Rental" :value="rupiah($stats['totalValue'])" />
        </div>
        <div class="col-6 col-xl-3">
            <x-stat icon="fa-calendar" label="Transaksi Terakhir"
                :value="$stats['lastTransaction'] ? tanggal($stats['lastTransaction']->start_at) : '-'"
                sub="periode mulai" />
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <x-panel title="Informasi Pelanggan">
                <div class="kv"><span class="kv-label">Nomor identitas</span><span class="kv-value">{{ $customer->id_number }}</span></div>
                <div class="kv"><span class="kv-label">Telepon</span><span class="kv-value">{{ $customer->phone }}</span></div>
                <div class="kv"><span class="kv-label">Email</span><span class="kv-value">{{ $customer->email ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Tanggal lahir</span><span class="kv-value">{{ $customer->birth_date ? tanggal($customer->birth_date) : '-' }}</span></div>
                <div class="kv"><span class="kv-label">Alamat</span><span class="kv-value">{{ $customer->address ?: '-' }}</span></div>
                <div class="kv"><span class="kv-label">Terdaftar</span><span class="kv-value">{{ tanggal($customer->created_at) }}</span></div>

                @if ($customer->notes)
                    <div class="form-hint mt-3">{{ $customer->notes }}</div>
                @endif
            </x-panel>

            <x-panel title="Verifikasi KTP & SIM A" class="mt-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <span class="text-muted-2 small">Status Verifikasi</span>
                    @if ($customer->isVerified())
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            <i class="fa-solid fa-circle-check me-1"></i>Terverifikasi
                        </span>
                    @elseif ($customer->isPending())
                        <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1">
                            <i class="fa-solid fa-hourglass-half me-1"></i>Menunggu Verifikasi
                        </span>
                    @else
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                            <i class="fa-solid fa-circle-xmark me-1"></i>Ditolak
                        </span>
                    @endif
                </div>

                @if ($customer->isVerified())
                    <div class="alert alert-success py-2 px-3 small mb-3">
                        <i class="fa-solid fa-user-check me-1"></i>
                        Diverifikasi oleh <strong>{{ $customer->verifiedBy?->name ?? 'Admin Sistem' }}</strong>
                        @if ($customer->verified_at)
                            pada {{ tanggal_waktu($customer->verified_at) }}
                        @endif
                    </div>
                @elseif ($customer->isRejected())
                    <div class="alert alert-danger py-2 px-3 small mb-3">
                        <div class="fw-bold mb-1"><i class="fa-solid fa-triangle-exclamation me-1"></i>Verifikasi Ditolak:</div>
                        <div>{{ $customer->rejection_reason ?: 'Dokumen tidak memenuhi persyaratan verifikasi identitas rental.' }}</div>
                    </div>
                @endif

                <div class="kv"><span class="kv-label">Nomor KTP</span><span class="kv-value">{{ $customer->id_number }}</span></div>
                <div class="kv"><span class="kv-label">Nomor SIM A</span><span class="kv-value">{{ $customer->sim_number ?: 'Belum diisi' }}</span></div>

                <div class="mt-3 pt-2 border-top">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small text-muted-2 mb-1">Foto KTP</label>
                            @if ($customer->ktp_photo)
                                <a href="{{ route('media.customer-ktp', $customer) }}" target="_blank" class="d-block border rounded p-1 bg-light text-center" title="Klik untuk perbesar KTP">
                                    <img src="{{ route('media.customer-ktp', $customer) }}" alt="Foto KTP" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                    <div class="small text-primary mt-1" style="font-size:0.75rem"><i class="fa-solid fa-up-right-from-square me-1"></i>Buka KTP</div>
                                </a>
                            @else
                                <div class="p-3 text-center border rounded bg-light text-muted-2 small">
                                    <i class="fa-regular fa-id-card fa-2x mb-1 d-block"></i>
                                    Belum ada foto
                                </div>
                            @endif
                        </div>
                        <div class="col-6">
                            <label class="form-label small text-muted-2 mb-1">Foto SIM A</label>
                            @if ($customer->sim_photo)
                                <a href="{{ route('media.customer-sim', $customer) }}" target="_blank" class="d-block border rounded p-1 bg-light text-center" title="Klik untuk perbesar SIM A">
                                    <img src="{{ route('media.customer-sim', $customer) }}" alt="Foto SIM" class="img-fluid rounded" style="max-height: 100px; width: 100%; object-fit: cover;">
                                    <div class="small text-primary mt-1" style="font-size:0.75rem"><i class="fa-solid fa-up-right-from-square me-1"></i>Buka SIM</div>
                                </a>
                            @else
                                <div class="p-3 text-center border rounded bg-light text-muted-2 small">
                                    <i class="fa-regular fa-id-badge fa-2x mb-1 d-block"></i>
                                    Belum ada foto
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                @can('manage-customers')
                    <div class="mt-3 pt-2 border-top">
                        @if (! $customer->isVerified())
                            <form method="POST" action="{{ route('customers.verify', $customer) }}" class="mb-2"
                                  data-confirm="Verifikasi dan setujui identitas pelanggan {{ $customer->name }}?"
                                  data-confirm-title="Setujui Identitas"
                                  data-confirm-button="Ya, Setujui">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm w-100">
                                    <i class="fa-solid fa-check me-1"></i> Setujui & Verifikasi Identitas
                                </button>
                            </form>
                        @endif

                        @if (! $customer->isRejected())
                            <button class="btn btn-outline-danger btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#rejectCustomerBox">
                                <i class="fa-solid fa-xmark me-1"></i> Tolak Dokumen / Identitas
                            </button>
                            <div class="collapse mt-2" id="rejectCustomerBox">
                                <form method="POST" action="{{ route('customers.reject', $customer) }}" class="p-2 border rounded bg-light border-danger-subtle">
                                    @csrf
                                    <label class="form-label small fw-semibold text-danger mb-1">Alasan Penolakan:</label>
                                    <textarea name="rejection_reason" class="form-control form-control-sm mb-2" rows="2" required placeholder="Misal: Foto KTP tidak jelas / NIK tidak cocok."></textarea>
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        Konfirmasi Penolakan
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                @endcan
            </x-panel>
        </div>

        <div class="col-lg-8">
            <x-panel title="Riwayat Rental" :flush="true">
                @if ($transactions->isEmpty())
                    <x-empty-state icon="fa-file-invoice" title="Belum ada transaksi"
                                   text="Pelanggan ini belum pernah melakukan rental.">
                        @can('manage-customers')
                            <x-slot name="action">
                                <a href="{{ route('transactions.create', ['customer_id' => $customer->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fa-solid fa-plus me-1"></i> Buat Transaksi
                                </a>
                            </x-slot>
                        @endcan
                    </x-empty-state>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No. Transaksi</th>
                                    <th>Kendaraan</th>
                                    <th>Periode</th>
                                    <th>Status</th>
                                    <th class="text-end">Total</th>
                                    <th class="text-end">Sisa</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ route('transactions.show', $transaction) }}" class="cell-title">
                                                {{ $transaction->transaction_number }}
                                            </a>
                                            <div class="cell-sub">{{ $transaction->booking_source->label() }}</div>
                                        </td>
                                        <td class="cell-sub">
                                            {{ $transaction->vehicle?->code }}<br>{{ $transaction->vehicle?->license_plate }}
                                        </td>
                                        <td class="cell-sub">
                                            {{ tanggal($transaction->start_at) }} — {{ tanggal($transaction->end_at) }}
                                        </td>
                                        <td><x-status-badge kind="transaction" :value="$transaction->status" /></td>
                                        <td class="text-end-tabular">{{ rupiah($transaction->total) }}</td>
                                        <td class="text-end-tabular">
                                            @php($balance = $transaction->totalPayable() - (int) ($transaction->paid_amount ?? $transaction->payments()->sum('amount')))
                                            @if ($transaction->status->value === 'cancelled')
                                                <span class="text-muted-2">-</span>
                                            @elseif ($balance > 0)
                                                <span class="text-danger">{{ rupiah($balance) }}</span>
                                            @else
                                                <span class="text-success">Lunas</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-panel>

            {{ $transactions->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
