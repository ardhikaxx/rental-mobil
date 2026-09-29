<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * 100 pelanggan Jaya Trans Rental Mobil — mayoritas wilayah Jember dan
     * sekitarnya (Jawa Timur), sebagian dari luar kota. NIK 16 digit mengikuti
     * pola wilayah (35xxxx untuk Jawa Timur) dan memuat tanggal lahir pada
     * digit 7-12 sehingga konsisten dengan kolom birth_date.
     *
     * Nama, NIK, telepon, email, dan alamat bersifat sintetis (bukan data
     * pribadi orang nyata) namun mengikuti pola yang realistis.
     */
    public function run(): void
    {
        Customer::insert([
            [
                'id' => 1, 'name' => 'Rendra Bagus Prasetyo', 'id_number' => '3509211704850154', 'phone' => '081234917568', 'email' => 'rendra.prasetyo@gmail.com',
                'address' => 'Jl. Kalimantan No. 24, Sumbersari, Jember', 'birth_date' => '1985-04-17', 'notes' => 'Langganan perjalanan dinas sejak 2025.',
                'created_at' => '2025-01-14 10:20:00', 'updated_at' => '2026-03-11 09:12:00',
            ],
            [
                'id' => 2, 'name' => 'Wulan Anggraini', 'id_number' => '3509212308900261', 'phone' => '085736209184', 'email' => 'wulan.anggraini@gmail.com',
                'address' => 'Jl. Jawa No. 17, Tegal Boto, Sumbersari, Jember', 'birth_date' => '1990-08-23', 'notes' => null,
                'created_at' => '2025-01-21 14:35:00', 'updated_at' => '2025-01-21 14:35:00',
            ],
            [
                'id' => 3, 'name' => 'Hendrik Susanto', 'id_number' => '3509210512790087', 'phone' => '081357842690', 'email' => null,
                'address' => 'Jl. Raya Kalisat No. 118, Kalisat, Jember', 'birth_date' => '1979-12-05', 'notes' => 'Pelanggan walk-in, sering menyewa untuk acara keluarga.',
                'created_at' => '2025-02-03 09:48:00', 'updated_at' => '2026-01-19 10:20:00',
            ],
            [
                'id' => 4, 'name' => 'Maya Puspita Sari', 'id_number' => '3509311403940192', 'phone' => '081934267085', 'email' => 'maya.puspita14@gmail.com',
                'address' => 'Jl. Cendrawasih No. 6, Kaliwates, Jember', 'birth_date' => '1994-03-14', 'notes' => null,
                'created_at' => '2025-02-08 13:22:00', 'updated_at' => '2025-02-08 13:22:00',
            ],
            [
                'id' => 5, 'name' => 'Agung Wibowo', 'id_number' => '3509313007820245', 'phone' => '085848120763', 'email' => 'agung.wibowo@yahoo.co.id',
                'address' => 'Jl. Hayam Wuruk No. 201, Kaliwates, Jember', 'birth_date' => '1982-07-30', 'notes' => null,
                'created_at' => '2025-02-15 10:05:00', 'updated_at' => '2026-05-23 16:40:00',
            ],
            [
                'id' => 6, 'name' => 'Intan Nur Aini', 'id_number' => '3509211911960318', 'phone' => '082231705948', 'email' => 'intan.nurani@gmail.com',
                'address' => 'Jl. Letjen Suprapto No. 33, Sumbersari, Jember', 'birth_date' => '1996-11-19', 'notes' => 'Referensi dari pelanggan Rendra B. Prasetyo.',
                'created_at' => '2025-03-01 11:15:00', 'updated_at' => '2025-11-08 14:30:00',
            ],
            [
                'id' => 7, 'name' => 'Bayu Setiawan', 'id_number' => '3509310802880064', 'phone' => '081239857416', 'email' => 'bayu.setiawan88@gmail.com',
                'address' => 'Jl. Gajah Mada No. 152, Kaliwates, Jember', 'birth_date' => '1988-02-08', 'notes' => null,
                'created_at' => '2025-03-07 15:50:00', 'updated_at' => '2026-07-02 11:18:00',
            ],
            [
                'id' => 8, 'name' => 'Larasati Dewi Anggun', 'id_number' => '3509212706930427', 'phone' => '087760415239', 'email' => 'larasati.dewi@gmail.com',
                'address' => 'Jl. Mastrip No. 89, Sumbersari, Jember', 'birth_date' => '1993-06-27', 'notes' => 'Sering memesan untuk wisata Bromo dan Ijen.',
                'created_at' => '2025-03-12 08:40:00', 'updated_at' => '2026-08-14 09:55:00',
            ],
            [
                'id' => 9, 'name' => 'Fajar Sidiq Nugroho', 'id_number' => '3509211209870533', 'phone' => '081947630218', 'email' => null,
                'address' => 'Jl. Karimata No. 45, Sumbersari, Jember', 'birth_date' => '1987-09-12', 'notes' => 'Rekanan kampus; rutin sewa untuk kunjungan kerja.',
                'created_at' => '2025-03-24 10:30:00', 'updated_at' => '2026-06-30 08:25:00',
            ],
            [
                'id' => 10, 'name' => 'Ratih Kumala Sari', 'id_number' => '3509312501910148', 'phone' => '085259671340', 'email' => 'ratih.kumala@gmail.com',
                'address' => 'Jl. Sultan Agung No. 74, Kaliwates, Jember', 'birth_date' => '1991-01-25', 'notes' => null,
                'created_at' => '2025-04-02 13:05:00', 'updated_at' => '2025-12-27 15:12:00',
            ],
            [
                'id' => 11, 'name' => 'Doni Irawan', 'id_number' => '3509410305840219', 'phone' => '081335902176', 'email' => 'doni.irawan84@gmail.com',
                'address' => 'Jl. Manggar No. 12, Patrang, Jember', 'birth_date' => '1984-05-03', 'notes' => null,
                'created_at' => '2025-04-11 09:20:00', 'updated_at' => '2025-04-11 09:20:00',
            ],
            [
                'id' => 12, 'name' => 'Siska Amalia Putri', 'id_number' => '3509411610970376', 'phone' => '082168430795', 'email' => 'siska.amalia@gmail.com',
                'address' => 'Jl. Melati No. 21, Patrang, Jember', 'birth_date' => '1997-10-16', 'notes' => null,
                'created_at' => '2025-04-18 14:45:00', 'updated_at' => '2026-02-20 10:35:00',
            ],
            [
                'id' => 13, 'name' => 'Yoga Pratama Putra', 'id_number' => '3509412904950284', 'phone' => '081259378426', 'email' => 'yoga.pratama95@gmail.com',
                'address' => 'Jl. Urip Sumoharjo No. 58, Patrang, Jember', 'birth_date' => '1995-04-29', 'notes' => 'Sering antar-jemput keluarga di Bandara Juanda.',
                'created_at' => '2025-05-06 08:55:00', 'updated_at' => '2026-04-17 13:42:00',
            ],
            [
                'id' => 14, 'name' => 'Nurul Hidayah', 'id_number' => '3509212112890461', 'phone' => '085730196428', 'email' => 'nurul.hidayah@gmail.com',
                'address' => 'Jl. Kaliurang No. 103, Sumbersari, Jember', 'birth_date' => '1989-12-21', 'notes' => null,
                'created_at' => '2025-05-19 10:10:00', 'updated_at' => '2025-05-19 10:10:00',
            ],
            [
                'id' => 15, 'name' => 'Aris Munandar', 'id_number' => '3509220708800093', 'phone' => '081357420986', 'email' => null,
                'address' => 'Jl. Raya Pakusari No. 76, Pakusari, Jember', 'birth_date' => '1980-08-07', 'notes' => 'Menyewa unit MPV untuk keluarga besar.',
                'created_at' => '2025-05-27 09:30:00', 'updated_at' => '2026-07-23 15:05:00',
            ],
            [
                'id' => 16, 'name' => 'Tri Wahyuni', 'id_number' => '3509411105920173', 'phone' => '081336784052', 'email' => 'tri.wahyuni@gmail.com',
                'address' => 'Jl. Semangka No. 9, Patrang, Jember', 'birth_date' => '1992-05-11', 'notes' => null,
                'created_at' => '2025-06-03 13:40:00', 'updated_at' => '2025-06-03 13:40:00',
            ],
            [
                'id' => 17, 'name' => 'Rizky Ananda Pratama', 'id_number' => '3509210209980358', 'phone' => '082267915483', 'email' => 'rizky.ananda98@gmail.com',
                'address' => 'Jl. Riau No. 41, Sumbersari, Jember', 'birth_date' => '1998-09-02', 'notes' => null,
                'created_at' => '2025-06-11 10:25:00', 'updated_at' => '2026-09-04 11:47:00',
            ],
            [
                'id' => 18, 'name' => 'Endang Sulistyowati', 'id_number' => '3509312703750126', 'phone' => '085893417620', 'email' => 'endang.sulistyowati@yahoo.com',
                'address' => 'Jl. Nangka No. 27, Kaliwates, Jember', 'birth_date' => '1975-03-27', 'notes' => 'Menyewa beberapa unit untuk acara pernikahan keluarga.',
                'created_at' => '2025-06-20 14:55:00', 'updated_at' => '2026-06-12 10:08:00',
            ],
            [
                'id' => 19, 'name' => 'Gilang Ramadhan Saputra', 'id_number' => '3509210811990437', 'phone' => '081259630418', 'email' => 'gilang.ramadhan99@gmail.com',
                'address' => 'Jl. Teratai No. 15, Sumbersari, Jember', 'birth_date' => '1999-11-08', 'notes' => null,
                'created_at' => '2025-07-01 09:15:00', 'updated_at' => '2025-07-01 09:15:00',
            ],
            [
                'id' => 20, 'name' => 'Cahyo Purnomo', 'id_number' => '3509313001860294', 'phone' => '087881592034', 'email' => 'cahyo.purnomo@gmail.com',
                'address' => 'Jl. Kertanegara No. 62, Kaliwates, Jember', 'birth_date' => '1986-01-30', 'notes' => 'Perjalanan dinas rutin ke Surabaya.',
                'created_at' => '2025-07-14 08:20:00', 'updated_at' => '2026-08-28 16:33:00',
            ],
            [
                'id' => 21, 'name' => 'Dewi Ayu Lestari', 'id_number' => '3509311807930512', 'phone' => '082133570869', 'email' => 'dewi.ayulestari@gmail.com',
                'address' => 'Jl. Trunojoyo No. 88, Kaliwates, Jember', 'birth_date' => '1993-07-18', 'notes' => null,
                'created_at' => '2025-07-22 11:05:00', 'updated_at' => '2025-07-22 11:05:00',
            ],
            [
                'id' => 22, 'name' => 'Rudi Hartanto', 'id_number' => '3509212410780075', 'phone' => '081531279460', 'email' => null,
                'address' => 'Jl. Kalisat No. 39, Kalisat, Jember', 'birth_date' => '1978-10-24', 'notes' => 'Menyewa untuk mobilitas proyek di Bondowoso.',
                'created_at' => '2025-08-05 10:40:00', 'updated_at' => '2026-05-09 09:26:00',
            ],
            [
                'id' => 23, 'name' => 'Anisa Fitriani', 'id_number' => '3509311402960229', 'phone' => '085604823197', 'email' => 'anisa.fitriani@gmail.com',
                'address' => 'Jl. Argopuro No. 51, Kaliwates, Jember', 'birth_date' => '1996-02-14', 'notes' => null,
                'created_at' => '2025-08-19 15:10:00', 'updated_at' => '2026-03-27 13:55:00',
            ],
            [
                'id' => 24, 'name' => 'Surya Wijaya Kusuma', 'id_number' => '3509310906830341', 'phone' => '081217483560', 'email' => 'surya.wijaya@gmail.com',
                'address' => 'Jl. Gajah Mada No. 117, Kaliwates, Jember', 'birth_date' => '1983-06-09', 'notes' => 'Repeat order untuk kebutuhan operasional toko.',
                'created_at' => '2025-09-02 09:05:00', 'updated_at' => '2026-09-16 14:22:00',
            ],
            [
                'id' => 25, 'name' => 'Nur Aisyah Rahmawati', 'id_number' => '3509310604900387', 'phone' => '081946372051', 'email' => 'nur.aisyah@gmail.com',
                'address' => 'Jl. Diponegoro No. 143, Kaliwates, Jember', 'birth_date' => '1990-04-06', 'notes' => null,
                'created_at' => '2025-09-10 10:15:00', 'updated_at' => '2026-02-11 14:20:00',
            ],
            [
                'id' => 26, 'name' => 'Iwan Setiawan', 'id_number' => '3509312711810158', 'phone' => '085732619408', 'email' => 'iwan.setiawan81@gmail.com',
                'address' => 'Jl. Ahmad Yani No. 96, Kaliwates, Jember', 'birth_date' => '1981-11-27', 'notes' => 'Sewa mingguan untuk operasional ekspedisi.',
                'created_at' => '2025-09-18 08:50:00', 'updated_at' => '2026-07-31 09:05:00',
            ],
            [
                'id' => 27, 'name' => 'Putri Ayu Wulandari', 'id_number' => '3509210306970264', 'phone' => '081259147306', 'email' => 'putri.ayuw@gmail.com',
                'address' => 'Jl. Kenanga No. 34, Sumbersari, Jember', 'birth_date' => '1997-06-03', 'notes' => null,
                'created_at' => '2025-09-26 13:10:00', 'updated_at' => '2025-09-26 13:10:00',
            ],
            [
                'id' => 28, 'name' => 'Ahmad Fauzi', 'id_number' => '3509311409880471', 'phone' => '087762043915', 'email' => 'ahmad.fauzi88@gmail.com',
                'address' => 'Jl. KH. Wahid Hasyim No. 77, Kaliwates, Jember', 'birth_date' => '1988-09-14', 'notes' => 'Rutin menyewa untuk acara keluarga besar.',
                'created_at' => '2025-10-06 09:35:00', 'updated_at' => '2026-08-19 15:50:00',
            ],
            [
                'id' => 29, 'name' => 'Rina Marlina', 'id_number' => '3509230912940338', 'phone' => '081358974210', 'email' => 'rina.marlina@gmail.com',
                'address' => 'Jl. Raya Bangsalsari No. 55, Bangsalsari, Jember', 'birth_date' => '1994-12-09', 'notes' => null,
                'created_at' => '2025-10-15 11:40:00', 'updated_at' => '2026-06-04 10:22:00',
            ],
            [
                'id' => 30, 'name' => 'Dedi Kurniawan', 'id_number' => '3509242107860192', 'phone' => '082146739058', 'email' => 'dedi.kurniawan@gmail.com',
                'address' => 'Jl. Raya Rambipuji No. 82, Rambipuji, Jember', 'birth_date' => '1986-07-21', 'notes' => 'Menyewa untuk operasional toko bangunan.',
                'created_at' => '2025-10-24 08:30:00', 'updated_at' => '2026-09-08 11:15:00',
            ],
            [
                'id' => 31, 'name' => 'Sri Wahyuni Ningsih', 'id_number' => '3509313005790082', 'phone' => '085840127963', 'email' => null,
                'address' => 'Jl. Pandan No. 18, Kaliwates, Jember', 'birth_date' => '1979-05-30', 'notes' => null,
                'created_at' => '2025-11-03 10:05:00', 'updated_at' => '2025-11-03 10:05:00',
            ],
            [
                'id' => 32, 'name' => 'Rizal Maulana Akbar', 'id_number' => '3509211708920415', 'phone' => '081337940218', 'email' => 'rizal.maulana@gmail.com',
                'address' => 'Jl. Brantas No. 63, Sumbersari, Jember', 'birth_date' => '1992-08-17', 'notes' => 'Pengguna rutin paket sewa 3 hari.',
                'created_at' => '2025-11-12 14:25:00', 'updated_at' => '2026-09-12 09:48:00',
            ],
            [
                'id' => 33, 'name' => 'Ayu Lestari Ningsih', 'id_number' => '3509411101980249', 'phone' => '082268540713', 'email' => 'ayu.lestari98@gmail.com',
                'address' => 'Jl. Seruni No. 7, Patrang, Jember', 'birth_date' => '1998-01-11', 'notes' => null,
                'created_at' => '2025-11-21 09:15:00', 'updated_at' => '2026-05-16 13:05:00',
            ],
            [
                'id' => 34, 'name' => 'Bimo Adi Nugroho', 'id_number' => '3509210503910362', 'phone' => '085890634172', 'email' => 'bimo.nugroho@gmail.com',
                'address' => 'Jl. Bengawan Solo No. 29, Sumbersari, Jember', 'birth_date' => '1991-03-05', 'notes' => null,
                'created_at' => '2025-12-02 10:50:00', 'updated_at' => '2026-08-06 12:18:00',
            ],
            [
                'id' => 35, 'name' => 'Fitria Handayani', 'id_number' => '3509310210950417', 'phone' => '081236790485', 'email' => 'fitria.handayani@gmail.com',
                'address' => 'Jl. Sutoyo No. 112, Kaliwates, Jember', 'birth_date' => '1995-10-02', 'notes' => 'Sering sewa untuk kunjungan keluarga di Yogyakarta.',
                'created_at' => '2025-12-11 15:35:00', 'updated_at' => '2026-09-02 10:30:00',
            ],
            [
                'id' => 36, 'name' => 'Yudi Prasetyo', 'id_number' => '3509252812830168', 'phone' => '081537042896', 'email' => 'yudi.prasetyo@gmail.com',
                'address' => 'Jl. Raya Arjasa No. 47, Arjasa, Jember', 'birth_date' => '1983-12-28', 'notes' => null,
                'created_at' => '2025-12-19 09:40:00', 'updated_at' => '2025-12-19 09:40:00',
            ],
            [
                'id' => 37, 'name' => 'Muhammad Iqbal Ramadhan', 'id_number' => '3509212212950374', 'phone' => '081338762904', 'email' => 'iqbal.ramadhan95@gmail.com',
                'address' => 'Jl. Imam Bonjol No. 8, Kaliwates, Jember', 'birth_date' => '1995-12-22', 'notes' => null,
                'created_at' => '2026-01-07 09:25:00', 'updated_at' => '2026-09-19 15:40:00',
            ],
            [
                'id' => 38, 'name' => 'Kartika Sari Dewi', 'id_number' => '3509410408820196', 'phone' => '085733842510', 'email' => 'kartika.saridewi@gmail.com',
                'address' => 'Jl. WR Supratman No. 26, Patrang, Jember', 'birth_date' => '1982-08-04', 'notes' => 'Menyewa untuk acara wisuda anak.',
                'created_at' => '2026-01-15 11:10:00', 'updated_at' => '2026-08-25 10:05:00',
            ],
            [
                'id' => 39, 'name' => 'Bagus Pramudya Wardhana', 'id_number' => '3509311701900293', 'phone' => '082146075839', 'email' => 'bagus.pramudya@gmail.com',
                'address' => 'Jl. Sumatra No. 91, Kaliwates, Jember', 'birth_date' => '1990-01-17', 'notes' => null,
                'created_at' => '2026-01-22 14:40:00', 'updated_at' => '2026-07-09 13:26:00',
            ],
            [
                'id' => 40, 'name' => 'Silvia Rossa Andini', 'id_number' => '3509411204990257', 'phone' => '081259730648', 'email' => 'silvia.andini@gmail.com',
                'address' => 'Jl. Gajah Mada No. 41, Kaliwates, Jember', 'birth_date' => '1999-04-12', 'notes' => null,
                'created_at' => '2026-02-02 09:50:00', 'updated_at' => '2026-09-27 11:15:00',
            ],
            [
                'id' => 41, 'name' => 'Taufik Hidayat', 'id_number' => '3509210506850218', 'phone' => '085895617043', 'email' => 'taufik.hidayat85@gmail.com',
                'address' => 'Jl. Empu Tantular No. 54, Sumbersari, Jember', 'birth_date' => '1985-06-05', 'notes' => 'Pelanggan lama, menyewa untuk mudik.',
                'created_at' => '2026-02-11 10:35:00', 'updated_at' => '2026-09-21 09:30:00',
            ],
            [
                'id' => 42, 'name' => 'Nanda Ayu Pertiwi', 'id_number' => '3509313009970364', 'phone' => '082269104857', 'email' => 'nanda.ayu@gmail.com',
                'address' => 'Jl. Pemuda No. 19, Kaliwates, Jember', 'birth_date' => '1997-09-30', 'notes' => null,
                'created_at' => '2026-02-19 15:20:00', 'updated_at' => '2026-06-13 14:05:00',
            ],
            [
                'id' => 43, 'name' => 'Eko Prasetyo Wibisono', 'id_number' => '3509312107840301', 'phone' => '081537826094', 'email' => 'eko.wibisono84@gmail.com',
                'address' => 'Jl. Letjen Panjaitan No. 88, Kaliwates, Jember', 'birth_date' => '1984-07-21', 'notes' => null,
                'created_at' => '2026-03-03 08:45:00', 'updated_at' => '2026-03-03 08:45:00',
            ],
            [
                'id' => 44, 'name' => 'Melati Puspa Anggraini', 'id_number' => '3509210603930481', 'phone' => '081947380256', 'email' => 'melati.puspa@gmail.com',
                'address' => 'Jl. Diponegoro No. 210, Kaliwates, Jember', 'birth_date' => '1993-03-06', 'notes' => 'Menyewa untuk pernikahan saudara di Situbondo.',
                'created_at' => '2026-03-12 13:55:00', 'updated_at' => '2026-08-03 10:48:00',
            ],
            [
                'id' => 45, 'name' => 'Rangga Adi Saputra', 'id_number' => '3509412806910254', 'phone' => '085842037915', 'email' => 'rangga.adi@gmail.com',
                'address' => 'Jl. Kalimantan No. 77, Sumbersari, Jember', 'birth_date' => '1991-06-28', 'notes' => null,
                'created_at' => '2026-03-20 10:05:00', 'updated_at' => '2026-09-10 08:35:00',
            ],
            [
                'id' => 46, 'name' => 'Lia Kartika Wulandari', 'id_number' => '3509312402950173', 'phone' => '081336205984', 'email' => 'lia.kartika@gmail.com',
                'address' => 'Jl. Srikoyo No. 63, Patrang, Jember', 'birth_date' => '1995-02-24', 'notes' => null,
                'created_at' => '2026-03-29 09:15:00', 'updated_at' => '2026-03-29 09:15:00',
            ],
            [
                'id' => 47, 'name' => 'Hendra Setiawan Putra', 'id_number' => '3509311412830368', 'phone' => '082231947605', 'email' => 'hendra.setiawan83@gmail.com',
                'address' => 'Jl. Ahmad Yani No. 204, Kaliwates, Jember', 'birth_date' => '1983-12-14', 'notes' => 'Sering sewa untuk kunjungan proyek di Malang.',
                'created_at' => '2026-04-06 11:25:00', 'updated_at' => '2026-09-24 16:12:00',
            ],
            [
                'id' => 48, 'name' => 'Yuni Astuti', 'id_number' => '3509211606880425', 'phone' => '081358407926', 'email' => 'yuni.astuti@gmail.com',
                'address' => 'Jl. Moch. Seruji No. 35, Kaliwates, Jember', 'birth_date' => '1988-06-16', 'notes' => null,
                'created_at' => '2026-04-14 14:30:00', 'updated_at' => '2026-07-21 09:40:00',
            ],
            [
                'id' => 49, 'name' => 'Wahyu Setiadi', 'id_number' => '3509210204780159', 'phone' => '085896304172', 'email' => 'wahyu.setiadi78@gmail.com',
                'address' => 'Jl. Cendrawasih No. 48, Kaliwates, Jember', 'birth_date' => '1978-04-02', 'notes' => 'Menyewa unit SUV untuk perjalanan ke Bromo.',
                'created_at' => '2026-04-23 09:35:00', 'updated_at' => '2026-09-05 11:05:00',
            ],
            [
                'id' => 50, 'name' => 'Desi Ratnasari', 'id_number' => '3509410309890261', 'phone' => '081259647083', 'email' => 'desi.ratnasari@gmail.com',
                'address' => 'Jl. Semeru No. 23, Patrang, Jember', 'birth_date' => '1989-09-03', 'notes' => null,
                'created_at' => '2026-05-05 14:15:00', 'updated_at' => '2026-08-11 13:40:00',
            ],
            [
                'id' => 51, 'name' => 'Yoga Adi Nugraha', 'id_number' => '3509212907940317', 'phone' => '082146803975', 'email' => 'yoga.adinugraha@gmail.com',
                'address' => 'Jl. Bromo No. 60, Sumbersari, Jember', 'birth_date' => '1994-07-29', 'notes' => null,
                'created_at' => '2026-05-13 10:05:00', 'updated_at' => '2026-05-13 10:05:00',
            ],
            [
                'id' => 52, 'name' => 'Putra Bagaskara', 'id_number' => '3509311610850358', 'phone' => '081338507924', 'email' => 'putra.bagaskara@gmail.com',
                'address' => 'Jl. Sultan Agung No. 118, Kaliwates, Jember', 'birth_date' => '1985-10-16', 'notes' => 'Menyewa untuk kunjungan vendor di Surabaya.',
                'created_at' => '2026-05-21 08:55:00', 'updated_at' => '2026-07-14 15:20:00',
            ],
            [
                'id' => 53, 'name' => 'Fitriani Anggraeni', 'id_number' => '3509410702940185', 'phone' => '085842906137', 'email' => 'fitriani.anggraeni@gmail.com',
                'address' => 'Jl. Papandayan No. 31, Patrang, Jember', 'birth_date' => '1994-02-07', 'notes' => null,
                'created_at' => '2026-05-29 11:40:00', 'updated_at' => '2026-09-23 09:50:00',
            ],
            [
                'id' => 54, 'name' => 'Ardian Wicaksono', 'id_number' => '3509311106920418', 'phone' => '087762518304', 'email' => 'ardian.wicaksono@gmail.com',
                'address' => 'Jl. Kertanegara No. 96, Kaliwates, Jember', 'birth_date' => '1992-06-11', 'notes' => null,
                'created_at' => '2026-06-04 09:20:00', 'updated_at' => '2026-06-04 09:20:00',
            ],
            [
                'id' => 55, 'name' => 'Nur Lailatul Badriyah', 'id_number' => '3509221501870273', 'phone' => '081356798204', 'email' => 'nur.lailatul@gmail.com',
                'address' => 'Jl. Raya Mayang No. 12, Mayang, Jember', 'birth_date' => '1987-01-15', 'notes' => 'Menyewa untuk haul keluarga besar.',
                'created_at' => '2026-06-12 13:30:00', 'updated_at' => '2026-09-18 10:25:00',
            ],
            [
                'id' => 56, 'name' => 'Dian Permatasari', 'id_number' => '3509310303980149', 'phone' => '085604917238', 'email' => 'dian.permatasari@gmail.com',
                'address' => 'Jl. Mangga No. 57, Kaliwates, Jember', 'birth_date' => '1998-03-03', 'notes' => null,
                'created_at' => '2026-06-20 10:45:00', 'updated_at' => '2026-06-20 10:45:00',
            ],
            [
                'id' => 57, 'name' => 'Slamet Riyadi', 'id_number' => '3509211207720084', 'phone' => '081537024896', 'email' => null,
                'address' => 'Jl. Raya Semboro No. 64, Semboro, Jember', 'birth_date' => '1972-07-12', 'notes' => 'Pelanggan senior; menyewa untuk acara keluarga.',
                'created_at' => '2026-06-28 09:05:00', 'updated_at' => '2026-09-01 14:35:00',
            ],
            [
                'id' => 58, 'name' => 'Indra Gunawan', 'id_number' => '3509312309840267', 'phone' => '082267804139', 'email' => 'indra.gunawan84@gmail.com',
                'address' => 'Jl. Yos Sudarso No. 79, Kaliwates, Jember', 'birth_date' => '1984-09-23', 'notes' => null,
                'created_at' => '2026-07-07 14:20:00', 'updated_at' => '2026-09-26 11:40:00',
            ],
            [
                'id' => 59, 'name' => 'Vina Nurviana', 'id_number' => '3509412111960352', 'phone' => '081947520683', 'email' => 'vina.nurviana@gmail.com',
                'address' => 'Jl. Ijen No. 14, Patrang, Jember', 'birth_date' => '1996-11-21', 'notes' => null,
                'created_at' => '2026-07-15 10:15:00', 'updated_at' => '2026-07-15 10:15:00',
            ],
            [
                'id' => 60, 'name' => 'Rizal Fadillah', 'id_number' => '3509212508000413', 'phone' => '085731962408', 'email' => 'rizal.fadillah@gmail.com',
                'address' => 'Jl. Tidar No. 38, Sumbersari, Jember', 'birth_date' => '2000-08-25', 'notes' => 'Menyewa untuk perjalanan wisata bersama komunitas.',
                'created_at' => '2026-07-23 13:50:00', 'updated_at' => '2026-09-29 08:20:00',
            ],
            [
                'id' => 61, 'name' => 'Hartono Wijaya', 'id_number' => '3509310906770104', 'phone' => '081357698204', 'email' => 'hartono.wijaya@gmail.com',
                'address' => 'Jl. Kamboja No. 52, Kaliwates, Jember', 'birth_date' => '1977-06-09', 'notes' => 'Menyewa untuk kebutuhan perjalanan bisnis.',
                'created_at' => '2026-08-03 09:40:00', 'updated_at' => '2026-09-22 10:15:00',
            ],
            [
                'id' => 62, 'name' => 'Sekar Ayu Pramesti', 'id_number' => '3509411802000327', 'phone' => '085842691307', 'email' => 'sekar.ayu@gmail.com',
                'address' => 'Jl. Sumatera No. 27, Patrang, Jember', 'birth_date' => '2000-02-18', 'notes' => null,
                'created_at' => '2026-08-11 13:25:00', 'updated_at' => '2026-08-11 13:25:00',
            ],
            [
                'id' => 63, 'name' => 'Agus Setiono', 'id_number' => '3509212008800236', 'phone' => '082146370958', 'email' => null,
                'address' => 'Jl. Raya Jenggawah No. 91, Jenggawah, Jember', 'birth_date' => '1980-08-20', 'notes' => 'Pelanggan pick-up untuk pasar.',
                'created_at' => '2026-08-19 08:30:00', 'updated_at' => '2026-09-27 09:05:00',
            ],
            [
                'id' => 64, 'name' => 'Novita Sari', 'id_number' => '3509311511920473', 'phone' => '081259408136', 'email' => 'novita.sari@gmail.com',
                'address' => 'Jl. Cempaka No. 44, Kaliwates, Jember', 'birth_date' => '1992-11-15', 'notes' => null,
                'created_at' => '2026-08-27 14:50:00', 'updated_at' => '2026-08-27 14:50:00',
            ],
            [
                'id' => 65, 'name' => 'Firman Hidayatullah', 'id_number' => '3509412609930185', 'phone' => '085730518462', 'email' => 'firman.hidayatullah@gmail.com',
                'address' => 'Jl. Letjen S. Parman No. 73, Patrang, Jember', 'birth_date' => '1993-09-26', 'notes' => null,
                'created_at' => '2026-09-02 10:20:00', 'updated_at' => '2026-09-02 10:20:00',
            ],
            [
                'id' => 66, 'name' => 'Ratna Dewi Safitri', 'id_number' => '3509211206950358', 'phone' => '081338429075', 'email' => 'ratna.dewi@gmail.com',
                'address' => 'Jl. Mangunsarkoro No. 16, Sumbersari, Jember', 'birth_date' => '1995-06-12', 'notes' => 'Menyewa untuk keperluan wisuda.',
                'created_at' => '2026-09-07 09:35:00', 'updated_at' => '2026-09-28 11:50:00',
            ],
            [
                'id' => 67, 'name' => 'Andika Pratama Yudha', 'id_number' => '3509310410900264', 'phone' => '081947385026', 'email' => 'andika.pratama@gmail.com',
                'address' => 'Jl. Sudarman No. 85, Kaliwates, Jember', 'birth_date' => '1990-10-04', 'notes' => null,
                'created_at' => '2026-09-09 13:15:00', 'updated_at' => '2026-09-29 08:45:00',
            ],
            [
                'id' => 68, 'name' => 'Luluk Munawaroh', 'id_number' => '3509222711880196', 'phone' => '085893172046', 'email' => 'luluk.munawaroh@gmail.com',
                'address' => 'Jl. Raya Kaliwates No. 122, Kaliwates, Jember', 'birth_date' => '1988-11-27', 'notes' => null,
                'created_at' => '2026-09-11 10:05:00', 'updated_at' => '2026-09-11 10:05:00',
            ],
            [
                'id' => 69, 'name' => 'Candra Kusuma', 'id_number' => '3509211402990431', 'phone' => '081356820794', 'email' => 'candra.kusuma@gmail.com',
                'address' => 'Jl. Kalimantan No. 146, Sumbersari, Jember', 'birth_date' => '1999-02-14', 'notes' => 'Booking mendatang untuk liburan akhir tahun.',
                'created_at' => '2026-09-14 15:40:00', 'updated_at' => '2026-09-27 09:20:00',
            ],
            [
                'id' => 70, 'name' => 'Elsa Novianti', 'id_number' => '3509412911980273', 'phone' => '082268904137', 'email' => 'elsa.novianti@gmail.com',
                'address' => 'Jl. Argopuro No. 8, Patrang, Jember', 'birth_date' => '1998-11-29', 'notes' => null,
                'created_at' => '2026-09-16 09:50:00', 'updated_at' => '2026-09-16 09:50:00',
            ],
            [
                'id' => 71, 'name' => 'Sugeng Riyanto', 'id_number' => '3509311806740087', 'phone' => '081537284096', 'email' => null,
                'address' => 'Jl. Brawijaya No. 39, Kaliwates, Jember', 'birth_date' => '1974-06-18', 'notes' => 'Menyewa untuk acara tasyakuran.',
                'created_at' => '2026-09-18 10:30:00', 'updated_at' => '2026-09-29 09:10:00',
            ],
            [
                'id' => 72, 'name' => 'Kurnia Sari Wulandari', 'id_number' => '3509210707930385', 'phone' => '087762419058', 'email' => 'kurnia.sari@gmail.com',
                'address' => 'Jl. Jawa No. 61, Sumbersari, Jember', 'birth_date' => '1993-07-07', 'notes' => null,
                'created_at' => '2026-09-21 11:15:00', 'updated_at' => '2026-09-28 14:30:00',
            ],
            [
                'id' => 73, 'name' => 'Ferry Gunawan', 'id_number' => '3509211205840019', 'phone' => '081235498120', 'email' => 'ferry.gunawan@gmail.com',
                'address' => 'Jl. Trunojoyo No. 45, Kepatihan, Kaliwates, Jember', 'birth_date' => '1984-05-12', 'notes' => 'Pelanggan sewa mingguan untuk distribusi barang.',
                'created_at' => '2026-01-12 09:20:00', 'updated_at' => '2026-01-12 09:20:00',
            ],
            [
                'id' => 74, 'name' => 'Yunita Indah Permata', 'id_number' => '3509314809920042', 'phone' => '085748912304', 'email' => 'yunita.indah@yahoo.com',
                'address' => 'Jl. Mastrip Timur No. 18, Sumbersari, Jember', 'birth_date' => '1992-09-08', 'notes' => null,
                'created_at' => '2026-01-18 14:10:00', 'updated_at' => '2026-01-18 14:10:00',
            ],
            [
                'id' => 75, 'name' => 'Agus Setiawan', 'id_number' => '3509212508780071', 'phone' => '081334589201', 'email' => null,
                'address' => 'Jl. PB Sudirman No. 89, Patrang, Jember', 'birth_date' => '1978-08-25', 'notes' => 'Sering menyewa mobil keluarga untuk rombongan.',
                'created_at' => '2026-02-02 11:00:00', 'updated_at' => '2026-02-02 11:00:00',
            ],
            [
                'id' => 76, 'name' => 'Dewi Anggun Lestari', 'id_number' => '3509415603950028', 'phone' => '087859402135', 'email' => 'dewi.anggun@gmail.com',
                'address' => 'Perum Bumi Mangli Permai Blok C-12, Kaliwates, Jember', 'birth_date' => '1995-03-16', 'notes' => null,
                'created_at' => '2026-02-10 10:15:00', 'updated_at' => '2026-02-10 10:15:00',
            ],
            [
                'id' => 77, 'name' => 'Bambang Triyono', 'id_number' => '3509210311720054', 'phone' => '082143765980', 'email' => null,
                'address' => 'Jl. Hayam Wuruk No. 112, Sempusari, Kaliwates, Jember', 'birth_date' => '1972-11-03', 'notes' => 'Kontraktor proyek lokal.',
                'created_at' => '2026-02-15 08:45:00', 'updated_at' => '2026-02-15 08:45:00',
            ],
            [
                'id' => 78, 'name' => 'Maya Silvia Rahmadani', 'id_number' => '3509216407960015', 'phone' => '089678432109', 'email' => 'maya.silvia@gmail.com',
                'address' => 'Jl. Letjen Panjaitan No. 34, Sumbersari, Jember', 'birth_date' => '1996-07-24', 'notes' => null,
                'created_at' => '2026-02-22 13:30:00', 'updated_at' => '2026-02-22 13:30:00',
            ],
            [
                'id' => 79, 'name' => 'Rahmat Hidayatullah', 'id_number' => '3509311904880092', 'phone' => '081231987456', 'email' => 'rahmat.hidayatullah@gmail.com',
                'address' => 'Jl. Gajah Mada No. 195, Kaliwates, Jember', 'birth_date' => '1988-04-19', 'notes' => 'Perjalanan dinas instansi kesehatan.',
                'created_at' => '2026-03-01 09:00:00', 'updated_at' => '2026-03-01 09:00:00',
            ],
            [
                'id' => 80, 'name' => 'Fitria Dian Safitri', 'id_number' => '3509214812970037', 'phone' => '085852109438', 'email' => 'fitria.dian@gmail.com',
                'address' => 'Jl. Riau No. 14, Sumbersari, Jember', 'birth_date' => '1997-12-08', 'notes' => null,
                'created_at' => '2026-03-08 15:20:00', 'updated_at' => '2026-03-08 15:20:00',
            ],
            [
                'id' => 81, 'name' => 'Hendra Setiawan', 'id_number' => '3509211506860049', 'phone' => '081336789012', 'email' => 'hendra.setiawan86@gmail.com',
                'address' => 'Jl. Danau Toba No. 56, Tegalgede, Sumbersari, Jember', 'birth_date' => '1986-06-15', 'notes' => null,
                'created_at' => '2026-03-15 11:10:00', 'updated_at' => '2026-03-15 11:10:00',
            ],
            [
                'id' => 82, 'name' => 'Siti Aminah', 'id_number' => '3509316201800063', 'phone' => '087756891234', 'email' => null,
                'address' => 'Jl. Teuku Umar No. 72, Tegal Besar, Kaliwates, Jember', 'birth_date' => '1980-01-22', 'notes' => 'Sewa untuk acara arisan keluarga.',
                'created_at' => '2026-03-22 14:00:00', 'updated_at' => '2026-03-22 14:00:00',
            ],
            [
                'id' => 83, 'name' => 'Dwi Wahyu Nugroho', 'id_number' => '3509210909900085', 'phone' => '082234190875', 'email' => 'dwi.wahyu@yahoo.com',
                'address' => 'Jl. Bangka No. 27, Sumbersari, Jember', 'birth_date' => '1990-09-09', 'notes' => null,
                'created_at' => '2026-04-01 10:30:00', 'updated_at' => '2026-04-01 10:30:00',
            ],
            [
                'id' => 84, 'name' => 'Anisa Rahmawati', 'id_number' => '3509415105940018', 'phone' => '081938475620', 'email' => 'anisa.rahmawati@gmail.com',
                'address' => 'Jl. Bedadung No. 9, Patrang, Jember', 'birth_date' => '1994-05-11', 'notes' => null,
                'created_at' => '2026-04-08 09:40:00', 'updated_at' => '2026-04-08 09:40:00',
            ],
            [
                'id' => 85, 'name' => 'Teguh Prasetya', 'id_number' => '3509212702830062', 'phone' => '081232847591', 'email' => 'teguh.prasetya@gmail.com',
                'address' => 'Jl. Karimata No. 68, Sumbersari, Jember', 'birth_date' => '1983-02-27', 'notes' => 'Pengusaha garmen lokal.',
                'created_at' => '2026-04-14 16:15:00', 'updated_at' => '2026-04-14 16:15:00',
            ],
            [
                'id' => 86, 'name' => 'Nurmala Sari', 'id_number' => '3509316710910034', 'phone' => '085739281045', 'email' => null,
                'address' => 'Jl. Imam Bonjol No. 41, Kaliwates, Jember', 'birth_date' => '1991-10-27', 'notes' => null,
                'created_at' => '2026-04-20 11:25:00', 'updated_at' => '2026-04-20 11:25:00',
            ],
            [
                'id' => 87, 'name' => 'Rizki Aditya Pratama', 'id_number' => '3509211408980076', 'phone' => '089534217890', 'email' => 'rizki.aditya98@gmail.com',
                'address' => 'Jl. Nias No. 12, Sumbersari, Jember', 'birth_date' => '1998-08-14', 'notes' => 'Mahasiswa pascasarjana UNEJ.',
                'created_at' => '2026-04-28 13:45:00', 'updated_at' => '2026-04-28 13:45:00',
            ],
            [
                'id' => 88, 'name' => 'Endang Sulistowati', 'id_number' => '3509414506750051', 'phone' => '081335987124', 'email' => 'endang.sulisto@gmail.com',
                'address' => 'Jl. Nusa Indah No. 19, Patrang, Jember', 'birth_date' => '1975-06-05', 'notes' => null,
                'created_at' => '2026-05-05 08:30:00', 'updated_at' => '2026-05-05 08:30:00',
            ],
            [
                'id' => 89, 'name' => 'Wahyu Hidayat', 'id_number' => '3509211807870029', 'phone' => '087854129863', 'email' => 'wahyu.hidayat@yahoo.com',
                'address' => 'Jl. Semeru No. 83, Sumbersari, Jember', 'birth_date' => '1987-07-18', 'notes' => 'Sewa mobil untuk perjalanan dinas surveyor.',
                'created_at' => '2026-05-12 10:00:00', 'updated_at' => '2026-05-12 10:00:00',
            ],
            [
                'id' => 90, 'name' => 'Lestari Handayani', 'id_number' => '3509315904890048', 'phone' => '082142987351', 'email' => null,
                'address' => 'Jl. Kenanga No. 31, Gebang, Patrang, Jember', 'birth_date' => '1989-04-19', 'notes' => null,
                'created_at' => '2026-05-19 14:20:00', 'updated_at' => '2026-05-19 14:20:00',
            ],
            [
                'id' => 91, 'name' => 'Aris Munandar', 'id_number' => '3509210612810093', 'phone' => '081233984512', 'email' => 'aris.munandar@gmail.com',
                'address' => 'Jl. Madura No. 15, Sumbersari, Jember', 'birth_date' => '1981-12-06', 'notes' => null,
                'created_at' => '2026-05-26 09:15:00', 'updated_at' => '2026-05-26 09:15:00',
            ],
            [
                'id' => 92, 'name' => 'Kusuma Wardhana', 'id_number' => '3509412103850067', 'phone' => '085731984260', 'email' => 'kusuma.wardhana@gmail.com',
                'address' => 'Perum Taman Gading Blok H-5, Kaliwates, Jember', 'birth_date' => '1985-03-21', 'notes' => 'Sering menyewa untuk acara reuni keluarga.',
                'created_at' => '2026-06-02 11:30:00', 'updated_at' => '2026-06-02 11:30:00',
            ],
            [
                'id' => 93, 'name' => 'Ratnawati Soebandono', 'id_number' => '3509214708770014', 'phone' => '081338271945', 'email' => null,
                'address' => 'Jl. Dr. Soebandi No. 44, Patrang, Jember', 'birth_date' => '1977-08-07', 'notes' => null,
                'created_at' => '2026-06-09 15:10:00', 'updated_at' => '2026-06-09 15:10:00',
            ],
            [
                'id' => 94, 'name' => 'Eko Purnomo', 'id_number' => '3509312901820058', 'phone' => '089675412980', 'email' => 'eko.purnomo@gmail.com',
                'address' => 'Jl. KH Shiddiq No. 92, Kaliwates, Jember', 'birth_date' => '1982-01-29', 'notes' => 'Konsultan agribisnis.',
                'created_at' => '2026-06-16 08:50:00', 'updated_at' => '2026-06-16 08:50:00',
            ],
            [
                'id' => 95, 'name' => 'Novita Anggraeni', 'id_number' => '3509216311930032', 'phone' => '082236598124', 'email' => 'novita.anggraeni@gmail.com',
                'address' => 'Jl. Letjen Suprapto No. 58, Kebonsari, Sumbersari, Jember', 'birth_date' => '1993-11-23', 'notes' => null,
                'created_at' => '2026-06-23 13:40:00', 'updated_at' => '2026-06-23 13:40:00',
            ],
            [
                'id' => 96, 'name' => 'Danang Kusumo', 'id_number' => '3509411604890075', 'phone' => '087754981236', 'email' => 'danang.kusumo@yahoo.com',
                'address' => 'Jl. Cempaka No. 22, Gebang, Patrang, Jember', 'birth_date' => '1989-04-16', 'notes' => null,
                'created_at' => '2026-07-01 10:15:00', 'updated_at' => '2026-07-01 10:15:00',
            ],
            [
                'id' => 97, 'name' => 'Rina Marlina', 'id_number' => '3509215202860081', 'phone' => '081235897412', 'email' => 'rina.marlina@gmail.com',
                'address' => 'Jl. Bengawan Solo No. 37, Sumbersari, Jember', 'birth_date' => '1986-02-12', 'notes' => null,
                'created_at' => '2026-07-08 14:00:00', 'updated_at' => '2026-07-08 14:00:00',
            ],
            [
                'id' => 98, 'name' => 'Surya Saputra', 'id_number' => '3509310810940026', 'phone' => '085854192837', 'email' => 'surya.saputra@gmail.com',
                'address' => 'Jl. Wolter Monginsidi No. 104, Kaliwates, Jember', 'birth_date' => '1994-10-08', 'notes' => 'Fotografer freelance, sering sewa luar kota.',
                'created_at' => '2026-07-15 09:20:00', 'updated_at' => '2026-07-15 09:20:00',
            ],
            [
                'id' => 99, 'name' => 'Tri Handayani', 'id_number' => '3509214405790043', 'phone' => '081339847520', 'email' => null,
                'address' => 'Jl. Kaliurang No. 51, Sumbersari, Jember', 'birth_date' => '1979-05-04', 'notes' => null,
                'created_at' => '2026-07-22 11:45:00', 'updated_at' => '2026-07-22 11:45:00',
            ],
            [
                'id' => 100, 'name' => 'Achmad Fauzan', 'id_number' => '3509411909870098', 'phone' => '089532187654', 'email' => 'achmad.fauzan@gmail.com',
                'address' => 'Jl. Slamet Riyadi No. 80, Baratan, Patrang, Jember', 'birth_date' => '1987-09-19', 'notes' => 'Dosen FEB UNEJ.',
                'created_at' => '2026-07-29 16:30:00', 'updated_at' => '2026-07-29 16:30:00',
            ],
            [
                'id' => 101, 'name' => 'Wahyuni Agustina', 'id_number' => '3509216808910017', 'phone' => '082145892134', 'email' => 'wahyuni.agustina@gmail.com',
                'address' => 'Jl. PB Sudirman No. 42, Wirolegi, Sumbersari, Jember', 'birth_date' => '1991-08-28', 'notes' => null,
                'created_at' => '2026-08-05 10:10:00', 'updated_at' => '2026-08-05 10:10:00',
            ],
            [
                'id' => 102, 'name' => 'Galih Rakasiwi', 'id_number' => '3509312303960052', 'phone' => '087856412975', 'email' => 'galih.rakasiwi@gmail.com',
                'address' => 'Jl. Trunojoyo No. 118, Kaliwates, Jember', 'birth_date' => '1996-03-23', 'notes' => null,
                'created_at' => '2026-08-12 13:25:00', 'updated_at' => '2026-08-12 13:25:00',
            ],
            [
                'id' => 103, 'name' => 'Sri Wahyuni', 'id_number' => '3509215507740039', 'phone' => '081236984125', 'email' => null,
                'address' => 'Jl. Piere Tendean No. 16, Karangrejo, Sumbersari, Jember', 'birth_date' => '1974-07-15', 'notes' => 'Sewa untuk acara keluarga ke Banyuwangi.',
                'created_at' => '2026-08-19 08:40:00', 'updated_at' => '2026-08-19 08:40:00',
            ],
            [
                'id' => 104, 'name' => 'Imron Rosyadi', 'id_number' => '3509411201830074', 'phone' => '085732194856', 'email' => 'imron.rosyadi@yahoo.com',
                'address' => 'Jl. Melati No. 8, Gebang, Patrang, Jember', 'birth_date' => '1983-01-12', 'notes' => null,
                'created_at' => '2026-08-26 15:00:00', 'updated_at' => '2026-08-26 15:00:00',
            ],
            [
                'id' => 105, 'name' => 'Nur Laili Farida', 'id_number' => '3509216010950061', 'phone' => '081337492810', 'email' => 'laili.farida@gmail.com',
                'address' => 'Jl. Belitung No. 29, Sumbersari, Jember', 'birth_date' => '1995-10-20', 'notes' => null,
                'created_at' => '2026-09-02 11:15:00', 'updated_at' => '2026-09-02 11:15:00',
            ],
        ]);

        // Data verifikasi KTP & SIM A spesifik untuk operasional rental
        Customer::where('id', 1)->update([
            'sim_number' => '1004-8504-000123',
            'verified_at' => '2025-01-14 10:25:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 2)->update([
            'sim_number' => '1004-9008-000245',
            'verified_at' => '2025-01-21 14:40:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 3)->update([
            'sim_number' => '1004-7912-000389',
            'verified_at' => '2025-02-03 09:55:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 7)->update([
            'sim_number' => '1004-8802-000412',
            'verified_at' => '2025-03-07 16:00:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 10)->update([
            'sim_number' => '1004-9304-000578',
            'verified_at' => '2025-03-18 10:15:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 20)->update([
            'sim_number' => '1004-8609-000623',
            'verified_at' => '2025-04-12 11:30:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 37)->update([
            'sim_number' => '1004-9107-000789',
            'verified_at' => '2025-05-19 14:20:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 45)->update([
            'sim_number' => '1004-8711-000854',
            'verified_at' => '2025-06-25 09:45:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 53)->update([
            'sim_number' => '1004-9403-000962',
            'verified_at' => '2025-07-30 13:10:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 69)->update([
            'sim_number' => '1004-8905-001047',
            'verified_at' => '2025-08-15 15:30:00',
            'verified_by' => 1,
        ]);
        Customer::where('id', 104)->update([
            'sim_number' => '1004-8301-001156',
            'verification_status' => 'pending',
            'verified_at' => null,
            'verified_by' => null,
        ]);
        Customer::where('id', 105)->update([
            'sim_number' => null,
            'verification_status' => 'rejected',
            'verified_at' => null,
            'verified_by' => 1,
            'rejection_reason' => 'Foto KTP buram, tidak terbaca jelas dan NIK belum terdaftar di Dispendukcapil.',
        ]);
    }
}
