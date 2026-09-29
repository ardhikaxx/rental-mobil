{{-- ================= PANDUAN ADMIN OPERASIONAL ================= --}}

<section id="admin-ringkasan" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-briefcase text-warning me-2"></i>Peran &amp; Tanggung Jawab Admin Operasional</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                <strong>Admin Operasional</strong> memegang kendali atas alur administrasi sewa menyewa, reservasi pelanggan, 
                verifikasi keabsahan dokumen identitas, koordinasi supir, pencatatan transaksi finansial (DP, pelunasan, deposit), 
                serta pencetakan dokumen legal seperti Surat Perjanjian Sewa Kendaraan (SPK) dan Faktur Tagihan (Invoice).
            </p>

            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-file-invoice text-warning fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Transaksi Rental</h4>
                        <p class="small text-muted mb-0">Input booking baru, verifikasi ketersediaan armada, approval, dan pembatalan sewa.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-id-card text-primary fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Verifikasi KTP &amp; SIM</h4>
                        <p class="small text-muted mb-0">Validasi NIK 16 digit, masa berlaku SIM A, dan penilaian kelayakan penyewa.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-money-bill-wave text-success fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Keuangan &amp; Deposit</h4>
                        <p class="small text-muted mb-0">Pencatatan uang muka (DP), pelunasan sisa biaya, dan penahanan/pengembalian deposit.</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="p-3 border rounded bg-light text-center h-100">
                        <i class="fa-solid fa-calendar-days text-danger fa-2x mb-2"></i>
                        <h4 class="h6 fw-bold mb-1">Kalender &amp; Dokumen</h4>
                        <p class="small text-muted mb-0">Monitoring jadwal sewa armada, cegah bentrok jadwal, dan cetak SPK/Invoice resmi.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="admin-transaksi" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-file-invoice text-warning me-2"></i>Manajemen Transaksi Pemesanan Rental</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Transaksi</strong> &nbsp;•&nbsp; 
                <a href="{{ route('transactions.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Transaksi</a> &nbsp;|&nbsp;
                <a href="{{ route('transactions.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Buat Transaksi Baru</a>
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Membuat Transaksi Sewa Baru</h3>
            <ol class="guide-step mb-4">
                <li>
                    Buka menu <strong>Transaksi</strong>, lalu klik tombol <strong>+ Buat Transaksi Baru</strong>.
                </li>
                <li>
                    <strong>Pilih Pelanggan:</strong> Pilih dari daftar pelanggan yang telah terdaftar. Jika pelanggan baru pertama kali menyewa, pastikan untuk mendaftarkan datanya terlebih dahulu di menu Pelanggan.
                </li>
                <li>
                    <strong>Pilih Kendaraan:</strong> Pilih mobil dari armada yang berstatus <code>Tersedia (Available)</code>. Sistem akan menampilkan harga sewa per hari secara otomatis.
                </li>
                <li>
                    <strong>Tipe Layanan:</strong>
                    <ul class="mt-1 small">
                        <li><strong>Lepas Kunci (Self-Drive):</strong> Pelanggan menyetir sendiri. Wajib verifikasi SIM A aktif.</li>
                        <li><strong>Dengan Supir (With Driver):</strong> Rental mobil beserta jasa pengemudi. Anda wajib memilih supir yang sedang aktif/tersedia dari dropdown. Tarif supir harian akan otomatis ditambahkan ke total tagihan.</li>
                    </ul>
                </li>
                <li>
                    <strong>Tentukan Tanggal &amp; Jam Sewa:</strong>
                    Masukkan tanggal &amp; jam mulai sewa serta tanggal &amp; jam rencana pengembalian. Sistem akan otomatis mengalkulasikan total durasi hari sewa.
                </li>
                <li>
                    <strong>Biaya Tambahan &amp; Promo:</strong>
                    <ul class="mt-1 small">
                        <li><strong>Biaya Pengantaran (Delivery Fee):</strong> Jika mobil diantar ke rumah/bandara/stasiun penyewa.</li>
                        <li><strong>Biaya Penjemputan (Pickup Fee):</strong> Jika mobil diambil kembali dari lokasi penyewa.</li>
                        <li><strong>Diskon Promo:</strong> Potongan harga khusus pelanggan loyal atau event promo.</li>
                        <li><strong>Deposit Jaminan (Security Deposit):</strong> Uang jaminan yang ditahan selama masa sewa dan akan dikembalikan saat pengembalian unit aman tanpa denda.</li>
                    </ul>
                </li>
                <li>
                    Periksa ringkasan total tagihan, lalu klik <strong>Simpan Transaksi</strong>. Transaksi akan tercatat dengan status awal <code>Pending</code>.
                </li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Aksi Pengelolaan Transaksi</h3>
            <table class="table table-bordered guide-table mb-0">
                <thead>
                    <tr>
                        <th style="width: 25%;">Tombol Aksi</th>
                        <th>Kapan Digunakan &amp; Dampak ke Sistem</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="badge text-bg-success">Setujui Transaksi</span></td>
                        <td>Digunakan setelah penyewa membayar uang muka (DP) dan dokumen e-KTP/SIM A dinyatakan valid. Status transaksi berubah menjadi <code>Disetujui (Approved)</code>.</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-primary">Siap Diserahkan</span></td>
                        <td>Digunakan jika armada sudah dicek dan siap diambil oleh penyewa di garasi. Transaksi berpindah ke antrean Serah Terima (Handover).</td>
                    </tr>
                    <tr>
                        <td><span class="badge text-bg-danger">Batalkan Transaksi</span></td>
                        <td>Digunakan jika penyewa mengurungkan sewa atau data terbukti palsu. Wajib mengisi alasan pembatalan resmi. Mobil otomatis kembali <code>Tersedia</code>.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>

