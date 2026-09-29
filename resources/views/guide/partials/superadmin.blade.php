{{-- ================= PANDUAN SUPER ADMIN ================= --}}

<section id="sa-ringkasan" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-user-shield text-purple me-2"></i>Wewenang &amp; Hak Akses Penuh Super Admin</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Sebagai <strong>Super Admin</strong>, Anda memiliki hak istimewa tertinggi (Full Access) dalam arsitektur sistem rental mobil. 
                Anda bertanggung jawab atas tata kelola akun personil, master aset kendaraan, kebijakan penetapan harga &amp; denda, 
                pemantauan audit log keamanan, serta rekapitulasi laporan finansial eksekutif.
            </p>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-users-gear text-purple fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Manajemen Pengguna</h4>
                        <p class="small text-muted mb-0">Tambah akun staf, atur hak akses peran, ganti password, dan toggle nonaktif akun.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-car text-primary fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Master Armada</h4>
                        <p class="small text-muted mb-0">Tambah unit baru, kelola nomor polisi, transmisi, harga sewa, dan hapus kendaraan.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-gears text-warning fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Pengaturan Bisnis</h4>
                        <p class="small text-muted mb-0">Konfigurasi nama rental, kontak WA, tarif denda per jam, dan syarat ketentuan SPK.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-chart-pie text-success fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Laporan &amp; Audit</h4>
                        <p class="small text-muted mb-0">Laporan omzet, utilisasi armada mobil, ekspor file CSV/Excel, dan jejak audit log.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="sa-pengguna" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-users-gear text-purple me-2"></i>Manajemen Akun Pengguna &amp; Hak Akses</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pengguna</strong> &nbsp;•&nbsp; 
                <a href="{{ route('users.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Pengguna</a> &nbsp;|&nbsp;
                <a href="{{ route('users.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Tambah Pengguna</a>
            </p>

            <p class="mb-3">
                Setiap pegawai yang mengoperasikan sistem rental mobil wajib memiliki akun individual tersendiri 
                agar setiap tindakan dan riwayat aktivitas dapat teridentifikasi secara akurat di Audit Log.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Menambahkan Akun Pengguna Baru</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Pengguna</strong>, lalu klik tombol <strong>+ Tambah Pengguna</strong>.</li>
                <li>Masukkan <strong>Nama Lengkap Pegawai</strong>.</li>
                <li>Masukkan <strong>Alamat Email Aktif</strong> (digunakan untuk login ke sistem).</li>
                <li>Tentukan <strong>Peran (Role)</strong> yang sesuai dengan tanggung jawab kerja:
                    <ul class="mt-1 small">
                        <li><strong>Super Admin:</strong> Hak akses tertinggi ke seluruh fitur dan pengaturan sistem.</li>
                        <li><strong>Admin:</strong> Operasional reservasi sewa, pelanggan, pembayaran, supir, kalender, dan cetak SPK.</li>
                        <li><strong>Staff:</strong> Operasional lapangan, checklist inspeksi, serah terima, pengembalian, dan cuci armada.</li>
                    </ul>
                </li>
                <li>Masukkan <strong>Kata Sandi (Password)</strong> awal dan konfirmasi password.</li>
                <li>Klik tombol <strong>Simpan Pengguna</strong>. Akun baru siap digunakan untuk login.</li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Fitur Toggle Status Akun (Aktif / Nonaktif)</h3>
            <p class="small text-muted mb-2">
                Jika ada karyawan yang mutasi, cuti panjang, atau berhenti bekerja (resign), Anda tidak perlu menghapus akunnya 
                (agar riwayat transaksi masa lalu tetap utuh). Cukup klik tombol <strong>Nonaktifkan Akun</strong> pada baris nama pengguna tersebut.
            </p>
            <div class="alert guide-warn small mb-0">
                <i class="fa-solid fa-shield-halved me-1"></i>
                <strong>Dampak Nonaktif:</strong> Pengguna dengan akun nonaktif akan langsung ditolak sistem saat mencoba login, dan jika sesi sedang aktif di peramban, sesi tersebut akan dibatalkan otomatis.
            </div>
        </div>
    </div>
</section>

