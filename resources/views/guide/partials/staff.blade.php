{{-- ================= PANDUAN PETUGAS LAPANGAN / STAFF GARASI ================= --}}

<section id="staff-ringkasan" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-wrench text-info me-2"></i>Peran &amp; Tanggung Jawab Petugas Garasi / Staff</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="mb-3">
                Sebagai <strong>Petugas Lapangan (Staff Garasi)</strong>, Anda adalah garda terdepan dalam menjaga keselamatan, 
                kondisi fisik, dan kelayakan seluruh armada kendaraan rental. Tanggung jawab Anda meliputi pemeriksaan sebelum &amp; sesudah mobil disewa, 
                menyerahkan serta menerima kembali unit mobil dari pelanggan secara teliti dan akurat.
            </p>

            <div class="row g-3 mb-3">
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-clipboard-check text-success me-1"></i> 1. Inspeksi Fisik</div>
                        <p class="small text-muted mb-0">Cek 12 poin checklist kelayakan mesin, lampu, ban, dan dokumentasi foto 4 sisi bodi mobil.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-key text-primary me-1"></i> 2. Serah Terima (Handover)</div>
                        <p class="small text-muted mb-0">Pencatatan Odometer awal, level BBM keluar, kelengkapan STNK asli, kunci kontak, dan penandatanganan SPK.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light">
                        <div class="fw-bold text-dark mb-1"><i class="fa-solid fa-rotate-left text-warning me-1"></i> 3. Pengembalian (Return)</div>
                        <p class="small text-muted mb-0">Pengecekan Odometer akhir, level BBM kembali, keterlambatan jam sewa, serta deteksi goresan / lecet baru.</p>
                    </div>
                </div>
            </div>

            <div class="alert guide-info small mb-0">
                <i class="fa-solid fa-circle-info me-1"></i>
                <strong>Integritas Data:</strong> Data Odometer dan BBM yang Anda input saat serah terima dan pengembalian digunakan langsung oleh sistem untuk menghitung denda keterlambatan, biaya BBM, dan riwayat pemakaian mobil.
            </div>
        </div>
    </div>
</section>

<section id="staff-pemeriksaan" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-clipboard-check text-info me-2"></i>Pemeriksaan Rutin &amp; Checklist Armada</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pemeriksaan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('inspections.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Pemeriksaan</a> &nbsp;|&nbsp;
                <a href="{{ route('inspections.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Input Pemeriksaan Baru</a>
            </p>

            <p class="mb-3">
                Pemeriksaan wajib dilakukan sebelum kendaraan diserahkan ke pelanggan atau sebagai inspeksi rutin berkala di garasi.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Pengisian Form Pemeriksaan</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Pemeriksaan</strong> di sidebar kiri, lalu klik tombol <strong>+ Pemeriksaan Baru</strong>.</li>
                <li>Pilih <strong>Armada Mobil</strong> yang akan diperiksa (misal: Toyota Avanza - B 1234 XYZ).</li>
                <li>Pilih <strong>Tipe Pemeriksaan</strong>: Sebelum Sewa (Pre-rental), Sesudah Sewa (Post-rental), atau Rutin Harian (Routine).</li>
                <li>Catat <strong>Angka Odometer Saat Ini</strong> (KM) dan <strong>Level Bahan Bakar</strong> (misal: Penuh, 3/4, 1/2, 1/4).</li>
                <li>
                    Lakukan pengecekan fisik 12 poin checklist standar keselamatan:
                    <ul class="mt-1 small">
                        <li><strong>Kelistrikan &amp; Penerangan:</strong> Lampu utama (dekat/jauh), lampu sein kiri/kanan, lampu rem, klakson, wiper &amp; air washer.</li>
                        <li><strong>Roda &amp; Pengereman:</strong> Ketebalan kembang ban, tekanan angin 4 ban + 1 ban cadangan, fungsi rem tangan &amp; rem kaki.</li>
                        <li><strong>Mesin &amp; Fluida:</strong> Volume oli mesin, level air radiator (coolant), minyak rem, dan air aki.</li>
                        <li><strong>Interior &amp; Kenyamanan:</strong> Kesejukan AC, sabuk pengaman seluruh kursi, kebersihan karpet &amp; jok mobil.</li>
                        <li><strong>Perlengkapan Darurat:</strong> Dongkrak, kunci roda, segitiga pengaman, dan kotak P3K.</li>
                    </ul>
                </li>
                <li>
                    Unggah <strong>Foto Kondisi Fisik Kendaraan</strong>:
                    <div class="small text-muted mt-1">
                        Sistem mendukung upload gambar berformat JPG, PNG, atau WebP. Gambar otomatis dikompres ke format WebP berkualitas optimal tanpa membebani penyimpanan server.
                    </div>
                </li>
                <li>Tentukan <strong>Status Kelayakan</strong>: <em>Layak Jalan (Passed)</em> jika aman, atau <em>Perlu Perbaikan (Failed)</em> jika ditemukan masalah fatal.</li>
                <li>Klik tombol <strong>Simpan Hasil Pemeriksaan</strong>.</li>
            </ol>

            <div class="alert guide-warn small mb-0">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                <strong>Peringatan Keselamatan:</strong> Jangan pernah meloloskan kendaraan dengan rem blong, ban botak, atau radiator bocor. Jika kendaraan tidak layak, segera laporkan ke Admin dan ajukan perawatan.
            </div>
        </div>
    </div>
