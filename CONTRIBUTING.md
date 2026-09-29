# 🤝 Panduan Kontribusi (Contributing Guidelines)

Terima kasih atas minat Anda pada proyek **Sistem Operasional Rental Mobil**!

Aplikasi ini dikelola dan dirancang oleh **Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**. Karena proyek berlisensi **Proprietary (All Rights Reserved)**, kontribusi berlangsung dengan ketentuan sebagai berikut.

---

## 📌 Prinsip & Kebijakan Hak Cipta

1. **Kepemilikan Kode**: Proyek ini dilindungi hak cipta atas nama **Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**.
2. **Larangan Plagiarisme**: Dilarang keras menghapus watermark, metadata hak cipta, atau mengubah *author attribution* yang tertanam dalam sistem (lihat [LICENSE](./LICENSE)).
3. Setiap kontribusi kode yang diterima menjadi bagian dari basis kode proyek berlisensi resmi, dan hak ciptanya melekat pada pemilik proyek.
4. Kontribusi dilakukan melalui *pull request*; dengan mengirimkan kontribusi, Anda menyatakan bahwa kode tersebut adalah karya Anda sendiri dan Anda memberikannya kepada pemilik proyek.

---

## 🛠️ Standar Pengembangan Kode (Code Standards)

* **Bahasa & Framework**: PHP 8.4+ dan Laravel 13.x.
* **Standar Kode PHP**: Mematuhi **PSR-12** — jalankan `vendor/bin/pint --dirty` sebelum commit.
* **Struktur**: Ikuti struktur direktori dan konvensi yang sudah ada (Controller → FormRequest → Service → Model).
* **Keamanan**:
  * Semua endpoint wajib dilindungi middleware `auth` dan `role` sesuai matriks akses.
  * Validasi form menggunakan FormRequest, bukan validasi inline di controller.
  * Jangan pernah menyimpan data sensitif dalam bentuk plaintext.
* **Bahasa Antarmuka**: Seluruh label UI dan pesan validasi menggunakan **Bahasa Indonesia**.
* **Testing**: Setiap fitur baru wajib disertai test Pest (`php artisan make:test --pest`). Pastikan seluruh suite lolos: `php artisan test --compact`.

---

## 🌿 Alur Kerja Git (Git Workflow)

1. **Fork & Clone Repository**:
   ```bash
   git clone https://github.com/ardhikaxx/rental-mobil.git
   cd rental-mobil
   ```

2. **Buat Branch Fitur / Perbaikan**:
   * `feat/nama-fitur` (untuk fitur baru)
   * `fix/nama-bug` (untuk perbaikan bug)
   * `docs/nama-dokumentasi` (untuk dokumentasi)

   ```bash
   git checkout -b feat/modul-baru
   ```

3. **Format Commit Message**:
   Gunakan konvensi [Conventional Commits](https://www.conventionalcommits.org/) dengan deskripsi berbahasa Indonesia:
   * `feat: penambahan fitur transaksi cicilan`
   * `fix: perbaikan perhitungan denda keterlambatan`
   * `docs: perbaikan panduan instalasi`

4. **Kirim Pull Request**:
   * Satu PR berfokus pada satu perubahan logis.
   * Sertakan deskripsi perubahan, alasan, dan bukti test lolos.
   * PR akan ditinjau oleh maintainer sebelum digabungkan.

---

## 🐞 Melaporkan Bug

* Gunakan [GitHub Issues](https://github.com/ardhikaxx/rental-mobil/issues) dengan template yang tersedia.
* Sertakan versi PHP/Laravel, langkah reproduksi, dan perilaku yang diharapkan.
* Untuk celah keamanan, **jangan** gunakan Issues — ikuti [SECURITY.md](./SECURITY.md).

---

Terima kasih telah membantu proyek ini berkembang!

**Yanuar Ardhika Rahmadhani Ubaidillah (@ardhikaxx)**
