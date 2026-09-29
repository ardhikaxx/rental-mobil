{{-- ================= PANDUAN UMUM (SEMUA ROLE) ================= --}}

<section id="umum" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-book text-primary me-2"></i>Tentang Panduan Sistem Rental Mobil</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Selamat datang di panduan operasional resmi <strong>Sistem Manajemen Rental Mobil Jaya Trans</strong>. 
                Dokumentasi ini dirancang untuk memudahkan seluruh personil dalam menjalankan tugas harian secara akurat, 
                efisien, dan terstandar sesuai hak akses peran masing-masing.
            </p>

            <h3 class="h6 fw-bold text-dark mt-4 mb-3">Tiga Peran Utama dalam Sistem</h3>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
                        </div>
                        <p class="small text-muted mb-0">
                            Fokus pada operasional garasi &amp; unit: checklist inspeksi fisik armada, serah terima unit (handover), 
                            pengembalian unit (return) &amp; cek odometer/BBM, perawatan berkala, serta status kebersihan mobil.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge guide-badge-admin">Admin Operasional</span>
                        </div>
                        <p class="small text-muted mb-0">
                            Fokus pada administrasi &amp; layanan pelanggan: pembuatan &amp; verifikasi transaksi, validasi KTP &amp; SIM A, 
                            penugasan supir, pencatatan uang muka (DP) &amp; deposit, kalender booking, serta cetak SPK &amp; Invoice.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 rounded border bg-light h-100">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge guide-badge-sa">Super Admin</span>
                        </div>
                        <p class="small text-muted mb-0">
                            Kendali penuh atas seluruh sistem: manajemen akun pengguna &amp; hak akses, penambahan master armada mobil, 
                            kebijakan denda &amp; pengaturan sistem, audit log rekam jejak, serta ekspor laporan finansial.
                        </p>
                    </div>
                </div>
            </div>

            <div class="alert guide-info mb-0 small">
                <i class="fa-solid fa-circle-info me-1"></i>
                <strong>Navigasi Cepat:</strong> Klik judul pada <strong>Daftar Isi</strong> di sisi kiri untuk berpindah bagian panduan secara seketika. 
                Gunakan tombol <strong>Cetak Panduan</strong> di pojok kanan atas untuk menyimpan atau mencetak dokumen panduan resmi dalam format kertas/PDF.
            </div>
        </div>
    </div>
</section>