</section>

<section id="staff-serah-terima" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-key text-info me-2"></i>Prosedur Serah Terima Unit (Handover)</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Serah Terima</strong> &nbsp;•&nbsp; 
                <a href="{{ route('handover.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Serah Terima</a>
            </p>

            <p class="mb-3">
                Halaman ini menampilkan seluruh transaksi sewa yang telah disetujui (status <code>Disetujui</code> atau <code>Siap Diserahkan</code>) 
                dan menunggu penyerahan unit mobil kepada penyewa.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Pelaksanaan Serah Terima</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Serah Terima</strong> di sidebar. Cari nomor transaksi atau nama pelanggan yang bersangkutan.</li>
                <li>Klik tombol <strong>Proses Serah Terima</strong> pada baris transaksi tersebut.</li>
                <li>
                    <strong>Validasi Dokumen Fisik:</strong>
                    Minta pelanggan menunjukkan e-KTP dan SIM A fisik asli. Pastikan identitas sama persis dengan data yang diverifikasi Admin di sistem.
                </li>
                <li>
                    <strong>Inspeksi Bersama Pelanggan:</strong>
                    Ajak pelanggan berkeliling mobil untuk melihat kondisi bodi luar, goresan yang sudah ada sebelumnya (jika ada), serta kelengkapan STNK dan alat darurat.
                </li>
                <li>
                    <strong>Input Data Serah Terima di Sistem:</strong>
                    <ul class="mt-1 small">
                        <li>Masukkan <strong>Odometer Awal (KM Keluar)</strong> yang tertera pada speedometer dashboard mobil.</li>
                        <li>Pilih <strong>Level BBM Keluar</strong> (misal: Penuh / Full Bar).</li>
                        <li>Tulis catatan kondisi awal jika ada baret minor agar pelanggan tidak disalahkan saat pengembalian.</li>
                    </ul>
                </li>
                <li>Minta tanda tangan basah pelanggan pada lembar fisik <strong>Surat Perjanjian Sewa (SPK)</strong>.</li>
                <li>Serahkan <strong>Kunci Kontak Mobil &amp; STNK Asli</strong> kepada pelanggan atau supir yang ditugaskan.</li>
                <li>
                    Klik tombol <strong>Konfirmasi Serah Terima</strong> di sistem.<br>
                    <span class="badge text-bg-success mt-1">Dampak Sistem:</span> Status transaksi otomatis berubah menjadi <code>Aktif (Active)</code> dan status mobil menjadi <code>Sedang Disewa (Rented)</code>.
                </li>
            </ol>

            <div class="alert guide-tip small mb-0">
                <i class="fa-solid fa-lightbulb me-1"></i>
                <strong>Tips Lapangan:</strong> Sarankan pelanggan untuk memotret atau merekam video 360 derajat keliling mobil sebelum membawa mobil keluar dari garasi untuk kenyamanan kedua belah pihak.
            </div>
        </div>
    </div>
</section>

