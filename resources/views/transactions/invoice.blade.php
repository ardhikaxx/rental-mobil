@extends('layouts.print')

@section('title', 'Invoice '.$transaction->transaction_number)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4 no-print">
        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.invoice.pdf', $transaction) }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="d-flex justify-content-between mb-4">
                <div>
                    <div class="fw-bold" style="font-size:1.1rem">{{ $values['company_name'] }}</div>
                    <div class="text-muted-2" style="font-size:.85rem">{{ $values['company_address'] }}</div>
                    <div class="text-muted-2" style="font-size:.85rem">Telp: {{ $values['company_phone'] }}</div>
                </div>
                <div class="text-end">
                    <div class="fw-bold" style="letter-spacing:.08em">INVOICE RENTAL</div>
                    <div class="text-muted-2" style="font-size:.85rem">Dicetak {{ tanggal_waktu(now()) }}</div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <div class="section-title">Pelanggan</div>
                    <div class="fw-semibold">{{ $transaction->customer?->name }}</div>
                    <div style="font-size:.86rem">No. Identitas: {{ $transaction->customer?->id_number }}</div>
                    <div style="font-size:.86rem">Telepon: {{ $transaction->customer?->phone }}</div>
                </div>
                <div class="col-6">
                    <div class="section-title">Transaksi</div>
                    <div class="fw-semibold">{{ $transaction->transaction_number }}</div>
                    <div style="font-size:.86rem">Tanggal dibuat: {{ tanggal($transaction->created_at) }}</div>
                    <div style="font-size:.86rem">Sumber booking: {{ $transaction->booking_source->label() }}</div>
                    <div style="font-size:.86rem">Status: {{ $transaction->status->label() }}</div>
                </div>
            </div>

            <div class="section-title">Kendaraan & Periode</div>
            <div class="row mb-4">
                <div class="col-6">
                    <div class="fw-semibold">{{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }}</div>
                    <div style="font-size:.86rem">Kode unit: {{ $transaction->vehicle?->code }}</div>
                    <div style="font-size:.86rem">No. Polisi: {{ $transaction->vehicle?->license_plate }}</div>
                </div>
                <div class="col-6">
                    <div style="font-size:.86rem">Mulai: {{ tanggal_waktu($transaction->start_at) }}</div>
                    <div style="font-size:.86rem">Kembali: {{ tanggal_waktu($transaction->end_at) }}</div>
                    @if ($transaction->actual_return_at)
                        <div style="font-size:.86rem">Dikembalikan: {{ tanggal_waktu($transaction->actual_return_at) }}</div>
                    @endif
                </div>
            </div>

            <div class="section-title">Rincian Biaya</div>
            <table class="table table-sm mb-3">
                <tbody>
                    <tr>
                        <td>Sewa kendaraan ({{ $transaction->rental_days }} hari × {{ rupiah($transaction->daily_rate) }})</td>
                        <td class="text-end">{{ rupiah($transaction->subtotal) }}</td>
                    </tr>
                    <tr>
                        <td>Diskon</td>
                        <td class="text-end">- {{ rupiah($transaction->discount) }}</td>
                    </tr>
                    <tr>
                        <td>Denda keterlambatan</td>
                        <td class="text-end">{{ rupiah($transaction->late_fee) }}</td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Total Tagihan</td>
                        <td class="text-end">{{ rupiah($transaction->totalPayable()) }}</td>
                    </tr>
                </tbody>
            </table>

            <div class="section-title">Riwayat Pembayaran</div>
            @if ($transaction->payments->isEmpty())
                <p style="font-size:.87rem" class="text-muted-2">Belum ada pembayaran tercatat.</p>
            @else
                <table class="table table-sm mb-3">
                    <thead>
                        <tr>
                            <th>No. Pembayaran</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Metode</th>
                            <th class="text-end">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($transaction->payments as $payment)
                            <tr>
                                <td>{{ $payment->payment_number }}</td>
                                <td>{{ tanggal($payment->paid_at) }}</td>
                                <td>{{ $payment->type->label() }}</td>
                                <td>{{ $payment->method->label() }}</td>
                                <td class="text-end">{{ rupiah($payment->amount) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="fw-semibold">
                            <td colspan="4">Total Dibayar</td>
                            <td class="text-end">{{ rupiah($transaction->paidAmount()) }}</td>
                        </tr>
                        <tr class="fw-bold">
                            <td colspan="4">Sisa Tagihan</td>
                            <td class="text-end">{{ rupiah($transaction->balance()) }}</td>
                        </tr>
                    </tfoot>
                </table>
            @endif

            <div class="row mt-5">
                <div class="col-6">
                    <div style="font-size:.86rem">Petugas pembuat:</div>
                    <div class="fw-semibold">{{ $transaction->creator?->name ?? '-' }}</div>
                </div>
                <div class="col-6 text-end">
                    <div style="font-size:.86rem">Hormat kami,</div>
                    <div style="height:56px"></div>
                    <div class="fw-semibold">{{ $values['company_name'] }}</div>
                </div>
            </div>

            <div class="text-muted-2 mt-4" style="font-size:.78rem">
                Dokumen ini dihasilkan oleh sistem operasional {{ $values['company_name'] }} dan sah tanpa tanda tangan basah.
            </div>
        </div>
    </div>
@endsection
