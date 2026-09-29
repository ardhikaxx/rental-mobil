{{-- ================= LAMPIRAN, MATRIKS ROLE & FAQ ================= --}}

<section id="matriks-role" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-table-cells text-primary me-2"></i>Matriks Hak Akses &amp; Peran Sistem (RBAC)</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Berikut adalah tabel perbandingan hak akses dan wewenang untuk masing-masing peran pengguna di dalam sistem rental mobil:
            </p>

            <div class="table-responsive">
                <table class="table table-bordered guide-table text-center mb-3">
                    <thead>
                        <tr>
                            <th class="text-start" style="width: 38%;">Fitur &amp; Menu Modul</th>
                            <th style="width: 20%;"><span class="badge guide-badge-staff">Staff Garasi</span></th>
                            <th style="width: 21%;"><span class="badge guide-badge-admin">Admin Operasional</span></th>
                            <th style="width: 21%;"><span class="badge guide-badge-sa">Super Admin</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-start guide-field-name">Dashboard Ringkasan Operasional</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Daftar Transaksi (Lihat &amp; Detail)</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Buat Transaksi Baru &amp; Approval</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Pelaksanaan Serah Terima Unit (Handover)</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Pelaksanaan Pengembalian Unit (Return)</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Checklist Pemeriksaan Fisik Mobil</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Status Armada (Cuci / Siap Jalan)</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Pendaftaran &amp; Verifikasi Pelanggan (KTP/SIM)</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Manajemen Supir &amp; Penugasan Driver</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Pencatatan Pembayaran, DP &amp; Deposit</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Kalender Booking Visual</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Cetak Dokumen SPK &amp; Invoice Tagihan</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Catat &amp; Update Servis Perawatan Bengkel</td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Tambah / Hapus Master Armada Kendaraan</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Laporan Keuangan &amp; Ekspor Data Excel/CSV</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Manajemen Akun Pengguna &amp; Reset Password</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Audit Log Rekam Jejak Sistem</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                        <tr>
                            <td class="text-start guide-field-name">Pengaturan Tarif Denda &amp; Bisnis Rental</td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-xmark text-muted"></i></td>
                            <td><i class="fa-solid fa-check text-success"></i></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<section id="sop-denda" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-scale-balanced text-primary me-2"></i>SOP Denda Keterlambatan, BBM &amp; Kerusakan</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Standar Operasional Prosedur (SOP) ini menjadi acuan seragam bagi Staff dan Admin saat menyelesaikan transaksi di garasi.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">1. Ketentuan Denda Keterlambatan Pengembalian (Late Return)</h3>
            <ul class="mb-4 small">
                <li><strong>Batas Toleransi (Grace Period):</strong> Diberikan toleransi keterlambatan maksimal <strong>30 menit</strong> dari jam yang disepakati di SPK untuk mengantisipasi kemacetan lalu lintas normal.</li>
                <li><strong>Keterlambatan 1 s.d. 5 Jam:</strong> Dikenakan denda per jam sesuai pengaturan sistem (contoh: Rp 50.000,- per jam keterlambatan). Sistem otomatis menghitung jam keterlambatan x tarif per jam.</li>
                <li><strong>Keterlambatan Lebih dari 6 Jam:</strong> Dianggap menggunakan sewa tambahan 1 hari penuh, dan dikenakan tarif sewa harian mobil tersebut.</li>
            </ul>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">2. Ketentuan Selisih Bahan Bakar (BBM)</h3>
            <ul class="mb-4 small">
                <li>Prinsip sewa mobil adalah <strong>Level Sama Saat Keluar dan Masuk</strong> (misal: keluar Full Bar, maka kembali wajib Full Bar).</li>
                <li>Jika mobil kembali dengan level BBM lebih rendah, penyewa dikenakan biaya penggantian BBM per bar/liter ditambah biaya jasa pengisian garasi.</li>
            </ul>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">3. Prosedur Klaim Kerusakan &amp; Pemotongan Deposit</h3>
            <ol class="guide-step mb-0">
                <li>Bandingkan foto kondisi mobil saat serah terima dengan kondisi saat pengembalian.</li>
                <li>Jika ditemukan goresan, penyok, atau komponen patah yang baru terjadi selama masa sewa, dokumentasikan foto kerusakan secara jelas.</li>
                <li>Perkirakan estimasi biaya perbaikan bodi bengkel (body repair) atau penggantian sparepart.</li>
                <li>Biaya kerusakan tersebut langsung dipotongkan dari Uang Jaminan (Deposit) yang ditahan oleh Admin.</li>
                <li>Jika biaya kerusakan melebihi nilai deposit, penyewa wajib melunasi kekurangannya sebelum transaksi dapat ditutup (Completed).</li>
            </ol>
        </div>
    </div>