<section id="admin-pelanggan" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-users text-warning me-2"></i>Pendaftaran &amp; Verifikasi Dokumen Pelanggan</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pelanggan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('customers.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Pelanggan</a> &nbsp;|&nbsp;
                <a href="{{ route('customers.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Daftarkan Pelanggan</a>
            </p>

            <p class="mb-3">
                Keamanan armada dimulai dari ketatnya seleksi identitas penyewa. Sistem dilengkapi fitur verifikasi dokumen KTP dan SIM A 
                untuk meminimalkan risiko penggelapan atau penyalahgunaan kendaraan rental.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Panduan Mendaftarkan Pelanggan Baru</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Pelanggan</strong>, lalu klik <strong>+ Tambah Pelanggan Baru</strong>.</li>
                <li>Isi <strong>Nama Lengkap Sesuai KTP</strong>.</li>
                <li>Masukkan <strong>NIK KTP (16 Digit Angka)</strong>. NIK bersifat unik dan tidak boleh ganda dalam sistem.</li>
                <li>Masukkan <strong>Nomor WhatsApp / Telepon Aktif</strong> untuk koordinasi serah terima dan pengiriman invoice.</li>
                <li>Isi <strong>Nomor Kontak Darurat (Keluarga Serumah)</strong> beserta hubungan kekerabatan untuk antisipasi keadaan darurat di jalan.</li>
                <li>Isi <strong>Alamat Domisili Tinggal Sekarang</strong>.</li>
                <li>
                    <strong>Upload Foto Dokumen Identitas:</strong>
                    <ul class="mt-1 small">
                        <li><strong>Foto e-KTP Asli:</strong> Pastikan foto jelas, tidak buram, seluruh teks terbaca, dan tidak terpotong sudutnya.</li>
                        <li><strong>Foto SIM A Asli:</strong> Wajib bagi penyewa yang memilih opsi sewa lepas kunci. Pastikan masa berlaku masih aktif.</li>
                    </ul>
                </li>
                <li>Klik tombol <strong>Simpan Data Pelanggan</strong>.</li>
            </ol>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Prosedur Verifikasi Dokumen Identitas</h3>
            <ol class="guide-step mb-3">
                <li>Buka detail profil pelanggan yang baru didaftarkan.</li>
                <li>Klik thumbnail foto KTP dan SIM A untuk memperbesar pratinjau dokumen.</li>
                <li>Cocokkan kesesuaian Nama, NIK, tanggal lahir, dan masa berlaku SIM.</li>
                <li>Jika data valid dan tidak mencurigakan, klik tombol hijau <strong>Verifikasi Pelanggan</strong>. Status pelanggan akan menjadi <span class="badge text-bg-success">Terverifikasi</span>.</li>
                <li>Jika dokumen palsu, buram, atau nama berbeda, klik tombol <strong>Tolak / Minta Perbaikan Dokumen</strong>.</li>
            </ol>
        </div>
    </div>
</section>