<section id="login" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-right-to-bracket text-primary me-2"></i>Login, Logout &amp; Keamanan Akun</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-link me-1"></i>URL Akses: <code>{{ route('login') }}</code>
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Masuk (Login) ke Sistem</h3>
            <ol class="guide-step mb-4">
                <li>Buka peramban (browser) dan akses alamat web sistem rental mobil pada <code>/login</code>.</li>
                <li>Masukkan <strong>Email Akun</strong> dan <strong>Kata Sandi (Password)</strong> yang telah didaftarkan oleh Super Admin.</li>
                <li>Klik tombol <strong>Masuk ke Sistem</strong>. Sistem akan memvalidasi kredensial dan mengarahkan Anda ke Dashboard sesuai hak akses.</li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Keluar (Logout) yang Aman</h3>
            <ol class="guide-step mb-4">
                <li>Klik nama akun Anda di pojok kanan atas pada bilah navigasi atas (topbar).</li>
                <li>Pilih menu <strong>Keluar (Logout)</strong>.</li>
                <li>Konfirmasi pop-up dialog yang muncul. Sistem akan menghapus sesi login dan kembali ke halaman login utama.</li>
            </ol>

            <div class="alert guide-warn small mb-0">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                <strong>Protokol Keamanan Wajib:</strong>
                <ul class="mb-0 mt-1">
                    <li>Jangan pernah membagikan password akun Anda kepada siapapun. Setiap aksi di sistem tercatat di <strong>Audit Log</strong> dengan nama akun pelaku.</li>
                    <li>Hindari menyimpan kata sandi secara otomatis pada komputer bersama di garasi atau meja resepsionis.</li>
                    <li>Selalu lakukan <strong>Logout</strong> setelah menyelesaikan giliran tugas kerja (shift).</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section id="dashboard" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-gauge-high text-primary me-2"></i>Navigasi &amp; Memahami Dashboard</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Dashboard</strong> &nbsp;•&nbsp; 
                <a href="{{ route('dashboard') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Buka Dashboard</a>
            </p>

            <p class="mb-3">
                Dashboard adalah beranda utama yang menyajikan ringkasan performa bisnis dan operasional harian rental mobil dalam bentuk kartu metrik, 
                grafik interaktif, serta daftar antrean tugas mendesak.
            </p>

            <h3 class="h6 fw-bold text-dark mt-4 mb-2">Metrik &amp; Indikator Utama</h3>
            <table class="table table-bordered guide-table mb-4">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kartu Indikator</th>
                        <th>Penjelasan &amp; Tindakan yang Diperlukan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="guide-field-name"><i class="fa-solid fa-car-side text-success me-1"></i> Armada Tersedia</td>
                        <td>Menampilkan total kendaraan yang berstatus siap jalan dan tidak sedang disewa/dibersihkan. Dapat langsung ditawarkan ke pelanggan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name"><i class="fa-solid fa-road text-primary me-1"></i> Armada Sedang Disewa</td>
                        <td>Total kendaraan yang saat ini sedang aktif digunakan oleh pelanggan di jalan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name"><i class="fa-solid fa-key text-warning me-1"></i> Siap Serah Terima</td>
                        <td>Transaksi yang sudah disetujui dan menunggu petugas lapangan melakukan serah terima kunci kepada pelanggan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name"><i class="fa-solid fa-rotate-left text-danger me-1"></i> Jadwal Pengembalian</td>
                        <td>Mobil yang dijadwalkan kembali hari ini atau telah melewati batas waktu sewa dan memerlukan pengecekan fisik unit.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name"><i class="fa-solid fa-screwdriver-wrench text-secondary me-1"></i> Unit Dalam Servis</td>
                        <td>Armada yang sedang berada di bengkel untuk perawatan rutin atau perbaikan kerusakan.</td>
                    </tr>
                </tbody>
            </table>

            <div class="alert guide-tip small mb-0">
                <i class="fa-solid fa-lightbulb me-1"></i>
                <strong>Tips Efisiensi:</strong> Klik pada kartu metrik atau baris antrean di Dashboard untuk langsung diarahkan ke halaman detail transaksi atau armada yang bersangkutan.
            </div>
        </div>
    </div>
</section>

