@extends('layouts.app')

@section('title', 'Detail Transaksi')

@section('content')
    <x-breadcrumb :items="[
        ['label' => 'Transaksi', 'url' => route('transactions.index')],
        ['label' => $transaction->transaction_number],
    ]" />

    <x-page-header
        :title="$transaction->transaction_number"
        :subtitle="$transaction->customer?->name.' · '.$transaction->vehicle?->brand.' '.$transaction->vehicle?->model.' ('.$transaction->vehicle?->license_plate.')'">
        <x-slot name="actions">
            <x-status-badge kind="transaction" :value="$transaction->status" />
            <a href="{{ route('transactions.invoice', $transaction) }}" class="btn btn-outline-primary" target="_blank">
                <i class="fa-solid fa-print me-1"></i> Invoice
            </a>
            <a href="{{ route('transactions.spk', $transaction) }}" class="btn btn-outline-primary" target="_blank">
                <i class="fa-solid fa-file-contract me-1"></i> Cetak SPK
            </a>
            @if ($canEdit)
                <a href="{{ route('transactions.edit', $transaction) }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-pen me-1"></i> Ubah
                </a>
            @endif
            @if ($canApprove)
                <form method="POST" action="{{ route('transactions.approve', $transaction) }}"
                      data-confirm="Setujui booking ini? Kendaraan akan dikunci untuk periode rental ini."
                      data-confirm-title="Setujui booking"
                      data-confirm-button="Ya, setujui">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-check me-1"></i> Setujui Booking
                    </button>
                </form>
            @endif
            @if ($canMarkReady)
                <form method="POST" action="{{ route('transactions.mark-ready', $transaction) }}"
                      data-confirm="Tandai transaksi ini siap diserahkan?"
                      data-confirm-title="Siap diserahkan"
                      data-confirm-button="Ya, tandai">
                    @csrf
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fa-solid fa-key me-1"></i> Siap Diserahkan
                    </button>
                </form>
            @endif
            @if ($canHandover)
                <a href="{{ route('handover.create', $transaction) }}" class="btn btn-primary">
                    <i class="fa-solid fa-car-side me-1"></i> Proses Serah Terima
                </a>
            @endif
            @if ($canReturn)
                <a href="{{ route('return.create', $transaction) }}" class="btn btn-primary">
                    <i class="fa-solid fa-rotate-left me-1"></i> Proses Pengembalian
                </a>
            @endif
        </x-slot>
    </x-page-header>

    @if ($transaction->isOverdue())
        <div class="panel mb-3" style="border-left:3px solid #dc2626">
            <div class="panel-body d-flex align-items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                <div>
                    <strong>Kendaraan melewati batas pengembalian.</strong>
                    <span class="text-muted-2">
                        Tenggat {{ tanggal_waktu($transaction->end_at) }}. Keterlambatan akan dihitung saat pengembalian diproses.
                    </span>
                </div>
            </div>
        </div>
    @elseif ($transaction->isDueSoon())
        <div class="panel mb-3" style="border-left:3px solid #d97706">
            <div class="panel-body d-flex align-items-center gap-2">
                <i class="fa-solid fa-clock text-warning"></i>
                <div>
                    <strong>Pengembalian segera berakhir.</strong>
                    <span class="text-muted-2">Tenggat {{ tanggal_waktu($transaction->end_at) }}.</span>
                </div>
            </div>
        </div>
    @endif

    @if ($transaction->status->value !== 'cancelled' && $transaction->balance() > 0 && $canPay)
        <div class="panel mb-3" style="border-left:3px solid #2563eb">
            <div class="panel-body d-flex align-items-center justify-content-between gap-3 flex-wrap">
                <div>
                    <strong>Sisa tagihan {{ rupiah($transaction->balance()) }}</strong>
                    <span class="text-muted-2">dari total {{ rupiah($transaction->totalPayable()) }}.</span>
                </div>
                <button type="button" class="btn btn-primary btn-sm" onclick="document.getElementById('paymentFormPanel').scrollIntoView({behavior:'smooth'})">
                    <i class="fa-solid fa-money-bill-transfer me-1"></i> Catat Pembayaran
                </button>
            </div>
        </div>
    @endif

    <div class="row g-3">
        <div class="col-lg-4">
            <x-panel title="Pelanggan">
                <div class="kv"><span class="kv-label">Nama</span>
                    <span class="kv-value"><a href="{{ route('customers.show', $transaction->customer) }}">{{ $transaction->customer?->name }}</a></span></div>
                <div class="kv"><span class="kv-label">Identitas (KTP)</span>
                    <span class="kv-value d-flex align-items-center gap-1 justify-content-end">
                        {{ $transaction->customer?->id_number }}
                        @if ($transaction->customer?->verification_status === 'verified')
                            <span class="badge bg-success-subtle text-success p-1" title="KTP Terverifikasi"><i class="fa-solid fa-circle-check"></i></span>
                        @else
                            <span class="badge bg-warning-subtle text-warning p-1" title="KTP Belum Terverifikasi"><i class="fa-solid fa-clock"></i></span>
                        @endif
                    </span>
                </div>
                <div class="kv"><span class="kv-label">Nomor SIM A</span>
                    <span class="kv-value">{{ $transaction->customer?->sim_number ?: 'Belum diisi' }}</span></div>
                <div class="kv"><span class="kv-label">Telepon</span><span class="kv-value">{{ $transaction->customer?->phone }}</span></div>
                <div class="kv"><span class="kv-label">Sumber booking</span>
                    <span class="kv-value"><x-status-badge kind="booking_source" :value="$transaction->booking_source" /></span></div>
                <div class="kv"><span class="kv-label">Dibuat oleh</span><span class="kv-value">{{ $transaction->creator?->name ?? '-' }}</span></div>
            </x-panel>

            <x-panel title="Layanan & Supir">
                <div class="kv">
                    <span class="kv-label">Jenis Layanan</span>
                    <span class="kv-value">
                        @if ($transaction->with_driver)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                <i class="fa-solid fa-user-tie me-1"></i> Dengan Supir
                            </span>
                        @else
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">
                                <i class="fa-solid fa-key me-1"></i> Lepas Kunci
                            </span>
                        @endif
                    </span>
                </div>
                @if ($transaction->with_driver)
                    <div class="kv">
                        <span class="kv-label">Supir Ditugaskan</span>
                        <span class="kv-value fw-semibold text-primary">
                            {{ $transaction->driver?->name ?? 'Belum ditentukan' }}
                        </span>
                    </div>
                    @if ($transaction->driver)
                        <div class="kv"><span class="kv-label">Kontak Supir</span><span class="kv-value">{{ $transaction->driver->phone }}</span></div>
                        <div class="kv"><span class="kv-label">Tarif Supir / Hari</span><span class="kv-value">{{ rupiah($transaction->driver_rate) }}</span></div>
                        <div class="kv"><span class="kv-label">Total Jasa Supir</span><span class="kv-value fw-semibold">{{ rupiah($transaction->driver_fee) }}</span></div>
                    @endif
                @endif
            </x-panel>

            <x-panel title="Jaminan / Security Deposit">
                <div class="kv">
                    <span class="kv-label">Jenis Jaminan</span>
                    <span class="kv-value fw-semibold">{{ $transaction->deposit_type ? ucfirst($transaction->deposit_type) : 'Jaminan Standar' }}</span>
                </div>
                <div class="kv">
                    <span class="kv-label">Nominal Deposit</span>
                    <span class="kv-value fw-bold {{ $transaction->deposit_amount > 0 ? 'text-primary' : '' }}">
                        {{ $transaction->deposit_amount > 0 ? rupiah($transaction->deposit_amount) : 'Rp 0' }}
                    </span>
                </div>
                <div class="kv">
                    <span class="kv-label">Status Jaminan</span>
                    <span class="kv-value">
                        @php
                            $depositBadges = [
                                'pending' => ['bg' => 'bg-warning-subtle text-warning border-warning-subtle', 'label' => 'Menunggu Setor'],
                                'held' => ['bg' => 'bg-info-subtle text-info border-info-subtle', 'label' => 'Ditahan Garasi'],
                                'refunded' => ['bg' => 'bg-success-subtle text-success border-success-subtle', 'label' => 'Dikembalikan'],
                                'forfeited' => ['bg' => 'bg-danger-subtle text-danger border-danger-subtle', 'label' => 'Hangus / Diklaim'],
                                'none' => ['bg' => 'bg-light text-muted border', 'label' => 'Tanpa Deposit'],
                            ];
                            $db = $depositBadges[$transaction->deposit_status] ?? ['bg' => 'bg-light text-dark', 'label' => $transaction->deposit_status];
                        @endphp
                        <span class="badge {{ $db['bg'] }} border">{{ $db['label'] }}</span>
                    </span>
                </div>
                @if ($transaction->deposit_notes)
                    <div class="form-hint mt-2 mb-2"><i class="fa-solid fa-receipt me-1"></i> {{ $transaction->deposit_notes }}</div>
                @endif
                @if ($transaction->deposit_refunded_at)
                    <div class="kv"><span class="kv-label">Dikembalikan</span>
                        <span class="kv-value">{{ tanggal_waktu($transaction->deposit_refunded_at) }} ({{ $transaction->depositRefundedBy?->name ?? 'Staff' }})</span></div>
                @endif

                @if ($canManage && $transaction->deposit_status !== 'none')
                    <div class="border-top pt-2 mt-2">
                        <form method="POST" action="{{ route('transactions.deposit.update', $transaction) }}" class="d-flex flex-column gap-2">
                            @csrf
                            <div class="d-flex gap-2">
                                <select name="deposit_status" class="form-select form-select-sm">
                                    <option value="pending" @selected($transaction->deposit_status === 'pending')>Menunggu Setor</option>
                                    <option value="held" @selected($transaction->deposit_status === 'held')>Ditahan Garasi</option>
                                    <option value="refunded" @selected($transaction->deposit_status === 'refunded')>Dikembalikan (Refund)</option>
                                    <option value="forfeited" @selected($transaction->deposit_status === 'forfeited')>Hangus / Klaim Denda</option>
                                </select>
                                <button type="submit" class="btn btn-outline-secondary btn-sm" style="white-space:nowrap">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </x-panel>

            <x-panel title="Kendaraan">
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="avatar-circle" style="width:56px;height:42px;border-radius:6px">
                        @if ($transaction->vehicle?->photo)
                            <img src="{{ $transaction->vehicle->photo_url }}" alt="kendaraan">
                        @else
                            <i class="fa-solid fa-car-side"></i>
                        @endif
                    </span>
                    <div>
                        <a href="{{ route('vehicles.show', $transaction->vehicle) }}" class="cell-title">
                            {{ $transaction->vehicle?->code }} — {{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }}
                        </a>
                        <div class="cell-sub">{{ $transaction->vehicle?->license_plate }}</div>
                    </div>
                </div>
                <div class="kv"><span class="kv-label">Status kendaraan</span>
                    <span class="kv-value"><x-status-badge kind="vehicle" :value="$transaction->vehicle?->status" /></span></div>
                <div class="kv"><span class="kv-label">Tarif harian mobil</span><span class="kv-value">{{ rupiah($transaction->daily_rate) }}</span></div>
            </x-panel>

            <x-panel title="Periode Rental">
                <div class="kv"><span class="kv-label">Mulai</span><span class="kv-value">{{ tanggal_waktu($transaction->start_at) }}</span></div>
                <div class="kv"><span class="kv-label">Rencana kembali</span><span class="kv-value">{{ tanggal_waktu($transaction->end_at) }}</span></div>
                <div class="kv"><span class="kv-label">Durasi</span><span class="kv-value">{{ $transaction->rental_days }} hari</span></div>
                <div class="kv"><span class="kv-label">Diserahkan</span>
                    <span class="kv-value">{{ $transaction->handover_at ? tanggal_waktu($transaction->handover_at) : 'Belum' }}</span></div>
                <div class="kv"><span class="kv-label">Dikembalikan</span>
                    <span class="kv-value">{{ $transaction->actual_return_at ? tanggal_waktu($transaction->actual_return_at) : 'Belum' }}</span></div>
                @if ($transaction->late_minutes > 0)
                    <div class="kv"><span class="kv-label">Keterlambatan</span>
                        <span class="kv-value text-danger">
                            {{ $transaction->late_minutes }} menit terlambat
                        </span></div>
                @endif
                @if ($transaction->notes)
                    <div class="form-hint mt-2">{{ $transaction->notes }}</div>
                @endif
            </x-panel>
        </div>

        <div class="col-lg-8">
            <x-panel title="Keuangan">
                <div class="row">
                    <div class="col-md-6">
                        <div class="kv"><span class="kv-label">Sewa mobil ({{ $transaction->rental_days }} hari × {{ rupiah($transaction->daily_rate) }})</span>
                            <span class="kv-value">{{ rupiah($transaction->daily_rate * $transaction->rental_days) }}</span></div>
                        @if ($transaction->with_driver)
                            <div class="kv"><span class="kv-label">Jasa supir ({{ $transaction->rental_days }} hari × {{ rupiah($transaction->driver_rate) }})</span>
                                <span class="kv-value text-primary">+ {{ rupiah($transaction->driver_fee) }}</span></div>
                        @endif
                        <div class="kv"><span class="kv-label fw-semibold">Subtotal</span>
                            <span class="kv-value fw-semibold">{{ rupiah($transaction->subtotal) }}</span></div>
                        <div class="kv"><span class="kv-label">Diskon</span><span class="kv-value text-danger">- {{ rupiah($transaction->discount) }}</span></div>
                        <div class="kv"><span class="kv-label">Denda keterlambatan</span>
                            <span class="kv-value {{ $transaction->late_fee > 0 ? 'text-danger' : '' }}">{{ rupiah($transaction->late_fee) }}</span></div>
                        <div class="kv"><span class="kv-label fw-bold text-dark">Total Tagihan</span>
                            <span class="kv-value" style="font-size:1.02rem">{{ rupiah($transaction->totalPayable()) }}</span></div>
                    </div>
                    <div class="col-md-6">
                        <div class="kv"><span class="kv-label">Total dibayar</span><span class="kv-value">{{ rupiah($transaction->paidAmount()) }}</span></div>
                        <div class="kv"><span class="kv-label fw-bold text-dark">Sisa Tagihan</span>
                            <span class="kv-value {{ $transaction->balance() > 0 ? 'text-danger' : 'text-success' }}" style="font-size:1.02rem">
                                {{ rupiah($transaction->balance()) }}
                            </span></div>
                        <div class="kv"><span class="kv-label">Status bayar</span>
                            <span class="kv-value">
                                @if ($transaction->status->value === 'cancelled')
                                    <span class="badge badge-secondary">Dibatalkan</span>
                                @elseif ($transaction->balance() <= 0)
                                    <span class="badge badge-success">Lunas</span>
                                @else
                                    <span class="badge badge-warning">Belum lunas</span>
                                @endif
                            </span></div>
                    </div>
                </div>

                <div class="mt-3">
                    <div class="section-title">Riwayat Pembayaran</div>
                    @if ($transaction->payments->isEmpty())
                        <x-empty-state icon="fa-money-bill-wave" title="Belum ada pembayaran"
                                       text="Belum ada pembayaran tercatat untuk transaksi ini." />
                    @else
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Bayar</th>
                                        <th>Tanggal</th>
                                        <th>Jenis</th>
                                        <th>Metode</th>
                                        <th>Petugas</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->payments as $payment)
                                        <tr>
                                            <td class="cell-title">{{ $payment->payment_number }}</td>
                                            <td>{{ tanggal($payment->paid_at) }}</td>
                                            <td><x-status-badge kind="payment_type" :value="$payment->type" /></td>
                                            <td><x-status-badge kind="payment_method" :value="$payment->method" /></td>
                                            <td class="cell-sub">{{ $payment->recorder?->name }}</td>
                                            <td class="text-end-tabular">{{ rupiah($payment->amount) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </x-panel>

            @if ($canPay)
                <div id="paymentFormPanel">
                    <x-panel title="Catat Pembayaran">
                        <form method="POST" action="{{ route('payments.store') }}">
                            @csrf
                            <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <x-input name="amount" label="Nominal (Rp)" type="number" :value="old('amount', $transaction->balance() > 0 ? $transaction->balance() : '')" required />
                                </div>
                                <div class="col-md-3">
                                    <x-input name="type" label="Jenis" type="select" :options="$paymentTypes" value="{{ old('type', $transaction->late_fee > 0 && $transaction->balance() == $transaction->late_fee ? 'denda' : 'cicilan') }}" required placeholder="Pilih jenis" />
                                </div>
                                <div class="col-md-3">
                                    <x-input name="method" label="Metode" type="select" :options="$paymentMethods" value="{{ old('method', 'cash') }}" required placeholder="Pilih metode" />
                                </div>
                                <div class="col-md-3">
                                    <x-input name="paid_at" label="Tanggal Bayar" type="date" value="{{ old('paid_at', now()->toDateString()) }}" required />
                                </div>
                                <div class="col-12">
                                    <x-input name="notes" label="Catatan" value="{{ old('notes') }}" placeholder="Opsional" />
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-plus me-1"></i> Simpan Pembayaran
                            </button>
                        </form>
                    </x-panel>
                </div>
            @endif

            <div class="row g-3">
                <div class="col-md-6">
                    <x-panel title="Serah Terima">
                        @if ($transaction->handoverInspection)
                            <div class="kv"><span class="kv-label">Waktu</span><span class="kv-value">{{ tanggal_waktu($transaction->handover_at) }}</span></div>
                            <div class="kv"><span class="kv-label">Petugas</span><span class="kv-value">{{ $transaction->handoverInspection->inspector?->name }}</span></div>
                            <div class="kv"><span class="kv-label">Odometer awal</span><span class="kv-value">{{ number_format((int) $transaction->handoverInspection->odometer) }} km</span></div>
                            <div class="kv"><span class="kv-label">Bahan bakar</span><span class="kv-value">{{ $transaction->handoverInspection->fuel_level?->label() }}</span></div>
                            <div class="kv"><span class="kv-label">Eksterior</span><span class="kv-value"><x-status-badge kind="condition" :value="$transaction->handoverInspection->exterior_condition" /></span></div>
                            <div class="kv"><span class="kv-label">Interior</span><span class="kv-value"><x-status-badge kind="condition" :value="$transaction->handoverInspection->interior_condition" /></span></div>
                            <div class="kv"><span class="kv-label">Ban</span><span class="kv-value">{{ $transaction->handoverInspection->tire_condition?->label() ?? '-' }}</span></div>
                            @if ($transaction->handoverInspection->existing_damage)
                                <div class="form-hint mt-2"><strong>Kerusakan lama:</strong> {{ $transaction->handoverInspection->existing_damage }}</div>
                            @endif
                            @if ($transaction->handoverInspection->photos->isNotEmpty())
                                <div class="photo-grid mt-2">
                                    @foreach ($transaction->handoverInspection->photos as $photo)
                                        <a href="{{ route('media.inspection-photo', $photo) }}" target="_blank">
                                            <img src="{{ route('media.inspection-photo', $photo) }}" alt="foto serah terima">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('inspections.show', $transaction->handoverInspection) }}" class="btn btn-sm btn-outline-primary mt-2">
                                Lihat Pemeriksaan
                            </a>
                        @else
                            <x-empty-state icon="fa-clipboard-check" title="Belum diperiksa"
                                           text="Kondisi awal kendaraan belum diperiksa.">
                                @if ($canHandover)
                                    <x-slot name="action">
                                        <a href="{{ route('handover.create', $transaction) }}" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-key me-1"></i> Proses Serah Terima
                                        </a>
                                    </x-slot>
                                @endif
                            </x-empty-state>
                        @endif
                    </x-panel>
                </div>

                <div class="col-md-6">
                    <x-panel title="Pengembalian">
                        @if ($transaction->returnInspection)
                            <div class="kv"><span class="kv-label">Waktu</span><span class="kv-value">{{ tanggal_waktu($transaction->actual_return_at) }}</span></div>
                            <div class="kv"><span class="kv-label">Petugas</span><span class="kv-value">{{ $transaction->returnInspection->inspector?->name }}</span></div>
                            <div class="kv"><span class="kv-label">Odometer akhir</span><span class="kv-value">{{ number_format((int) $transaction->returnInspection->odometer) }} km</span></div>
                            <div class="kv"><span class="kv-label">Bahan bakar</span><span class="kv-value">{{ $transaction->returnInspection->fuel_level?->label() }}</span></div>
                            <div class="kv"><span class="kv-label">Eksterior</span><span class="kv-value"><x-status-badge kind="condition" :value="$transaction->returnInspection->exterior_condition" /></span></div>
                            <div class="kv"><span class="kv-label">Kondisi setelah kembali</span><span class="kv-value">{{ $transaction->returnInspection->vehicle_status_after ? \App\Enums\VehicleStatus::tryFrom($transaction->returnInspection->vehicle_status_after)?->label() : '-' }}</span></div>
                            @if ($transaction->returnInspection->new_damage)
                                <div class="form-hint mt-2 text-danger"><strong>Kerusakan baru:</strong> {{ $transaction->returnInspection->new_damage }}</div>
                            @endif
                            @if ($transaction->late_minutes > 0)
                                <div class="form-hint mt-2 text-danger">
                                    <strong>Keterlambatan:</strong> {{ $transaction->late_minutes }} menit ·
                                    Denda {{ rupiah($transaction->late_fee) }}
                                </div>
                            @endif
                            @if ($transaction->returnInspection->photos->isNotEmpty())
                                <div class="photo-grid mt-2">
                                    @foreach ($transaction->returnInspection->photos as $photo)
                                        <a href="{{ route('media.inspection-photo', $photo) }}" target="_blank">
                                            <img src="{{ route('media.inspection-photo', $photo) }}" alt="foto pengembalian">
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('inspections.show', $transaction->returnInspection) }}" class="btn btn-sm btn-outline-primary mt-2">
                                Lihat Pemeriksaan
                            </a>
                        @else
                            <x-empty-state icon="fa-rotate-left" title="Belum dikembalikan"
                                           text="Pemeriksaan kondisi akhir akan tercatat saat kendaraan dikembalikan.">
                                @if ($canReturn)
                                    <x-slot name="action">
                                        <a href="{{ route('return.create', $transaction) }}" class="btn btn-primary btn-sm">
                                            <i class="fa-solid fa-rotate-left me-1"></i> Proses Pengembalian
                                        </a>
                                    </x-slot>
                                @endif
                            </x-empty-state>
                        @endif
                    </x-panel>
                </div>
            </div>

            <x-panel title="Timeline Aktivitas" :flush="true">
                @if ($transaction->logs->isEmpty())
                    <x-empty-state icon="fa-clock-rotate-left" title="Belum ada aktivitas" text="Riwayat perubahan transaksi akan tampil di sini." />
                @else
                    <div class="panel-body">
                        <ul class="timeline">
                            @foreach ($transaction->logs as $log)
                                <li>
                                    <div class="timeline-time">{{ tanggal_waktu($log->created_at) }} · {{ $log->user?->name ?? 'Sistem' }}</div>
                                    <div class="timeline-text">{{ $log->description }}</div>
                                    @if ($log->from_status && $log->to_status && $log->from_status !== $log->to_status)
                                        <div class="mt-1">
                                            <x-status-badge kind="transaction" :value="$log->from_status" />
                                            <i class="fa-solid fa-arrow-right mx-1 text-muted-2" style="font-size:.7rem"></i>
                                            <x-status-badge kind="transaction" :value="$log->to_status" />
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </x-panel>

            @if ($canCancel)
                <x-panel title="Pembatalan">
                    <form method="POST" action="{{ route('transactions.cancel', $transaction) }}"
                          data-confirm="Batalkan transaksi {{ $transaction->transaction_number }}? Kendaraan akan dilepas dari booking ini."
                          data-confirm-title="Batalkan transaksi?"
                          data-confirm-icon="error"
                          data-confirm-button="Ya, batalkan">
                        @csrf
                        <x-input name="reason" label="Alasan Pembatalan" value="{{ old('reason') }}"
                                 placeholder="Opsional — misalnya pelanggan membatalkan via WhatsApp" />
                        <button type="submit" class="btn btn-outline-danger">
                            <i class="fa-solid fa-ban me-1"></i> Batalkan Transaksi
                        </button>
                    </form>
                </x-panel>
            @endif
        </div>
    </div>
@endsection