<section id="admin-supir" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-user-tie text-warning me-2"></i>Manajemen Supir / Driver</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Supir / Driver</strong> &nbsp;•&nbsp; 
                <a href="{{ route('drivers.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Supir</a> &nbsp;|&nbsp;
                <a href="{{ route('drivers.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Tambah Supir Baru</a>
            </p>

            <p class="mb-3">
                Menu ini digunakan untuk mengelola data para pengemudi profesional yang bertugas melayani paket sewa mobil plus supir.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Penambahan &amp; Pengelolaan Supir</h3>
            <ol class="guide-step mb-3">
                <li>Klik tombol <strong>+ Tambah Supir Baru</strong>.</li>
                <li>Masukkan <strong>Nama Lengkap Supir</strong>, <strong>Nomor Telepon / WhatsApp</strong>, dan <strong>Nomor SIM</strong> (SIM A / SIM B1).</li>
                <li>Tentukan <strong>Tarif Harian Supir (Rp/Hari)</strong>. Tarif ini akan otomatis dikalikan dengan jumlah hari sewa saat membuat transaksi dengan opsi "Dengan Supir".</li>
                <li>Unggah <strong>Foto Profil Supir</strong> untuk identitas resmi.</li>
                <li>Atur <strong>Status Ketersediaan</strong>: <em>Aktif (Siap Ditugaskan)</em> atau <em>Cuti / Nonaktif</em>.</li>
                <li>Klik tombol <strong>Simpan Data Supir</strong>.</li>
            </ol>

            <div class="alert guide-info small mb-0">
                <i class="fa-solid fa-circle-info me-1"></i>
                <strong>Catatan Penugasan:</strong> Supir yang sedang menjalankan tugas di transaksi yang berstatus <code>Aktif</code> tidak dapat ditugaskan pada transaksi lain pada tanggal yang bersamaan.
            </div>
        </div>
    </div>
</section>

<section id="admin-pembayaran" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-money-bill-transfer text-warning me-2"></i>Pencatatan Pembayaran, Uang Muka (DP) &amp; Deposit</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pembayaran</strong> &nbsp;•&nbsp; 
                <a href="{{ route('payments.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Riwayat Pembayaran</a> &nbsp;|&nbsp;
                <a href="{{ route('payments.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Catat Pembayaran Baru</a>
            </p>

            <p class="mb-3">
                Setiap penerimaan uang dari penyewa wajib dicatat secara transparan di sistem agar status tagihan transaksi selalu diperbarui secara real-time.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Kategori Pembayaran di Sistem</h3>
            <ul class="mb-4 small">
                <li><strong>Uang Muka / DP:</strong> Pembayaran pertama sebagai tanda jadi pemesanan unit mobil sebelum serah terima.</li>
                <li><strong>Pelunasan:</strong> Pembayaran sisa tagihan sewa sebelum mobil keluar garasi atau saat pengembalian.</li>
                <li><strong>Deposit Jaminan:</strong> Uang jaminan sewa (ditahan selama rental berlangsung dan dikembalikan saat selesai).</li>
                <li><strong>Denda &amp; Biaya BBM:</strong> Pembayaran tagihan biaya keterlambatan atau penggantian bahan bakar saat pengembalian.</li>
            </ul>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Mencatat Pembayaran</h3>
            <ol class="guide-step mb-3">
                <li>Buka menu <strong>Pembayaran</strong>, lalu klik <strong>+ Catat Pembayaran</strong>.</li>
                <li>Pilih <strong>Nomor Transaksi</strong> yang hendak dibayarkan. Sistem akan menampilkan rincian total tagihan dan sisa tagihan yang belum dibayar.</li>
                <li>Pilih <strong>Metode Pembayaran</strong>: <em>Transfer Bank (BCA, Mandiri, BRI, BNI)</em>, <em>Tunai (Cash di Meja Kasir)</em>, atau <em>QRIS</em>.</li>
                <li>Masukkan <strong>Nominal Pembayaran (Rp)</strong> yang diterima.</li>
                <li>Masukkan <strong>Nomor Referensi Bank / Bukti Transfer</strong>.</li>
                <li>Unggah <strong>Bukti Transfer (Struk / Screenshot M-Banking)</strong> sebagai lampiran audit.</li>
                <li>Klik tombol <strong>Simpan Pembayaran</strong>. Saldo sisa tagihan transaksi akan langsung terpotong otomatis.</li>
            </ol>
        </div>
    </div>
</section>