<section id="siklus-rental" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-arrows-spin text-primary me-2"></i>Siklus Alur Operasional Rental Mobil (End-to-End)</span>
            <span class="badge guide-badge-both">Semua Role</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Memahami keterhubungan antar-divisi sangat penting agar tidak terjadi salah koordinasi. 
                Berikut adalah alur lengkap perjalanan satu transaksi rental dari pemesanan hingga unit siap disewakan kembali:
            </p>

            <ol class="guide-step mb-4">
                <li>
                    <strong>Reservasi &amp; Data Pelanggan (Admin):</strong><br>
                    Admin menerima permintaan sewa, memverifikasi KTP &amp; SIM A pelanggan, memilih mobil yang tersedia, 
                    dan menentukan opsi Lepas Kunci atau Dengan Supir.
                </li>
                <li>
                    <strong>Uang Muka / DP &amp; Persetujuan (Admin):</strong><br>
                    Pelanggan membayar DP atau lunas. Admin mencatat pembayaran dan menyetujui status transaksi menjadi <code>Disetujui (Approved)</code>.
                </li>
                <li>
                    <strong>Pemeriksaan &amp; Persiapan Unit (Staff):</strong><br>
                    Petugas lapangan melakukan inspeksi fisik 12 poin checklist, membersihkan kendaraan, dan memastikan mobil berstatus <code>Siap Serah Terima (Ready Handover)</code>.
                </li>
                <li>
                    <strong>Serah Terima Kunci / Handover (Staff &amp; Admin):</strong><br>
                    Pelanggan datang ke kantor atau mobil diantar ke lokasi. Petugas mencatat Odometer awal (KM), level BBM, memeriksa kartu identitas asli, 
                    menandatangani SPK, dan menyerahkan kunci unit. Status berubah menjadi <code>Aktif / Sedang Disewa (Active)</code>.
                </li>
                <li>
                    <strong>Masa Sewa &amp; Pemantauan (Admin &amp; Staff):</strong><br>
                    Admin memantau pergerakan jadwal di Kalender Booking.
                </li>
                <li>
                    <strong>Pengembalian Unit / Return (Staff):</strong><br>
                    Pelanggan mengembalikan mobil. Petugas lapangan mengecek KM akhir, level BBM, kondisi fisik (apakah ada goresan/penyok baru), 
                    serta keterlambatan waktu pengembalian. Sistem secara otomatis menghitung selisih BBM dan denda keterlambatan jika ada.
                </li>
                <li>
                    <strong>Pembersihan &amp; Cuci Mobil (Staff):</strong><br>
                    Setelah pengembalian, status mobil otomatis menjadi <code>Pembersihan (Cleaning)</code>. Petugas mencuci mobil hingga bersih, 
                    lalu menekan tombol "Siap Jalan" agar mobil kembali berstatus <code>Tersedia (Available)</code>.
                </li>
                <li>
                    <strong>Pelunasan &amp; Pengembalian Deposit (Admin):</strong><br>
                    Admin memproses pelunasan sisa tagihan jika belum lunas, memotong denda dari deposit jika ada, atau mengembalikan uang deposit jaminan seutuhnya kepada pelanggan. Transaksi dinyatakan <code>Selesai (Completed)</code>.
                </li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-4 mb-2">Tabel Arti Status Transaksi</h3>
            <table class="table table-bordered guide-table mb-4">
                <thead>
                    <tr>
                        <th style="width: 25%;">Status Transaksi</th>
                        <th>Kondisi &amp; Arti</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge text-bg-warning">Pending</span></td>
                        <td>Pemesanan baru dicatat, menunggu kelengkapan verifikasi KTP/SIM atau konfirmasi pembayaran uang muka (DP).</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-info">Disetujui (Approved)</span></td>
                        <td>Pemesanan telah diverifikasi dan dikonfirmasi ketersediaan unitnya oleh Admin.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-primary">Siap Diserahkan (Ready)</span></td>
                        <td>Armada telah dipersiapkan di garasi dan siap diambil oleh penyewa atau diantar supir.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-success">Aktif (Active)</span></td>
                        <td>Mobil telah diserahterimakan dan saat ini sedang digunakan di jalan oleh pelanggan.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-secondary">Selesai (Completed)</span></td>
                        <td>Unit telah dikembalikan, seluruh biaya &amp; denda telah diselesaikan, dan deposit telah diproses.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-danger">Dibatalkan (Cancelled)</span></td>
                        <td>Transaksi dibatalkan karena permintaan pelanggan, penolakan verifikasi, atau unit tidak tersedia.</td>
                    </tr>
                </tbody>
            </table>

            <h3 class="h6 fw-bold text-dark mt-4 mb-2">Tabel Arti Status Armada Mobil</h3>
            <table class="table table-bordered guide-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 25%;">Status Armada</th>
                        <th>Kondisi &amp; Arti</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge text-bg-success">Tersedia (Available)</span></td>
                        <td>Kondisi prima, bersih, berada di garasi, dan siap disewakan kapan saja.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-primary">Sedang Disewa (Rented)</span></td>
                        <td>Sedang dipakai oleh penyewa dalam transaksi yang aktif.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-warning">Pembersihan (Cleaning)</span></td>
                        <td>Baru saja dikembalikan oleh pelanggan, sedang dicuci/dibersihkan sebelum siap disewakan lagi.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-danger">Perawatan (Maintenance)</span></td>
                        <td>Sedang berada di bengkel untuk servis berkala atau perbaikan kerusakan.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-secondary">Nonaktif (Inactive)</span></td>
                        <td>Unit ditarik sementara dari operasional (misal: pengurusan pajak/STNK, atau mobil cadangan).</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