<section id="staff-pengembalian" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-rotate-left text-info me-2"></i>Prosedur Pengembalian Unit (Return) &amp; Perhitungan Denda</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Pengembalian</strong> &nbsp;•&nbsp; 
                <a href="{{ route('return.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Pengembalian</a>
            </p>

            <p class="mb-3">
                Saat mobil kembali ke garasi, petugas lapangan wajib melakukan pengecekan cermat untuk mendeteksi keterlambatan, 
                kekurangan bahan bakar, dan kerusakan baru sebelum transaksi dinyatakan selesai.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Pengecekan Pengembalian Unit</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Pengembalian</strong> di sidebar. Temukan transaksi mobil yang baru saja tiba di garasi.</li>
                <li>Klik tombol <strong>Proses Pengembalian</strong>.</li>
                <li>Terima kunci kontak dan STNK asli dari pelanggan. Pastikan STNK tidak tertinggal di dompet pelanggan.</li>
                <li>
                    <strong>Input Odometer Akhir (KM Masuk):</strong>
                    Catat angka KM pada speedometer. Sistem akan menghitung total jarak tempuh yang telah dijalani selama masa sewa.
                </li>
                <li>
                    <strong>Pengecekan Level Bahan Bakar (BBM):</strong>
                    <ul class="mt-1 small">
                        <li>Jika level BBM sama atau lebih banyak dari level keluar: tidak dikenakan biaya BBM.</li>
                        <li>Jika level BBM kurang dari level keluar: sistem menghitung biaya kekurangan BBM yang harus diganti oleh penyewa.</li>
                    </ul>
                </li>
                <li>
                    <strong>Perhitungan Denda Keterlambatan Waktu (Late Fee):</strong>
                    Sistem secara otomatis membandingkan waktu aktual pengembalian dengan jadwal rencana kembali pada SPK. Jika terlambat, sistem otomatis mengalikan jumlah jam keterlambatan dengan tarif denda per jam yang diatur dalam Pengaturan Bisnis.
                </li>
                <li>
                    <strong>Pemeriksaan Kerusakan Fisik:</strong>
                    Kelilingi mobil dan periksa apakah ada baret baru, penyok, kaca retak, atau jok terbakar/kotor parah. Jika ada kerusakan baru, centang opsi ada kerusakan, tulis catatan deskripsi kerusakan, dan cantumkan estimasi biaya ganti rugi.
                </li>
                <li>
                    Klik tombol <strong>Konfirmasi Pengembalian Mobil</strong>.<br>
                    <span class="badge text-bg-warning mt-1">Dampak Sistem:</span> Status mobil secara otomatis berpindah ke <code>Sedang Dibersihkan (Cleaning)</code> sehingga tidak bisa langsung disewa orang lain sebelum dicuci bersih.
                </li>
            </ol>

            <div class="alert guide-warn small mb-0">
                <i class="fa-solid fa-triangle-exclamation me-1"></i>
                <strong>Penting:</strong> Jangan langsung mengembalikan uang jaminan (deposit) sebelum Staff Garasi selesai memvalidasi form pengembalian ini. Segala pemotongan denda dan BBM akan langsung diteruskan ke Admin untuk pelunasan.
            </div>
        </div>
    </div>
</section>

<section id="staff-pembersihan" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-soap text-info me-2"></i>Pembersihan Armada &amp; Pengembalian Status Siap Jalan</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Kendaraan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('vehicles.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Daftar Kendaraan</a>
            </p>

            <p class="mb-3">
                Setelah proses pengembalian unit selesai, mobil berstatus <code>Cleaning</code>. 
                Mobil yang berstatus <code>Cleaning</code> tidak akan muncul di opsi pilihan pembuatan sewa baru bagi Admin, 
                guna memastikan setiap pelanggan menerima mobil dalam kondisi wangi, bersih, dan higienis.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Standar Pembersihan Unit (SOP Cuci Garasi)</h3>
            <ul class="mb-4 small">
                <li><strong>Eksterior:</strong> Cuci bodi mobil menggunakan sampo khusus, bersihkan debu velg, keringkan dengan lap microfiber, dan semir ban (tyre polish).</li>
                <li><strong>Interior:</strong> Bersihkan remah makanan &amp; sampah, sedot debu seluruh karpet &amp; sela jok dengan vacuum cleaner, lap dashboard dan kisi-kisi AC.</li>
                <li><strong>Aroma:</strong> Semprotkan pengharum ruangan mobil aroma segar dan pastikan tidak ada bau rokok atau bau lembap yang tertinggal.</li>
            </ul>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Mengaktifkan Kembali Unit di Sistem</h3>
            <ol class="guide-step mb-4">
                <li>Buka menu <strong>Kendaraan</strong> di sidebar.</li>
                <li>Cari mobil yang telah selesai dibersihkan (memiliki badge warna kuning <em>Cleaning</em>).</li>
                <li>Klik tombol <strong>Tandai Siap Jalan / Tersedia</strong>.</li>
                <li>Sistem memperbarui status mobil menjadi <code>Tersedia (Available)</code>. Mobil siap ditugaskan kembali untuk transaksi berikutnya.</li>
            </ol>
        </div>
    </div>
