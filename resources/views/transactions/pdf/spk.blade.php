<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Surat Perjanjian Sewa (SPK) - {{ $transaction->transaction_number }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.45;
            margin: 0;
            padding: 15px 25px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        .kop-title {
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .kop-sub {
            font-size: 10px;
            color: #4b5563;
        }
        .doc-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-top: 10px;
        }
        .doc-no {
            text-align: center;
            font-size: 10px;
            color: #4b5563;
            margin-bottom: 12px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 4px;
            text-transform: uppercase;
            font-size: 11px;
            border-bottom: 1px solid #cbd5e1;
            padding-bottom: 2px;
        }
        .table-party td {
            padding: 2px 4px;
            vertical-align: top;
            font-size: 10.5px;
        }
        .data-table {
            margin: 6px 0;
        }
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 4px 6px;
            font-size: 10.5px;
        }
        .data-table th {
            background-color: #f1f5f9;
            text-align: left;
        }
        ol {
            margin: 4px 0 8px 18px;
            padding: 0;
            font-size: 10px;
            line-height: 1.4;
        }
        ol li {
            margin-bottom: 3px;
        }
        .sign-table {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .sign-box {
            height: 55px;
        }
    </style>
</head>
<body>
    <div class="kop">
        <div class="kop-title">{{ $values['company_name'] }}</div>
        <div class="kop-sub">{{ $values['company_address'] }} · Telp: {{ $values['company_phone'] }}</div>
        <div class="kop-sub">Layanan Sewa Mobil Lepas Kunci & Dengan Supir Profesional</div>
    </div>

    <div class="doc-title">SURAT PERJANJIAN SEWA KENDARAAN (SPK)</div>
    <div class="doc-no">Nomor: SPK/{{ date('Y') }}/{{ date('m') }}/{{ $transaction->transaction_number }}</div>

    <p style="margin: 6px 0;">
        Pada hari ini, <strong>{{ tanggal_waktu($transaction->start_at) }}</strong>, telah disepakati perjanjian sewa kendaraan bermotor antara pihak-pihak di bawah ini:
    </p>

    <div class="section-title">I. Pihak Pertama (Penyedia Rental)</div>
    <table class="table-party">
        <tr>
            <td style="width: 25%;">Nama Usaha / Rental</td>
            <td style="width: 2%;">:</td>
            <td><strong>{{ $values['company_name'] }}</strong></td>
        </tr>
        <tr>
            <td>Penanggung Jawab</td>
            <td>:</td>
            <td>{{ $transaction->creator?->name ?? 'Petugas Operasional' }}</td>
        </tr>
        <tr>
            <td>Alamat Garasi</td>
            <td>:</td>
            <td>{{ $values['company_address'] }}</td>
        </tr>
        <tr>
            <td>Telepon</td>
            <td>:</td>
            <td>{{ $values['company_phone'] }}</td>
        </tr>
    </table>

    <div class="section-title">II. Pihak Kedua (Penyewa)</div>
    <table class="table-party">
        <tr>
            <td style="width: 25%;">Nama Lengkap</td>
            <td style="width: 2%;">:</td>
            <td><strong>{{ $transaction->customer?->name }}</strong></td>
        </tr>
        <tr>
            <td>No. KTP / NIK</td>
            <td>:</td>
            <td>{{ $transaction->customer?->id_number }}</td>
        </tr>
        <tr>
            <td>No. SIM A</td>
            <td>:</td>
            <td>{{ $transaction->customer?->sim_number ?: 'Terlampir' }}</td>
        </tr>
        <tr>
            <td>No. Telepon / WA</td>
            <td>:</td>
            <td>{{ $transaction->customer?->phone }}</td>
        </tr>
        <tr>
            <td>Alamat Tinggal</td>
            <td>:</td>
            <td>{{ $transaction->customer?->address ?: '-' }}</td>
        </tr>
    </table>

    <div class="section-title">III. Objek & Masa Sewa</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Kendaraan</th>
                <th>No. Polisi</th>
                <th>Periode Sewa</th>
                <th>Durasi</th>
                <th>Jenis Layanan</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }} ({{ $transaction->vehicle?->year }})</td>
                <td><strong>{{ $transaction->vehicle?->license_plate }}</strong></td>
                <td>{{ tanggal_waktu($transaction->start_at) }} s/d {{ tanggal_waktu($transaction->end_at) }}</td>
                <td>{{ $transaction->rental_days }} Hari</td>
                <td>{{ $transaction->with_driver ? 'Dengan Supir' : 'Lepas Kunci' }}</td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">IV. Rincian Biaya & Jaminan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Biaya Sewa Unit</th>
                <th>Biaya Supir</th>
                <th>Diskon</th>
                <th>Total Biaya</th>
                <th>Jaminan / Deposit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ rupiah($transaction->subtotal - $transaction->driver_fee) }}</td>
                <td>{{ rupiah($transaction->driver_fee) }}</td>
                <td>{{ rupiah($transaction->discount) }}</td>
                <td><strong>{{ rupiah($transaction->total) }}</strong></td>
                <td>
                    @if ($transaction->deposit_amount > 0)
                        {{ rupiah($transaction->deposit_amount) }} ({{ $transaction->deposit_type ?: 'Uang Tunai' }})
                    @else
                        {{ $transaction->deposit_notes ?: 'KTP Asli / Motor' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <div class="section-title">V. Syarat & Ketentuan Sewa</div>
    <ol>
        <li>Pihak Kedua wajib mematuhi seluruh peraturan lalu lintas yang berlaku di wilayah Republik Indonesia.</li>
        <li>Kendaraan tidak diperkenankan untuk disewakan kembali (over-rental), dipindahtangankan, digadaikan, atau digunakan untuk tindak kriminal/kejahatan.</li>
        <li>Keterlambatan pengembalian dikenakan denda sesuai tarif yang berlaku (Grace period 30 menit).</li>
        <li>Segala kerusakan, baret, penyok, maupun kehilangan bagian kendaraan akibat kelalaian penyewa sepenuhnya menjadi tanggung jawab Pihak Kedua.</li>
        <li>Bahan bakar saat pengembalian harus sesuai dengan kondisi awal serah terima.</li>
    </ol>

    <table class="sign-table">
        <tr>
            <td style="width: 50%; text-align: center;">
                <div>Pihak Kedua (Penyewa),</div>
                <div class="sign-box"></div>
                <div><strong>({{ $transaction->customer?->name }})</strong></div>
            </td>
            <td style="width: 50%; text-align: center;">
                <div>Pihak Pertama (Rental),</div>
                <div class="sign-box"></div>
                <div><strong>({{ $transaction->creator?->name ?? 'Pengelola Rental' }})</strong></div>
            </td>
        </tr>
    </table>
</body>
</html>