<section id="admin-kalender" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-calendar-days text-warning me-2"></i>Kalender Booking &amp; Penjadwalan Armada</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Kalender Booking</strong> &nbsp;•&nbsp; 
                <a href="{{ route('calendar.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Buka Kalender Booking</a>
            </p>

            <p class="mb-3">
                Fitur Kalender Booking menyajikan visualisasi jadwal pemakaian seluruh armada rental mobil secara komprehensif. 
                Sangat berguna bagi Admin untuk memeriksa ketersediaan mobil saat menerima telepon atau WhatsApp pemesanan baru agar tidak terjadi jadwal ganda (double booking).
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Fitur Utama Kalender</h3>
            <ul class="mb-4 small">
                <li><strong>Tampilan Bulan, Minggu &amp; Hari:</strong> Anda dapat mengganti sudut pandang kalender sesuai kebutuhan perencanaan operasional.</li>
                <li><strong>Filter Berdasarkan Kendaraan:</strong> Pilih mobil tertentu (misal: hanya Toyota Avanza) untuk melihat rentang tanggal kosong unit tersebut.</li>
                <li><strong>Filter Berdasarkan Status Transaksi:</strong> Tampilkan jadwal pemesanan yang berstatus Pending, Approved, atau Active.</li>
                <li><strong>Modal Rincian Booking:</strong> Klik salah satu balok jadwal pada kalender untuk membuka jendela informasi cepat yang memuat: nama penyewa, nomor polisi mobil, durasi rental, dan tombol tautan langsung ke detail transaksi.</li>
            </ul>

            <div class="alert guide-tip small mb-0">
                <i class="fa-solid fa-lightbulb me-1"></i>
                <strong>Tips Pelayanan Cepat:</strong> Saat calon pelanggan menanyakan "Apakah mobil Innova kosong tanggal 15 sampai 18?", cukup buka Kalender Booking dan pilih filter Toyota Innova untuk memastikan jawabannya dalam hitungan detik.
            </div>
        </div>
    </div>
</section>

<section id="admin-cetak" class="guide-section mb-4" data-roles="super_admin admin">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-print text-warning me-2"></i>Cetak Surat Perjanjian Sewa (SPK) &amp; Faktur Tagihan (Invoice)</span>
            <span class="badge guide-badge-admin">Admin Operasional</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Sistem menyediakan template dokumen cetak resmi berstandar A4 yang siap dicetak ke printer fisik atau disimpan sebagai file PDF.
            </p>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-file-contract text-primary me-1"></i> Surat Perjanjian Sewa (SPK)</span>
                            <span class="badge text-bg-primary">Dokumen Hukum</span>
                        </div>
                        <p class="small text-muted mb-2">
                            Surat perjanjian resmi yang wajib ditandatangani oleh penyewa dan pihak rental saat serah terima unit mobil.
                        </p>
                        <strong class="small d-block mb-1">Isi Dokumen SPK:</strong>
                        <ul class="small text-muted mb-3">
                            <li>Identitas lengkap Pihak Pertama (Rental) &amp; Pihak Kedua (Penyewa).</li>
                            <li>Spesifikasi detail mobil (Merk, Tipe, Plat Nomor, Odometer keluar, Level BBM).</li>
                            <li>Jadwal mulai sewa dan batas toleransi jam pengembalian.</li>
                            <li>Pasal klausul hak &amp; kewajiban, larangan membawa mobil keluar pulau tanpa izin, dan ketentuan denda keterlambatan / kerusakan.</li>
                            <li>Kolom tanda tangan basah &amp; materai kedua belah pihak.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded bg-light h-100">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold text-dark"><i class="fa-solid fa-receipt text-success me-1"></i> Faktur Tagihan (Invoice)</span>
                            <span class="badge text-bg-success">Dokumen Keuangan</span>
                        </div>
                        <p class="small text-muted mb-2">
                            Bukti tagihan resmi bernomor unik untuk penyewa individu maupun kebutuhan reimbursement sewa kantor/perusahaan.
                        </p>
                        <strong class="small d-block mb-1">Isi Dokumen Invoice:</strong>
                        <ul class="small text-muted mb-3">
                            <li>Kop resmi rental mobil, nomor invoice, dan tanggal terbit.</li>
                            <li>Tabel rincian sewa: tarif per hari x jumlah hari.</li>
                            <li>Biaya jasa supir, biaya antar-jemput, dan diskon potongan promo.</li>
                            <li>Rincian total tagihan kotor, riwayat pembayaran yang telah masuk (DP/Pelunasan), dan sisa saldo yang harus dibayarkan.</li>
                            <li>Nomor rekening resmi perusahaan untuk pembayaran transfer.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Mencetak Dokumen</h3>
            <ol class="guide-step mb-0">
                <li>Buka halaman detail transaksi yang ingin dicetak dokumennya.</li>
                <li>Klik tombol <strong>Cetak SPK</strong> atau tombol <strong>Cetak Invoice</strong> di bagian atas halaman detail.</li>
                <li>Halaman dokumen akan terbuka dengan tata letak bersih khusus cetak (tanpa sidebar dan menu atas).</li>
                <li>Tekan tombol <code>Ctrl + P</code> pada keyboard atau klik tombol cetak pada browser. Pilih ukuran kertas <strong>A4</strong> dan simpan sebagai PDF atau cetak ke printer fisik.</li>
            </ol>
        </div>
    </div>
</section>
