# 🛡️ Kebijakan Keamanan (Security Policy)

Keamanan informasi dan kerahasiaan data pelanggan serta data operasional bisnis rental adalah prioritas utama dalam pengembangan sistem **Sistem Operasional Rental Mobil**.

Aplikasi ini dirancang dengan mematuhi prinsip pengembangan aman (secure development), **Undang-Undang Perlindungan Data Pribadi (UU PDP No. 27 Tahun 2022)**, serta standar enkripsi modern.

---

## 📦 Versi yang Didukung

Pembaruan keamanan dan perbaikan celah (*security patches*) aktif diberikan untuk versi berikut:

| Versi | Status Keamanan |
| :--- | :--- |
| **v1.0.x** | ✅ Didukung Penuh (*Active Security Support*) |
| < 1.0.0 | ❌ Tidak Didukung |

---

## 🔒 Lapisan Keamanan Bawaan (Built-in Security Layers)

1. **Autentikasi & Otorisasi RBAC**:
   * Multi-role authorization (*Super Admin, Admin Operasional, Staf Garasi*) menggunakan Laravel Gate & Policy.
   * Verifikasi izin akses (middleware `role`) pada setiap endpoint controller.
2. **Perlindungan Akun**:
   * Middleware `EnsureAccountActive` — akun yang dinonaktifkan langsung dikeluarkan dari sesi.
   * Password disimpan dengan hashing bcrypt, tidak pernah ditampilkan di mana pun.
3. **Perlindungan Injeksi & Serangan Web**:
   * Proteksi penuh dari serangan **CSRF** pada seluruh formulir.
   * Perlindungan **SQL Injection** menggunakan PDO Prepared Statements melalui Eloquent ORM.
   * Sanitasi data masukan dari serangan **XSS**.
4. **Audit Trail & Logging**:
   * Setiap aktivitas penting (login gagal, transaksi, pembayaran, perubahan pengguna) dicatat otomatis di tabel `audit_logs` dan `transaction_logs`.

---

## 🚨 Melaporkan Celah Keamanan (Reporting a Vulnerability)

Jika Anda menemukan kerentanan atau celah keamanan (*security vulnerability*) dalam sistem ini:

> [!CAUTION]
> **JANGAN** membuat laporan publik melalui GitHub Issues terbuka demi melindungi pengguna sistem.

Silakan kirimkan laporan celah keamanan secara privat dan bertanggung jawab (*Responsible Disclosure*) melalui:

* **Email Penanggung Jawab Keamanan**: `ardhikayanuar58@gmail.com`
* **Subjek Email**: `[SECURITY VULNERABILITY] - Rental Mobil`

### Informasi yang Wajib Disertakan:
1. Deskripsi mendalam mengenai celah keamanan yang ditemukan.
2. Langkah-langkah detail atau *proof-of-concept (PoC)* untuk mereproduksi masalah.
3. Estimasi potensi dampak terhadap data bisnis atau operasional rental.

Tim pengembang akan merespons laporan dalam waktu **1x24 jam** dan segera merilis perbaikan (*patch*).

---

Terima kasih atas kerja sama Anda dalam menjaga keamanan ekosistem digitalisasi bisnis rental Indonesia.
**Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**