</section>

<section id="staff-perawatan" class="guide-section mb-4" data-roles="super_admin admin staff">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between border-bottom">
            <span class="fw-bold"><i class="fa-solid fa-screwdriver-wrench text-info me-2"></i>Pencatatan Perawatan &amp; Servis Bengkel</span>
            <span class="badge guide-badge-staff">Petugas Lapangan / Staff</span>
        </div>
        <div class="card-body p-4">
            <p class="guide-menu-path mb-3">
                <i class="fa-solid fa-bars me-1"></i>Menu: <strong>Perawatan</strong> &nbsp;•&nbsp; 
                <a href="{{ route('maintenances.index') }}" class="guide-a"><i class="fa-solid fa-arrow-up-right-from-square me-1"></i>Riwayat Perawatan</a> &nbsp;|&nbsp;
                <a href="{{ route('maintenances.create') }}" class="guide-a"><i class="fa-solid fa-plus me-1"></i>Catat Servis Baru</a>
            </p>

            <p class="mb-3">
                Menu Perawatan digunakan untuk mencatat setiap kali mobil masuk bengkel, baik untuk jadwal servis oli rutin, 
                penggantian suku cadang, spooring balancing, maupun perbaikan body repair akibat insiden.
            </p>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Kapan Harus Mengajukan Perawatan?</h3>
            <div class="row g-2 mb-4">
                <div class="col-md-6">
                    <div class="p-2 border rounded bg-light small">
                        <strong>Servis Berkala / Rutin:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Kelipatan Odometer 5.000 KM atau 10.000 KM (Ganti oli mesin &amp; filter oli).</li>
                            <li>Pembersihan &amp; isi freon AC berkala tiap 6 bulan.</li>
                            <li>Spooring, balancing, dan rotasi ban tiap 10.000 KM.</li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-2 border rounded bg-light small">
                        <strong>Perbaikan Kerusakan Insidentil:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Rem berdecit atau getar saat pengereman mendadak.</li>
                            <li>Lampu indikator Check Engine menyala di dashboard.</li>
                            <li>Klaim perbaikan bodi baret / penyok setelah pengembalian penyewa.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <h3 class="h6 fw-bold text-dark mt-3 mb-2">Langkah Mencatat Perawatan Baru</h3>
            <ol class="guide-step mb-3">
                <li>Buka menu <strong>Perawatan</strong>, lalu klik tombol <strong>+ Catat Perawatan Baru</strong>.</li>
                <li>Pilih <strong>Kendaraan</strong> yang akan diservis.</li>
                <li>Pilih <strong>Jenis Perawatan</strong>: <em>Rutin</em> atau <em>Perbaikan Kerusakan</em>.</li>
                <li>Masukkan <strong>Nama Bengkel Mitra</strong> tempat mobil diperbaiki.</li>
                <li>Tentukan <strong>Tanggal Mulai Masuk Bengkel</strong> dan perkiraan tanggal selesai.</li>
                <li>Isi <strong>Estimasi Biaya Servis (Rp)</strong> dan rincian pekerjaan pada kolom deskripsi.</li>
                <li>Klik tombol <strong>Simpan Catatan Perawatan</strong>. Status mobil otomatis berpindah ke <code>Perawatan (Maintenance)</code>.</li>
            </ol>
        </div>
    </div>
</section>
