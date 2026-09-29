# 📋 Changelog

Semua perubahan penting dan riwayat pengembangan proyek **Sistem Operasional Rental Mobil** didokumentasikan dalam file ini.

Format pencatatan mengacu pada [Keep a Changelog](https://keepachangelog.com/id/1.0.0/) dan mematuhi [Semantic Versioning (SemVer)](https://semver.org/).

---

## [1.0.0] - 2026-09-29
### 🚀 Rilis Perdana (Initial Production Release)
Dikembangkan oleh **Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**.

### ✨ Added (Fitur Baru)
- **Autentikasi & RBAC**:
  - Login berbasis username dengan middleware `EnsureAccountActive` (akun nonaktif dipaksa logout).
  - Tiga role: Super Admin (Pemilik), Admin Operasional (Kasir), Staf Garasi — dikelola via Laravel Gate & Policy.
  - Toggle tampilkan/sembunyikan password (ikon mata) pada seluruh form password.
  - Audit log untuk aktivitas penting (login gagal, perubahan data, reset password).
- **Manajemen Kendaraan**:
  - Master data lengkap: kode unit, merek, model, tipe, tahun, warna, nomor polisi unik, nomor rangka & mesin.
  - Status kendaraan otomatis (tersedia, disewa, perawatan, nonaktif) via `VehicleStatusService`.
- **Manajemen Pelanggan**:
  - Data pelanggan dengan Nomor Identitas (KTP) unik, kontak, dan catatan internal.
- **Transaksi Sewa**:
  - Booking dengan validasi ketersediaan kendaraan (`VehicleAvailabilityService`) anti tumpang tindih.
  - Nomor transaksi otomatis (`RNT-YYYYMMDD-0001`) via `NumberGenerator`.
  - DP minimum dapat dikonfigurasi per persentase dari total.
  - Pembayaran bertahap multi metode via `PaymentService`.
  - Invoice siap cetak dengan kop perusahaan.
- **Serah Terima & Pengembalian**:
  - Form serah terima: odometer awal, bahan bakar, kondisi eksterior/interior/ban.
  - Pengembalian dengan preview denda keterlambatan real-time (per jam/per hari + masa tenggang) via `LateFeeCalculator`.
- **Pemeriksaan Kendaraan**:
  - Inspeksi berkala dengan upload hingga 5 foto dokumentasi per pemeriksaan.
- **Perawatan (Maintenance)**:
  - Pencatatan perawatan rutin & perbaikan dengan biaya dan status.
- **Kalender Ketersediaan**:
  - Kalender booking visual bulanan per kendaraan.
- **Laporan & Pengaturan**:
  - Laporan pendapatan untuk pemilik.
  - Pengaturan dinamis: identitas perusahaan, prefix nomor, DP minimum, aturan denda.
  - Halaman audit log khusus Super Admin.
- **Identitas Aplikasi**:
  - Nama aplikasi dan copyright author terpusat di `config/app.php` dan tampil di footer sidebar.
- **Dokumentasi**:
  - README lengkap (fitur, instalasi, akun demo) dengan QRIS donasi.
  - LICENSE proprietary, SECURITY.md, CONTRIBUTING.md, SUPPORT.md, CHANGELOG.md.

### 🔐 Security
- Proteksi CSRF penuh, Eloquent ORM (anti SQL Injection), sanitasi XSS.
- Password hashing bcrypt; data sensitif tidak pernah dikirim balik ke klien.

---

[1.0.0]: https://github.com/ardhikaxx/rental-mobil/releases/tag/v1.0.0