<section id="sa-armada" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-car text-purple me-2"></i>Manajemen Master Armada Kendaraan (Fleet Master)</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Kendaraan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('vehicles.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Kendaraan</a> &nbsp;|&nbsp;
                <a href="{{ route('vehicles.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Tambah Mobil Baru</a>
            </p>

            <p class="mb-3">
                Super Admin memiliki wewenang eksklusif untuk menambahkan unit mobil baru ke dalam daftar aset rental, 
                memperbarui harga sewa, mengatur spesifikasi teknis mobil, serta menghapus data kendaraan.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Menambahkan Unit Mobil Baru</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Kendaraan</strong>, lalu klik tombol <strong>+ Tambah Kendaraan</strong>.</li>
                <li>Masukkan <strong>Merk &amp; Model</strong> (contoh: <em>Toyota All New Avanza</em>, <em>Mitsubishi Xpander Ultimate</em>, <em>Toyota Innova Zenix</em>).</li>
                <li>Masukkan <strong>Nomor Polisi / Plat Nomor</strong> (contoh: <code>B 1234 ABC</code>). Plat nomor harus unik dan belum pernah didaftarkan.</li>
                <li>Pilih <strong>Kategori Kendaraan</strong>: MPV, SUV, City Car, Sedan, atau Minibus.</li>
                <li>Pilih <strong>Tipe Transmisi</strong>: Manual (M/T) atau Otomatis (A/T).</li>
                <li>Pilih <strong>Jenis Bahan Bakar</strong>: Bensin, Solar / Diesel, Hybrid, atau Listrik (EV).</li>
                <li>Isi <strong>Tahun Pembuatan</strong>, <strong>Warna Bodi</strong>, dan <strong>Kapasitas Kursi Penumpang</strong> (misal: 7 Kursi).</li>
                <li>Masukkan <strong>Tarif Sewa Harian (Rp/Hari)</strong> yang menjadi dasar perhitungan transaksi.</li>
                <li>Unggah <strong>Foto Profil Mobil</strong>: Foto tampilan eksterior tampak depan-samping mobil yang menarik untuk ditampilkan pada sistem.</li>
                <li>Klik tombol <strong>Simpan Kendaraan</strong>. Unit langsung berstatus <code>Tersedia (Available)</code>.</li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Proteksi Penghapusan Kendaraan</h3>
            <p class="small text-muted mb-0">
                Sistem dilengkapi dengan proteksi integritas basis data. Unit mobil yang telah memiliki riwayat transaksi sewa aktif 
                tidak dapat dihapus sembarangan untuk menjaga keakuratan laporan keuangan dan audit pembukuan. 
                Jika unit mobil tersebut sudah dijual atau tidak dipakai, cukup ubah statusnya menjadi <code>Nonaktif (Inactive)</code>.
            </p>
        </div>
    </div>
</section>

<section id="sa-pengaturan" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-gears text-purple me-2"></i>Pengaturan Bisnis, Denda &amp; Kebijakan Rental</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pengaturan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('settings.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Buka Pengaturan Bisnis</a>
            </p>

            <p class="mb-3">
                Halaman Pengaturan adalah pusat konfigurasi aturan bisnis rental mobil yang memengaruhi cara kerja sistem secara menyeluruh.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Konfigurasi yang Dapat Diatur</h3>
            <table class="table table-bordered guide-table mb-4">
                <thead>
                    <tr>
                        <th style="width: 25%;">Bagian Pengaturan</th>
                        <th>Penjelasan &amp; Dampak ke Sistem</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="guide-field-name">Identitas Perusahaan</td>
                        <td>Nama Rental Mobil (contoh: <em>Jaya Trans Rental Mobil</em>), Alamat Kantor Pusat, Nomor WhatsApp CS, dan Email Resmi. Informasi ini dicetak otomatis pada Kop Surat SPK dan Invoice tagihan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Tarif Denda Keterlambatan (Late Fee)</td>
                        <td>Nominal biaya denda per jam jika penyewa terlambat mengembalikan mobil melewati batas jam sewa yang disepakati. Sistem mengalikan jam keterlambatan dengan nominal ini secara otomatis saat proses pengembalian unit.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Standar Deposit Jaminan</td>
                        <td>Besaran uang jaminan sewa (security deposit) default yang disarankan saat membuat transaksi baru untuk memproteksi denda atau kekurangan BBM.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Prefix Kode Transaksi &amp; Invoice</td>
                        <td>Awalan kode otomatis untuk nomor transaksi (contoh: <code>TRX-</code>) dan faktur pembayaran (contoh: <code>INV-</code>).</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Syarat &amp; Ketentuan Rental</td>
                        <td>Teks pasal klausul hak &amp; kewajiban penyewa yang akan dicetak di bagian belakang / bawah dokumen Surat Perjanjian Sewa (SPK).</td>
                    </tr>
                </tbody>
            </table>

            <div class="alert guide-tip small mb-0">
                <i class="fa-solid fa-lightbulb me-1"></i>
                <strong>Tips:</strong> Setelah mengubah konfigurasi pada form pengaturan, pastikan mengklik tombol <strong>Simpan Pengaturan</strong> di bawah halaman untuk menerapkan perubahan seketika.
            </div>
        </div>
    </div>
