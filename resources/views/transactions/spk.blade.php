@extends('layouts.print')

@section('title', 'Surat Perjanjian Sewa — '.$transaction->transaction_number)

@section('content')
    <div class="d-flex justify-content-between align-items-start mb-4 no-print">
        <a href="{{ route('transactions.show', $transaction) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Transaksi
        </a>
        <div class="d-flex gap-2">
            <a href="{{ route('transactions.spk.pdf', $transaction) }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-file-pdf me-1"></i> Unduh PDF
            </a>
            <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                <i class="fa-solid fa-print me-1"></i> Cetak Surat Perjanjian (SPK)
            </button>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body p-4">
            <!-- Kop Surat Resmi -->
            <div class="border-bottom pb-3 mb-4 text-center">
                <div class="fw-bold text-uppercase" style="font-size:1.35rem;letter-spacing:.05em">
                    {{ $values['company_name'] }}
                </div>
                <div style="font-size:.88rem">{{ $values['company_address'] }}</div>
                <div style="font-size:.85rem" class="text-muted-2">
                    Telepon: {{ $values['company_phone'] }} · Email: kontak@jayatrans.id · Jember, Jawa Timur
                </div>
                <div style="border-bottom: 2px solid #000; margin-top: 8px; margin-bottom: 2px;"></div>
                <div style="border-bottom: 1px solid #000;"></div>
            </div>

            <!-- Judul Dokumen -->
            <div class="text-center mb-4">
                <div class="fw-bold text-uppercase" style="font-size:1.15rem;text-decoration:underline">
                    SURAT PERJANJIAN SEWA KENDARAAN (SPK)
                </div>
                <div style="font-size:.85rem;letter-spacing:.05em" class="text-muted-2">
                    Nomor: SPK/{{ date('Y') }}/{{ date('m') }}/{{ $transaction->transaction_number }}
                </div>
            </div>

            <p style="font-size:.88rem;line-height:1.6" class="mb-3">
                Pada hari ini, <strong>{{ tanggal_waktu($transaction->start_at) }}</strong>, bertempat di kantor <strong>{{ $values['company_name'] }}</strong>, telah dibuat dan disepakati perjanjian sewa kendaraan antara pihak-pihak di bawah ini:
            </p>

            <!-- Pihak Pertama -->
            <table class="table table-sm table-borderless mb-2" style="font-size:.86rem">
                <tbody>
                    <tr>
                        <td style="width:20px;vertical-align:top"><strong>1.</strong></td>
                        <td style="width:140px"><strong>Nama Perusahaan</strong></td>
                        <td style="width:10px">:</td>
                        <td><strong>{{ $values['company_name'] }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Penanggung Jawab</td>
                        <td>:</td>
                        <td>{{ $transaction->creator?->name ?? 'Petugas Operasional' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Alamat Garasi</td>
                        <td>:</td>
                        <td>{{ $values['company_address'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Telepon Kantor</td>
                        <td>:</td>
                        <td>{{ $values['company_phone'] }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-muted-2">
                            <em>Selanjutnya dalam perjanjian ini disebut sebagai <strong>PIHAK PERTAMA (Pemilik/Penyedia Rental)</strong>.</em>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Pihak Kedua -->
            <table class="table table-sm table-borderless mb-3" style="font-size:.86rem">
                <tbody>
                    <tr>
                        <td style="width:20px;vertical-align:top"><strong>2.</strong></td>
                        <td style="width:140px"><strong>Nama Penyewa</strong></td>
                        <td style="width:10px">:</td>
                        <td><strong>{{ $transaction->customer?->name }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>No. KTP / NIK</td>
                        <td>:</td>
                        <td>{{ $transaction->customer?->id_number }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Nomor SIM A</td>
                        <td>:</td>
                        <td>{{ $transaction->customer?->sim_number ?: 'Terlampir saat serah terima' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Nomor Telepon / WA</td>
                        <td>:</td>
                        <td>{{ $transaction->customer?->phone }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Alamat Tempat Tinggal</td>
                        <td>:</td>
                        <td>{{ $transaction->customer?->address ?: '-' }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>Verifikasi Dokumen</td>
                        <td>:</td>
                        <td>
                            @if ($transaction->customer?->verification_status === 'verified')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">Terverifikasi Resmi</span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Menunggu Verifikasi</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td colspan="3" class="text-muted-2">
                            <em>Selanjutnya dalam perjanjian ini disebut sebagai <strong>PIHAK KEDUA (Penyewa)</strong>.</em>
                        </td>
                    </tr>
                </tbody>
            </table>

            <p style="font-size:.88rem;line-height:1.6" class="mb-3">
                Kedua belah pihak telah sepakat mengadakan ikatan perjanjian sewa menyewa dengan syarat dan ketentuan yang diatur sebagai berikut:
            </p>

            <!-- Objek Sewa -->
            <div class="fw-bold mb-1" style="font-size:.9rem">PASAL 1 — OBJEK KENDARAAN & JANGKA WAKTU</div>
            <table class="table table-sm table-bordered mb-3" style="font-size:.84rem">
                <thead class="table-light">
                    <tr>
                        <th>Merk & Model Unit</th>
                        <th>Nomor Polisi</th>
                        <th>Tahun / Warna</th>
                        <th>Jenis Layanan</th>
                        <th>Supir Bertugas</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="fw-semibold">{{ $transaction->vehicle?->brand }} {{ $transaction->vehicle?->model }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $transaction->vehicle?->license_plate }}</span></td>
                        <td>{{ $transaction->vehicle?->year }} / {{ $transaction->vehicle?->color }}</td>
                        <td>
                            @if ($transaction->with_driver)
                                <strong class="text-primary">Dengan Supir (All-in Driver)</strong>
                            @else
                                <strong class="text-dark">Lepas Kunci (Self-Drive)</strong>
                            @endif
                        </td>
                        <td>
                            {{ $transaction->driver ? $transaction->driver->name.' ('.$transaction->driver->phone.')' : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong>Mulai Sewa:</strong> {{ tanggal_waktu($transaction->start_at) }}</td>
                        <td colspan="2"><strong>Selesai Sewa:</strong> {{ tanggal_waktu($transaction->end_at) }}</td>
                        <td><strong>Total Durasi:</strong> {{ $transaction->rental_days }} Hari</td>
                    </tr>
                </tbody>
            </table>

            <!-- Rincian Biaya & Jaminan -->
            <div class="fw-bold mb-1" style="font-size:.9rem">PASAL 2 — BIAYA SEWA & UANG JAMINAN (SECURITY DEPOSIT)</div>
            <div class="row g-3 mb-3">
                <div class="col-6">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.84rem">
                        <tbody>
                            <tr>
                                <td>Tarif Sewa Mobil ({{ $transaction->rental_days }} hari × {{ rupiah($transaction->daily_rate) }})</td>
                                <td class="text-end">{{ rupiah($transaction->daily_rate * $transaction->rental_days) }}</td>
                            </tr>
                            @if ($transaction->with_driver)
                                <tr>
                                    <td>Jasa Supir ({{ $transaction->rental_days }} hari × {{ rupiah($transaction->driver_rate) }})</td>
                                    <td class="text-end">{{ rupiah($transaction->driver_fee) }}</td>
                                </tr>
                            @endif
                            @if ($transaction->discount > 0)
                                <tr>
                                    <td>Potongan Diskon Promo</td>
                                    <td class="text-end text-danger">- {{ rupiah($transaction->discount) }}</td>
                                </tr>
                            @endif
                            <tr class="fw-bold table-light">
                                <td>Total Biaya Sewa</td>
                                <td class="text-end">{{ rupiah($transaction->total) }}</td>
                            </tr>
                            <tr>
                                <td>Uang Muka (DP) Terbayar</td>
                                <td class="text-end text-success">{{ rupiah($transaction->paidAmount()) }}</td>
                            </tr>
                            <tr class="fw-semibold">
                                <td>Sisa Tagihan Pelunasan</td>
                                <td class="text-end">{{ rupiah($transaction->balance()) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table table-sm table-bordered mb-0" style="font-size:.84rem">
                        <thead class="table-light">
                            <tr><th colspan="2">Jaminan / Deposit Keamanan (Wajib)</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width:130px">Jenis Jaminan</td>
                                <td><strong>{{ $transaction->deposit_type ? ucfirst($transaction->deposit_type) : 'Deposit Tunai / Identitas' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Nominal Deposit</td>
                                <td class="fw-bold text-primary">{{ $transaction->deposit_amount > 0 ? rupiah($transaction->deposit_amount) : 'Rp 0' }}</td>
                            </tr>
                            <tr>
                                <td>Catatan Titipan Fisik</td>
                                <td>{{ $transaction->deposit_notes ?: 'KTP Asli Penyewa ditahan garasi selama masa sewa' }}</td>
                            </tr>
                            <tr>
                                <td>Status Deposit</td>
                                <td>
                                    @php
                                        $dLabels = [
                                            'pending' => 'Menunggu Setor',
                                            'held' => 'Ditahan Garasi',
                                            'refunded' => 'Sudah Dikembalikan',
                                            'forfeited' => 'Hangus / Diklaim Biaya',
                                            'none' => 'Tanpa Deposit',
                                        ];
                                    @endphp
                                    <span class="badge bg-light text-dark border">{{ $dLabels[$transaction->deposit_status] ?? $transaction->deposit_status }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ketentuan Hukum & Operasional -->
            <div class="fw-bold mb-1" style="font-size:.9rem">PASAL 3 — HAK, KEWAJIBAN & LARANGAN</div>
            <ol style="font-size:.83rem;line-height:1.55;padding-left:1.2rem" class="mb-4">
                <li><strong>Serah Terima & Pemeriksaan:</strong> Kendaraan diserahterimakan dalam kondisi bersih dan laik jalan lengkap dengan STNK asli. Pihak Kedua wajib menandatangani Berita Acara Pemeriksaan Kendaraan awal (*Inspection Sheet*).</li>
                <li><strong>Bahan Bakar & Kebersihan:</strong> Pihak Kedua wajib mengembalikan kendaraan dengan volume bahan bakar sekurang-kurangnya sama dengan saat serah terima awal. Apabila kembali dalam kondisi kotor ekstrem atau bau asap rokok tajam, dikenakan biaya salon interior Rp 100.000.</li>
                <li><strong>Batas Keterlambatan:</strong> Toleransi pengembalian adalah 60 (enam puluh) menit dari jadwal. Keterlambatan melebihi batas toleransi dikenakan biaya keterlambatan sebesar <strong>Rp 50.000 / jam</strong>.</li>
                <li><strong>Larangan Keras Pemindahtanganan & Tindak Pidana:</strong> Pihak Kedua <strong>DILARANG KERAS</strong> menggadaikan, memindahtangankan, menyewakan kembali unit kepada pihak ketiga, mengganti suku cadang, atau menggunakan kendaraan untuk kejahatan, narkoba, atau tindak pidana lainnya. Pelanggaran dikenakan sanksi pidana <strong>Pasal 372 KUHP (Penggelapan)</strong> dengan ancaman 4 tahun penjara dan dilaporkan segera ke Kepolisian Republik Indonesia.</li>
                <li><strong>Tilang Elektronik (ETLE):</strong> Segala bentuk pelanggaran lalu lintas (tilang manual maupun ETLE kamera) selama masa sewa berlangsung sepenuhnya menjadi beban tanggung jawab Pihak Kedua. Pihak Pertama berhak memotong deposit jika muncul denda tilang.</li>
                <li><strong>Kerusakan & Kecelakaan:</strong> Kerusakan yang terjadi akibat kelalaian Pihak Kedua (tabrakan, lecet, ban robek, kehilangan aksesoris) menjadi tanggung jawab penuh Pihak Kedua untuk mengganti kerugian sesuai estimasi bengkel rekanan resmi.</li>
            </ol>

            <p style="font-size:.85rem;line-height:1.5" class="mb-4">
                Demikian Surat Perjanjian Sewa Kendaraan (SPK) ini dibuat rangkap dua bermaterai cukup dan mempunyai kekuatan hukum yang sama bagi kedua belah pihak.
            </p>

            <!-- Tanda Tangan -->
            <div class="row text-center mt-3" style="font-size:.88rem">
                <div class="col-6">
                    <p class="mb-1">PIHAK PERTAMA,</p>
                    <p class="text-muted-2" style="font-size:.82rem">{{ $values['company_name'] }}</p>
                    <div style="height:70px"></div>
                    <p class="fw-bold mb-0 text-decoration-underline">({{ $transaction->creator?->name ?? 'Petugas Operasional' }})</p>
                    <span class="text-muted-2" style="font-size:.8rem">Staf Operasional Garasi</span>
                </div>
                <div class="col-6">
                    <p class="mb-1">PIHAK KEDUA,</p>
                    <p class="text-muted-2" style="font-size:.82rem">Penyewa Kendaraan</p>
                    <div style="height:70px" class="d-flex align-items-center justify-content-center">
                        <span class="border border-secondary px-2 py-1 text-muted" style="font-size:.7rem;border-style:dashed!important">Materai Rp 10.000</span>
                    </div>
                    <p class="fw-bold mb-0 text-decoration-underline">({{ $transaction->customer?->name }})</p>
                    <span class="text-muted-2" style="font-size:.8rem">NIK: {{ $transaction->customer?->id_number }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
