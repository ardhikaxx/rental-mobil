<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #1f2937;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
        }
        .company-title {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }
        .company-subtitle {
            font-size: 11px;
            color: #6b7280;
        }
        .doc-title {
            font-size: 18px;
            font-weight: bold;
            color: #2563eb;
            text-align: right;
            letter-spacing: 1px;
        }
        .doc-meta {
            font-size: 11px;
            color: #6b7280;
            text-align: right;
        }
        .divider {
            border-bottom: 2px solid #e5e7eb;
            margin: 15px 0;
        }
        .section-header {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            color: #4b5563;
            background: #f3f4f6;
            padding: 6px 10px;
            margin: 14px 0 8px 0;
            border-left: 3px solid #2563eb;
        }
        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }
        .data-table {
            margin-top: 5px;
            margin-bottom: 15px;
        }
        .data-table th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 7px 10px;
            border-bottom: 1px solid #d1d5db;
            font-size: 11px;
        }
        .data-table td {
            padding: 7px 10px;
            border-bottom: 1px solid #f3f4f6;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .total-row {
            background-color: #f8fafc;
            font-weight: bold;
            border-top: 1px solid #94a3b8;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 4px;
        }
        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-warning { background: #fef3c7; color: #b45309; }
        .badge-info { background: #e0f2fe; color: #0369a1; }
        .footer-note {
            margin-top: 25px;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px dashed #d1d5db;
            padding-top: 10px;
        }
        .signature-table {
            margin-top: 30px;
        }
        .signature-box {
            height: 60px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td style="width: 60%;">
                <div class="company-title">{{ $values['company_name'] }}</div>
                <div class="company-subtitle">{{ $values['company_address'] }}</div>
                <div class="company-subtitle">Telepon: {{ $values['company_phone'] }}</div>
            </td>
            <td style="width: 40%;">
                <div class="doc-title">INVOICE RENTAL</div>
                <div class="doc-meta">No: <strong>{{ $transaction->transaction_number }}</strong></div>
                <div class="doc-meta">Tanggal: {{ tanggal($transaction->created_at) }}</div>
                <div class="doc-meta">Status: <strong>{{ $transaction->status->label() }}</strong></div>
            </td>
        </tr>
    </table>

    <div class="divider"></div>

    <table class="info-table">
        <tr>
            <td style="width: 50%;">
                <div class="section-header">Informasi Penyewa</div>
                <table>
                    <tr>
                        <td style="width: 30%;" class="font-bold">Nama:</td>
                        <td>{{ $transaction->customer?->name }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">No. KTP/NIK:</td>
                        <td>{{ $transaction->customer?->id_number }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Telepon:</td>
                        <td>{{ $transaction->customer?->phone }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Alamat:</td>
                        <td>{{ $transaction->customer?->address ?: '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%;">
                <div class="section-header">Armada & Layanan</div>
                <table>
                    <tr>
                        <td style="width: 35%;" class="font-bold">Kendaraan:</td>
                        <td>{{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }} ({{ $transaction->vehicle?->code }})</td>
                    </tr>
                    <tr>
                        <td class="font-bold">No. Polisi:</td>
                        <td>{{ $transaction->vehicle?->license_plate }}</td>
                    </tr>
                    <tr>
                        <td class="font-bold">Layanan:</td>
                        <td>{{ $transaction->with_driver ? 'Dengan Supir (Driver)' : 'Lepas Kunci (Tanpa Supir)' }}</td>
                    </tr>
                    @if ($transaction->with_driver && $transaction->driver)
                        <tr>
                            <td class="font-bold">Nama Supir:</td>
                            <td>{{ $transaction->driver->name }} ({{ $transaction->driver->phone }})</td>
                        </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>

    <div class="section-header">Jadwal Sewa</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Waktu Mulai</th>
                <th>Rencana Pengembalian</th>
                <th>Realisasi Kembali</th>
                <th class="text-center">Durasi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ tanggal_waktu($transaction->start_at) }}</td>
                <td>{{ tanggal_waktu($transaction->end_at) }}</td>
                <td>{{ $transaction->actual_return_at ? tanggal_waktu($transaction->actual_return_at) : 'Sedang Berjalan' }}</td>
                <td class="text-center">{{ $transaction->rental_days }} Hari</td>
            </tr>
        </tbody>
    </table>

    <div class="section-header">Rincian Biaya</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Deskripsi Layanan</th>
                <th class="text-center">Hari / Satuan</th>
                <th class="text-right">Tarif</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sewa Kendaraan {{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }}</td>
                <td class="text-center">{{ $transaction->rental_days }} Hari</td>
                <td class="text-right">{{ rupiah($transaction->daily_rate) }}</td>
                <td class="text-right">{{ rupiah($transaction->daily_rate * $transaction->rental_days) }}</td>
            </tr>
            @if ($transaction->with_driver)
                <tr>
                    <td>Jasa Supir / Driver</td>
                    <td class="text-center">{{ $transaction->rental_days }} Hari</td>
                    <td class="text-right">{{ rupiah($transaction->driver_rate) }}</td>
                    <td class="text-right">{{ rupiah($transaction->driver_fee) }}</td>
                </tr>
            @endif
            @if ($transaction->discount > 0)
                <tr>
                    <td colspan="3" class="text-right">Diskon Khusus:</td>
                    <td class="text-right" style="color: #dc2626;">- {{ rupiah($transaction->discount) }}</td>
                </tr>
            @endif
            @if ($transaction->late_fee > 0)
                <tr>
                    <td colspan="3" class="text-right">Denda Keterlambatan ({{ $transaction->late_minutes }} menit):</td>
                    <td class="text-right" style="color: #dc2626;">+ {{ rupiah($transaction->late_fee) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td colspan="3" class="text-right font-bold">TOTAL TAGIHAN:</td>
                <td class="text-right font-bold">{{ rupiah($transaction->totalPayable()) }}</td>
            </tr>
            <tr>
                <td colspan="3" class="text-right font-bold">Total Pembayaran Masuk:</td>
                <td class="text-right font-bold" style="color: #16a34a;">{{ rupiah($transaction->paidAmount()) }}</td>
            </tr>
            <tr style="background:#f1f5f9;">
                <td colspan="3" class="text-right font-bold">SISA TAGIHAN / BALANCE:</td>
                <td class="text-right font-bold" style="font-size: 13px; color: {{ $transaction->balance() > 0 ? '#b91c1c' : '#15803d' }};">
                    {{ rupiah($transaction->balance()) }}
                </td>
            </tr>
        </tbody>
    </table>

    @if ($transaction->payments->isNotEmpty())
        <div class="section-header">Riwayat Pembayaran</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>No. Bukti</th>
                    <th>Tanggal</th>
                    <th>Jenis</th>
                    <th>Metode</th>
                    <th class="text-right">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->payments as $payment)
                    <tr>
                        <td>{{ $payment->payment_number }}</td>
                        <td>{{ tanggal($payment->paid_at) }}</td>
                        <td>{{ $payment->type->label() }}</td>
                        <td>{{ $payment->method->label() }}</td>
                        <td class="text-right">{{ rupiah($payment->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <table class="signature-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div>Penyewa,</div>
                <div class="signature-box"></div>
                <div class="font-bold">({{ $transaction->customer?->name }})</div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div>Petugas Kasir / Rental,</div>
                <div class="signature-box"></div>
                <div class="font-bold">({{ $transaction->creator?->name ?? 'Admin Operasional' }})</div>
            </td>
        </tr>
    </table>

    <div class="footer-note">
        Invoice ini merupakan bukti pembayaran resmi yang sah dari {{ $values['company_name'] }}. Dicetak secara elektronik pada {{ tanggal_waktu(now()) }}.
    </div>
</body>
</html>