</section>

<section id="faq" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-circle-question text-primary me-2"></i>Tanya Jawab Singkat (FAQ &amp; Troubleshooting)</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <div class="accordion" id="accordionFaq">
                <div class="accordion-item mb-2 border rounded">
                    <h2 class="accordion-header" id="headingOne">
                        <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne">
                            1. Mengapa unit mobil tidak muncul di dropdown saat membuat transaksi baru?
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                        <div class="accordion-body small text-muted">
                            Penyebabnya adalah status mobil saat ini bukan <code>Tersedia (Available)</code>. 
                            Periksa di menu Kendaraan apakah mobil tersebut sedang dalam status <em>Sedang Disewa (Rented)</em>, <em>Sedang Dibersihkan (Cleaning)</em>, atau <em>Dalam Servis (Maintenance)</em>. 
                            Jika mobil sudah selesai dicuci di garasi, minta Staff untuk menekan tombol "Tandai Siap Jalan" agar statusnya kembali <code>Available</code>.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-2 border rounded">
                    <h2 class="accordion-header" id="headingTwo">
                        <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                            2. Bagaimana jika penyewa ingin memperpanjang (extend) masa sewa di tengah jalan?
                        </button>
                    </h2>
                    <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                        <div class="accordion-body small text-muted">
                            Admin harus memeriksa <strong>Kalender Booking</strong> terlebih dahulu untuk memastikan mobil tersebut belum dibooking oleh pelanggan lain pada tanggal perpanjangan. 
                            Jika jadwal kosong, Admin dapat membuka detail transaksi aktif tersebut, mengklik tombol <strong>Ubah Transaksi</strong>, memperpanjang tanggal rencana kembali, dan menambahkan catatan konfirmasi perpanjangan serta menagih biaya sewa tambahannya.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-2 border rounded">
                    <h2 class="accordion-header" id="headingThree">
                        <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree">
                            3. Pelanggan terlambat mengembalikan mobil dan sulit dihubungi, apa langkahnya?
                        </button>
                    </h2>
                    <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                        <div class="accordion-body small text-muted">
                            Segera buka detail data pelanggan di menu Pelanggan. Hubungi <strong>Nomor Kontak Darurat Kerabat</strong> yang didaftarkan saat registrasi awal. 
                            Cek posisi kendaraan jika mobil dipasangi perangkat GPS Tracker. Jika melewati 24 jam tanpa kabar, laporkan kejadian ke Super Admin untuk langkah hukum darurat.
                        </div>
                    </div>
                </div>

                <div class="accordion-item mb-2 border rounded">
                    <h2 class="accordion-header" id="headingFour">
                        <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour">
                            4. Bagaimana cara mencetak SPK atau Invoice agar pas di kertas A4 tanpa terpotong?
                        </button>
                    </h2>
                    <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                        <div class="accordion-body small text-muted">
                            Saat jendela dialog cetak browser (Ctrl + P) muncul, pastikan pengaturan berikut dipilih:<br>
                            - Ukuran Kertas: <strong>A4</strong><br>
                            - Tata Letak: <strong>Potret (Portrait)</strong><br>
                            - Margin: <strong>Default</strong> atau <strong>Minimum</strong><br>
                            - Opsi: Centang <strong>Grafis Latar Belakang (Background graphics)</strong> agar tabel dan badge warna tercetak sempurna.
                        </div>
                    </div>
                </div>

                <div class="accordion-item border rounded">
                    <h2 class="accordion-header" id="headingFive">
                        <button class="accordion-button collapsed fw-bold small" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive">
                            5. Apakah gambar yang diunggah aman dan tidak memberatkan server?
                        </button>
                    </h2>
                    <div id="collapseFive" class="accordion-collapse collapse" data-bs-parent="#accordionFaq">
                        <div class="accordion-body small text-muted">
                            Ya, seluruh upload foto (KTP, SIM A, Foto Kendaraan, Pemeriksaan Mobil, Bukti Transfer) otomatis dikonversi oleh sistem ke format modern <strong>WebP</strong> dengan kompresi cerdas GD tanpa mengurangi ketajaman teks atau detail visual.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
