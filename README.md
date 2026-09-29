# 🚗 Sistem Operasional Rental Mobil

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Pest](https://img.shields.io/badge/Tested%20with-Pest-EB4432?style=for-the-badge&logo=pest&logoColor=white)](https://pestphp.com)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=for-the-badge)](./LICENSE)

Aplikasi **Sistem Operasional Rental Mobil** adalah platform manajemen operasional rental kendaraan yang komprehensif dan siap pakai. Mencakup seluruh alur bisnis rental: master kendaraan & pelanggan, transaksi sewa (booking, DP, pelunasan), serah terima, pemeriksaan berkala dengan dokumentasi foto, pengembalian dengan perhitungan denda keterlambatan otomatis, perawatan kendaraan, kalender ketersediaan, laporan keuangan, hingga audit log.

---

## ✨ Fitur Utama

### 1. 🔑 Autentikasi & Kontrol Akses Berbasis Role (RBAC)
- Login menggunakan **username**, dilindungi middleware `EnsureAccountActive` (akun nonaktif langsung dikeluarkan dari sesi).
- Tiga level akses dengan **Gate & Policy Laravel**: Super Admin (Pemilik), Admin Operasional (Kasir), dan Staf Garasi.
- Setiap perubahan data penting tercatat di **Audit Log** beserta pelakunya.
- Toggle **tampilkan/sembunyikan password** (ikon mata) pada seluruh form password.

### 2. 🚙 Manajemen Kendaraan
- Master data kendaraan: kode unit, merek, model, tipe, tahun, warna, nomor polisi (unik), nomor rangka & mesin.
- Status kendaraan otomatis: tersedia, disewa, perawatan, nonaktif — dikelola oleh `VehicleStatusService`.
- Tarif sewa harian per unit.

### 3. 👥 Manajemen Pelanggan
- Data pelanggan lengkap dengan **Nomor Identitas (KTP) unik**, telepon, email, tanggal lahir, alamat, dan catatan internal.
- Riwayat transaksi per pelanggan.

### 4. 🧾 Transaksi Sewa (Booking → DP → Pelunasan)
- Pembuatan transaksi dengan **validasi ketersediaan kendaraan** (`VehicleAvailabilityService`) — mencegah booking tumpang tindih.
- Nomor transaksi otomatis (`RNT-YYYYMMDD-0001`) via `NumberGenerator`.
- Sistem **DP minimum dapat dikonfigurasi** (persentase dari total) — booking hanya disetujui bila pembayaran mencapai batas minimum.
- Multi metode pembayaran dan pencatatan pembayaran bertahap melalui `PaymentService`.
- Status transaksi mengikuti alur: booking → aktif → selesai → batal.
- **Invoice siap cetak** dengan kop perusahaan.

### 5. 🔑 Serah Terima & 🔄 Pengembalian
- Form serah terima: waktu, odometer awal, volume bahan bakar, dan kondisi kendaraan (eksterior, interior, ban).
- Form pengembalian dengan **preview denda keterlambatan real-time** (mode per jam/per hari + masa tenggang, dihitung oleh `LateFeeCalculator`).
- Odometer akhir otomatis menjadi kilometer terakhir kendaraan.

### 6. 📋 Pemeriksaan Kendaraan (Inspeksi)
- Pemeriksaan berkala: odometer, bahan bakar, kelengkapan, kondisi eksterior/interior/ban, barang kurang, dan catatan kerusakan.
- **Upload hingga 5 foto dokumentasi** per pemeriksaan (`PhotoStorage`).
- Riwayat pemeriksaan terhubung ke kendaraan.

### 7. 🛠️ Perawatan (Maintenance)
- Pencatatan perawatan rutin & perbaikan: jenis, status, biaya, tanggal, dan keterangan.
- Kendaraan otomatis masuk status *perawatan* selama dikerjakan.

### 8. 📅 Kalender Ketersediaan
- Kalender booking visual: lihat jadwal terpakai/tersedia per kendaraan dalam satu tampilan bulanan.

### 9. 📊 Laporan & Pengaturan
- Laporan pendapatan dan rekap operasional untuk pemilik.
- Pengaturan aplikasi: identitas perusahaan, prefix nomor transaksi/pembayaran, DP minimum, aturan denda keterlambatan — semuanya diambil dinamis dari tabel `settings`.
- Halaman **Audit Log** khusus Super Admin.

---

## 👥 Hak Akses Pengguna (Role-Based Access Control)

| Role | Akses & Wewenang |
| :--- | :--- |
| **Super Admin / Pemilik** | Akses penuh: semua modul, laporan, manajemen pengguna, audit log, dan pengaturan sistem. |
| **Admin Operasional / Kasir** | Mengelola pelanggan, transaksi, pembayaran, kalender booking, dan serah terima/pengembalian. |
| **Staf Garasi** | Menangani pemeriksaan, serah terima, pengembalian, perawatan, dan pembaruan status kendaraan. |

---

## 🛠️ Tech Stack & Dependensi

* **Backend**: Laravel 13.x (PHP 8.4+)
* **Database**: MySQL 8.0+
* **Frontend**: Blade Templating, Bootstrap 5.3, FontAwesome 6, SweetAlert2
* **Testing**: Pest (41+ test fitur)
* **Code Style**: Laravel Pint (PSR-12)

---

## ⚙️ Panduan Instalasi & Menjalankan Aplikasi

### 1. Kloning Repositori
```bash
git clone https://github.com/ardhikaxx/rental-mobil.git
cd rental-mobil
```

### 2. Install Dependensi PHP
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file konfigurasi dan buat application key:
```bash
cp .env.example .env
php artisan key:generate
```
Sesuaikan konfigurasi koneksi database MySQL di file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_mobil
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Eksekusi Migrasi & Seeding Data
Aplikasi dilengkapi seeder data demo (pengguna, pelanggan, kendaraan, transaksi, perawatan, audit log, dan pengaturan):
```bash
php artisan migrate:fresh --seed
```

### 5. Jalankan Web Server Lokal
```bash
php artisan serve
```
Buka browser Anda dan akses: `http://127.0.0.1:8000`

---

## 🔑 Akun Demo Pengujian (Testing Credentials)

Semua akun menggunakan kata sandi default: **`password`**

| Role | Username | Password |
| :--- | :--- | :--- |
| **Super Admin / Pemilik** | `superadmin` | `password` |
| **Admin Operasional / Kasir** | `adminoper` | `password` |
| **Staf Garasi** | `stafgarasi` | `password` |

> [!WARNING]
> Akun demo di atas **hanya untuk lingkungan pengembangan**. Segera ganti password dan nonaktifkan akun demo sebelum aplikasi digunakan di lingkungan produksi.

---

## 📚 Dokumentasi Sistem

* [⚖️ **LICENSE**](./LICENSE) — Lisensi resmi **Proprietary Software License (All Rights Reserved)**.
* [🛡️ **SECURITY.md**](./SECURITY.md) — Kebijakan keamanan & pelaporan celah (*Responsible Disclosure*).
* [🤝 **CONTRIBUTING.md**](./CONTRIBUTING.md) — Pedoman kontribusi kode & standar PSR-12.
* [📜 **CODE_OF_CONDUCT.md**](./CODE_OF_CONDUCT.md) — Pedoman perilaku komunitas pengembang.
* [💬 **SUPPORT.md**](./SUPPORT.md) — Kanal bantuan teknis & komunikasi resmi pengembang.
* [📋 **CHANGELOG.md**](./CHANGELOG.md) — Riwayat rilis fitur terstruktur (*Keep a Changelog* & SemVer).

---

## 💖 Dukungan & Donasi

Jika proyek **Sistem Operasional Rental Mobil** ini bermanfaat bagi Anda dan telah menghemat banyak jam kerja Anda, Anda dapat menunjukkan apresiasi dengan memberikan traktiran kopi (donasi) melalui pemindaian kode QRIS di bawah ini:

<p align="center">
  <img src="./qris.png" alt="QRIS Donasi" width="300"/>
</p>

> Donasi sepenuhnya bersifat sukarela dan tidak mengikat. Aplikasi tetap dapat digunakan secara utuh tanpa donasi.

---

## ⚠️ Ketentuan Penggunaan

Proyek ini dilindungi lisensi **Proprietary Software License (All Rights Reserved)**. Anda **dilarang** menyalin, memodifikasi, mendistribusikan ulang, atau menjual kode ini tanpa izin tertulis dari pemilik hak cipta. Lihat [LICENSE](./LICENSE) untuk detail lengkap.

---

## 👨‍💻 Pengembang & Hak Cipta

Dirancang dan dikembangkan dengan penuh dedikasi oleh:
**[Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)](https://github.com/ardhikaxx)**
*Lead Software Architect & Maintainer*

> **Copyright (c) 2024 - 2026 Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx). All Rights Reserved.**