</section>

<section id="sa-laporan" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-chart-column text-purple me-2"></i>Laporan Finansial, Omzet &amp; Ekspor Data</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Laporan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('reports.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Laporan &amp; Statistik</a> &nbsp;|&nbsp;
                <a href="{{ route('reports.export') }}" class="guide-a"><i class="fa-solid fa-download me-1"></i>Ekspor Data</a>
            </p>

            <p class="mb-3">
                Menu Laporan menyediakan analisa menyeluruh tentang kesehatan finansial dan utilisasi armada bisnis rental mobil Anda.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Metrik Analisa yang Tersedia</h3>
            <ul class="mb-4 small">
                <li><strong>Total Omzet &amp; Pendapatan Bersih:</strong> Rincian pendapatan total dari biaya sewa mobil, jasa supir, biaya antar-jemput, dan penerimaan denda keterlambatan dalam rentang waktu yang dipilih (harian, mingguan, bulanan, tahunan).</li>
                <li><strong>Tingkat Utilisasi Armada:</strong> Mengetahui mobil mana yang paling produktif (paling sering disewa) dan mobil mana yang jarang disewa sehingga dapat dievaluasi strategi pemasarannya.</li>
                <li><strong>Kinerja Jasa Supir:</strong> Rekap penugasan supir beserta total pendapatan jasa pengemudi.</li>
                <li><strong>Biaya Perawatan Bengkel:</strong> Akumulasi pengeluaran biaya servis dan suku cadang seluruh armada.</li>
            </ul>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Ekspor Data ke Excel / CSV</h3>
            <ol class="guide-step mb-0">
                <li>Buka menu <strong>Laporan</strong>, lalu klik tab atau tombol <strong>Ekspor Laporan</strong>.</li>
                <li>Pilih <strong>Rentang Tanggal</strong> transaksi yang ingin diekspor (misal: 1 bulan terakhir atau kuartal ini).</li>
                <li>Pilih filter status transaksi jika diperlukan (misal: hanya transaksi yang sudah Selesai / Completed).</li>
                <li>Klik tombol <strong>Unduh File CSV / Excel</strong>. File laporan akan otomatis terunduh dan siap diolah pada aplikasi spreadsheet (Microsoft Excel, Google Sheets, dll.) untuk pembukuan akuntansi.</li>
            </ol>
        </div>
    </div>
</section>

<section id="sa-audit-log" class="guide-section mb-4" data-roles="super_admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-clock-rotate-left text-purple me-2"></i>Rekam Jejak Audit Log Keamanan Sistem</span>
            <span class="badge guide-badge-sa">Khusus Super Admin</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Audit Log</strong> &nbsp;•&nbsp; 
                <a href="{{ route('audit-logs.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Buka Audit Log</a>
            </p>

            <p class="mb-3">
                Fitur Audit Log adalah sistem rekam jejak digital otomatis yang mencatat setiap peristiwa dan perubahan data krusial di dalam aplikasi 
                guna menjamin transparansi operasional dan mencegah kecurangan internal (fraud).
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Informasi yang Tercatat di Audit Log</h3>
            <table class="table table-bordered guide-table mb-4">
                <thead>
                    <tr>
                        <th style="width: 25%;">Kolom Data</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="guide-field-name">Waktu Kejadian (Timestamp)</td>
                        <td>Tanggal, jam, menit, dan detik presisi saat aksi dilakukan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Pelaku (User)</td>
                        <td>Nama dan email akun staf yang sedang login saat mengeksekusi tindakan.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Tipe Aksi (Action)</td>
                        <td>Aktivitas yang terjadi: <code>Created</code> (tambah), <code>Updated</code> (ubah), <code>Deleted</code> (hapus), <code>Approved</code> (setujui), atau <code>Verified</code> (verifikasi).</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Entitas / Modul</td>
                        <td>Objek data yang dimodifikasi, seperti Transaksi, Pelanggan, Kendaraan, Pembayaran, atau Pengguna.</td>
                    </tr>
                    <tr>
                        <td class="guide-field-name">Alamat IP (IP Address)</td>
                        <td>Alamat IP perangkat komputer yang digunakan untuk mengakses sistem.</td>
                    </tr>
                </tbody>
            </table>

            <div class="alert guide-info small mb-0">
                <i class="fa-solid fa-circle-info me-1"></i>
                <strong>Pemeriksaan Investigasi:</strong> Jika terdapat perbedaan catatan nominal pembayaran atau status transaksi yang mendadak berubah, buka Audit Log dan filter berdasarkan modul Transaksi untuk melacak siapa yang melakukan pengubahan tersebut.
            </div>
        </div>
    </div>
</section>
