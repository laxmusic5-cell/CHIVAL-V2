# Laporan Analisis Website Lama (Chival Detailing)
**Project Rebuild: CHIVAL V2**  
**Role:** Senior Software Architect & Laravel Developer  
**Tanggal Audit:** 30 Juni 2026

Laporan ini menyajikan hasil analisis audit teknis dan arsitektur menyeluruh terhadap kode sumber website lama **Chival Detailing** (native PHP) sebagai acuan spesifikasi bisnis dan pedoman teknis pembangunan ulang (*rebuild*) pada **CHIVAL V2** menggunakan Laravel 13.

---

## 1. Analisis Struktur Folder Website Lama

Struktur folder website lama adalah aplikasi monolitik native PHP dengan pembagian direktori sebagai berikut:

| Nama Folder / Berkas | Fungsi & Deskripsi | Karakteristik / Masalah Teknis |
|---|---|---|
| **`root` (`index.php`, `login.php`, dll.)** | Halaman publik, dashboard customer, admin, dan employee. | Banyak berkas PHP tipis di root yang sebenarnya bertindak sebagai router/wrapper ke berkas utama seperti `customer.php`. |
| **`actions/`** | Endpoint untuk memproses form submission (POST) dan manipulasi state. | Berisi campuran kode redirect HTML dan API response JSON. Tidak ada standardisasi respon. |
| **`api/payment/`** | Endpoint untuk inisiasi pembayaran (Midtrans Snap), penerimaan webhook (notification), dan pengecekan status. | Merupakan bagian backend paling terstruktur karena menerapkan pemisahan tugas yang cukup baik. |
| **`includes/`** | Kumpulan *helper* dan *logic layer* informal (Auth, DB, Payment, Finance, Invoice, State). | Bertindak sebagai Service Layer informal. Berisi banyak fungsi global prosedural (berawalan `chival_`). |
| **`components/`** | Potongan layout UI (sidebar, header, helper UI) yang digunakan bersama. | Shared layouts manual dengan perintah `require/include`. |
| **`admin/`** | Modul khusus admin. Di dalamnya terdapat berkas finance utama (`keuangan.php`). | `admin/keuangan.php` sangat besar (43KB) dan diletakkan di subfolder terpisah secara tidak konsisten. |
| **`js/script.js`** | Monolit skrip Javascript frontend (±15.700 baris / 618 KB). | **Code Smell Kritis.** Menampung hampir seluruh logika aplikasi: pricing engine, inspeksi kendaraan, booking state, UI state, rendering chat, hingga caching localStorage. |
| **`css/style.css` & `overrides.css`** | Berkas styling utama (Total ~350 KB). | Patching CSS berlapis-lapis yang menandakan penanganan responsivitas yang berantakan (*overlapping style rules*). |
| **`database/`** | Berkas SQL schema, seed data, cleanup scripts, dan migrasi inkremental. | Menyimpan data cadangan dump SQL sensitif (~4,5 MB) di dalam folder yang berada di bawah document root. |
| **`data/`** | Berkas JSON lokal sebagai fallback penyimpanan user/state (`operational-state.json`). | **Celah Keamanan.** Data rahasia dan operasional disimpan dalam format file teks yang berpotensi dapat diakses publik jika web server salah dikonfigurasi. |
| **`uploads/` & `invoices/`** | Direktori penyimpanan gambar dokumentasi before/after dan invoice PDF. | Hanya dilindungi `.htaccess` Apache. Rentan bocor jika menggunakan server Nginx/LiteSpeed tanpa konfigurasi manual. |
| **`tests/` & `tools/`** | Script pengujian kalkulasi finance dan integrasi status pembayaran. | Sangat berguna untuk verifikasi matematika tetapi tidak boleh masuk ke production. |

---

## 2. Identifikasi Teknologi yang Digunakan

| Komponen | Teknologi Lama | Catatan & Implikasi untuk Chival V2 |
|---|---|---|
| **Bahasa Pemrograman** | Native PHP (target runtime `>=8.2`) | Ditingkatkan ke PHP 8.3/8.4 dengan standardisasi OOP pada Laravel 13. |
| **Database Engine** | MySQL / MariaDB | Diakses menggunakan PDO native dan prepared statement. Di Chival V2 akan diakses secara elegan melalui Eloquent ORM. |
| **Frontend Styling** | Vanilla CSS (style.css + overrides.css) | Digantikan secara menyeluruh menggunakan Tailwind CSS v4. |
| **Frontend Logic** | Vanilla Javascript (script.js - Monolitik) | Logika komparasi harga, perutean state, dan rendering akan dipindah sepenuhnya ke backend Laravel / Blade. |
| **Payment Gateway** | `midtrans/midtrans-php ^2.6` (Snap QRIS) | Dibungkus ke dalam Service Payment di Laravel 13. |
| **PDF Generator** | `dompdf/dompdf ^3.1` | Digunakan untuk invoice. Pada Chival V2, disarankan menggunakan library wrapper Laravel DomPDF yang lebih bersih. |
| **Excel Export** | `phpoffice/phpspreadsheet ^2.2` | Untuk laporan keuangan. Di Laravel dapat dikelola menggunakan package Laravel Excel (`maatwebsite/excel`). |
| **Environment Config** | `vlucas/phpdotenv ^5.6` | Sudah didukung secara native oleh Laravel. |
| **Web Server Config** | Apache `.htaccess` & built-in router PHP | Laravel menggunakan folder `public/` terisolasi sebagai document root utama untuk keamanan tinggi. |

---

## 3. Identifikasi Seluruh Modul Sistem

Sistem Chival Detailing memiliki modul-modul fungsional sebagai berikut:

1.  **Modul Autentikasi & Otorisasi:** Registrasi customer baru, login/logout multi-role (Customer, Employee, Admin, Owner), registrasi ulang token session, reset password admin via kode email manual.
2.  **Modul Manajemen Kendaraan Customer:** Fitur customer untuk mendaftarkan dan menyimpan profil kendaraan mereka (tipe, kategori ukuran, merek/model, plat nomor, warna, catatan tambahan).
3.  **Modul Katalog Layanan & Add-on:** Pengaturan paket detailing (Daily Detail Clean, Problem Care Deep Clean, Full Interior Reset, Maintenance Clean) beserta harga berdasar ukuran kendaraan, dan add-on kondisi ekstra (ruang mesin, jamur kaca, aspal, bau kabin).
4.  **Modul Wizard Booking & Jadwal Sesi:** Alur pendaftaran booking home service multi-langkah: pemilihan kendaraan, pemilihan paket, pengisian form inspeksi, pemilihan tanggal & sesi waktu kerja yang tersedia.
5.  **Modul Inspeksi Kondisi & Rekomendasi:** Form interaktif yang menganalisis keluhan customer (misal: "interior bau rokok") lalu merekomendasikan paket tambahan (*add-on*) yang relevan secara otomatis secara visual.
6.  **Modul Integrasi Pembayaran (Midtrans & WA):**
    *   **Midtrans (Automated):** Pembayaran Down Payment (DP) atau Full Payment melalui QRIS Midtrans Snap.
    *   **WhatsApp (Manual):** Pengalihan konfirmasi transaksi secara manual ke WhatsApp Admin jika sistem pembayaran online terganggu.
7.  **Modul Operasional & Penugasan Karyawan:** Dasbor bagi admin untuk menugaskan karyawan ke order booking tertentu, dan dasbor karyawan untuk melihat daftar tugas, checklist pengerjaan, dan mengunggah dokumentasi foto *before/after*.
8.  **Modul Keuangan (Finance Ledger):** Pencatatan detail pemasukan per pekerjaan (pendapatan kotor, biaya chemical, komisi helper, biaya transportasi, alokasi kas owner, dana darurat, dana upgrade alat, dana marketing) serta pencatatan pengeluaran operasional umum.
9.  **Modul Invoice:** Pembuatan invoice PDF otomatis saat pembayaran sukses, dengan batasan hak akses sesuai role pengguna.
10. **Modul Chat & Pesan Internal:** Chat antara customer dan admin/karyawan yang bertugas pada order tersebut.
11. **Modul Loyalty & Review (Setengah Jadi):** Sistem poin reward setelah pesanan selesai dan form pengisian review visual (yang sayangnya pada versi lama hanya disimpan di localStorage).
12. **Modul E-commerce Produk (Prototipe):** Katalog penjualan produk detailing fisik dengan sistem keranjang belanja lokal (*localStorage*).

---

## 4. Identifikasi Seluruh Menu per Role

Berikut pemetaan menu antarmuka berdasarkan role pengguna pada website lama:

### A. Guest (Pengunjung)
*   **Landing Page:** Paket layanan, add-on, cakupan area operasional, review customer, profil bisnis, CTA Booking.
*   **Auth Gates:** Halaman Login & Register.
*   **Local Cart:** Toko produk fisik (hanya visual lokal).
*   **Public Payment Status:** Halaman cek status pembayaran (jika mengetahui Order ID).

### B. Customer (Pelanggan)
*   **Dashboard:** Ringkasan pesanan aktif, poin loyalty, status pengerjaan.
*   **Booking Wizard:** Halaman pemesanan layanan interaktif.
*   **Daftar Kendaraan:** Tambah/edit/hapus kendaraan pribadi.
*   **Riwayat Order:** Riwayat pengerjaan, link invoice PDF, dan form ulasan (*review*).
*   **Chat Box:** Saluran pesan dengan Admin.
*   **Pengaturan Akun:** Update profil & password.

### C. Employee (Karyawan)
*   **Dashboard Tugas:** Daftar order detailing yang ditugaskan kepada mereka hari ini.
*   **Detail Tugas:** Detail kendaraan customer, lokasi alamat, instruksi admin, checklist SOP pengerjaan, upload foto *before/after*.
*   **Chat Terkait:** Saluran pesan dengan customer dari order yang sedang dikerjakan.
*   **Digital ID Card:** Tampilan kartu identitas karyawan untuk ditunjukkan ke customer.

### D. Admin & Owner (Pengelola Bisnis)
*   **Dashboard KPI:** Statistik pendapatan bulanan, antrean booking masuk, grafik kapasitas harian.
*   **Manajemen Jadwal:** Pengaturan kuota slot, libur sesi, dan tanggal operasional.
*   **Manajemen Katalog:** Pengaturan paket, add-on, area jangkauan, promo voucher.
*   **Manajemen Order:** Verifikasi pembayaran manual, alokasi karyawan, instruksi lapangan, pembaruan status pengerjaan.
*   **Manajemen Pengguna:** Pengelolaan akun customer dan data karyawan beserta generator ID Card.
*   **Modul Keuangan:** Buku kas masuk/keluar, pelaporan keuangan detail per pekerjaan, export Excel.
*   **Pusat Pesan:** Manajemen chat customer terintegrasi.

---

## 5. Identifikasi Seluruh Proses Bisnis

Alur bisnis utama yang didukung oleh sistem lama digambarkan melalui alur operasional berikut:

1.  **Akuisisi & Registrasi:** Pengunjung web mendaftar akun beserta data kendaraan default.
2.  **Form Inspeksi & Pemesanan:** Customer melakukan booking dengan memilih kendaraan, paket, dan mengisi keluhan kondisi visual (kaca berjamur, interior kotor tebal).
3.  **Rekomendasi Dinamis:** Sistem memberikan rekomendasi add-on berdasarkan keluhan visual serta menghitung jarak/biaya area jangkauan.
4.  **Proses Transaksi & Lock Jadwal:** Jadwal dikunci sementara dan sistem mengarahkan ke metode pembayaran (DP/Lunas via QRIS Midtrans atau verifikasi manual via WhatsApp).
5.  **Notifikasi & Sinkronisasi:** Webhook Midtrans memperbarui status pemesanan di database menjadi lunas/DP diterima.
6.  **Disposisi Operasional:** Admin memeriksa order baru, kemudian menugaskan karyawan detailing yang kompeten dan luang.
7.  **Pengerjaan Lapangan:** Karyawan datang ke lokasi customer, melakukan detailing sesuai checklist SOP, mengambil dokumentasi foto *before/after*, dan menandai tugas selesai.
8.  **Pembukuan Keuangan:** Sistem membagi pemasukan otomatis ke kas operasional, bahan kimia, transport, gaji karyawan, kas owner, dan dana darurat. Laporan ini dapat diunduh dalam format Excel.
9.  **Loyalty & Retention:** Customer mendapatkan poin loyalty setelah pengerjaan ditandai selesai dan diminta mengisi ulasan/review layanan.

---

## 6. Identifikasi Seluruh Database yang Digunakan

Database website lama menggunakan MySQL. Struktur tabel dirancang bertahap sehingga menimbulkan redundansi data yang parah.

### Analisis Tabel Database Utama (Lihat schema.sql)
1.  **`users`**: Menyimpan kredensial semua role. Foto profil karyawan disimpan dalam tipe data `LONGTEXT` berupa string base64 (membengkakkan ukuran database).
2.  **`orders`**: Menyimpan data transaksi utama. Menampung data gabungan antara booking layanan dan pembelian produk. Berisi banyak snapshot informasi (kontak, alamat, detail kendaraan, dokumentasi pengerjaan) dalam format kolom JSON.
3.  **`payments`**: Merekam status pembayaran, tautan Snap, payload response dari Midtrans, dan jenis pembayaran (`dp` atau `full`).
4.  **`payment_logs` & `payment_notifications`**: Log teknis dan arsip request payload webhook Midtrans untuk pencegahan fraud/idempotensi.
5.  **`customer_vehicles`**: Menyimpan data kendaraan per customer.
6.  **`service_settings`, `services`, `service_addons`**: **Titik Redundansi Utama.** Daftar harga layanan disimpan di 3 tempat berbeda: tabel `services`, tabel `service_settings`, dan di dalam JSON blob `operational_state`.
7.  **`operational_state`**: **Penyebab Masalah Arsitektur.** Tabel ini hanya memiliki kolom `state_key` and `state_json`. Menyimpan 6 jenis data besar (orders, chats, schedule, messages, service settings) dalam format JSON raksasa.
8.  **`finance_transactions` & `finance_expenses`**: Mencatat aliran keuangan per booking dan pengeluaran umum toko. Kalkulasi alokasi profit sharing dikalkulasi di backend script.

---

## 7. Identifikasi Relasi Antar Fitur

Berikut adalah pemetaan relasi antar entitas bisnis. Beberapa relasi dideklarasikan secara resmi dengan *Foreign Key constraints* di database, namun sebagian besar hanya bersifat relasi logika berbasis string di tingkat kode PHP.

*   `customer_vehicles.customer_id` -> `users.id` (Relasi database resmi: `RESTRICT`)
*   `customer_reviews.order_id` -> `orders.id` (Relasi database resmi: `CASCADE`)
*   `payment_logs.payment_id` -> `payments.id` (Relasi database resmi: `SET NULL`)
*   `finance_transactions.created_by` -> `users.id` (Relasi database resmi: `SET NULL`)
*   `finance_expenses.created_by` -> `users.id` (Relasi database resmi: `SET NULL`)

### Relasi Longgar (Hanya Berbasis Logika String di Kode PHP):
*   `orders.username` -> `users.username` (Jika username diedit di tabel `users`, relasi riwayat order akan terputus).
*   `orders.service_id` -> `services.id`
*   `orders.customer_vehicle_id` -> `customer_vehicles.id`
*   `payments.order_id` -> `orders.order_id`
*   `messages.username` -> `users.username`
*   `payment_notifications.order_id` -> `orders.order_id`

---

## 8. Identifikasi Bug, Duplicate Code, dan Code Smell

### A. Bug Keamanan Kritis (Vulnerabilities)

1.  **Manipulasi Harga dan Nominal DP (Business Logic Tampering):**
    Di dalam berkas `includes/payment.php` fungsi `chival_payment_create_booking_order()` mengambil harga total (`sellingPrice`) dan kebutuhan DP (`requiredDownPayment`) secara langsung dari objek JSON booking yang dikirim oleh browser customer.
    *Dampak:* Customer dengan pengetahuan dasar web debug dapat mengubah parameter JSON order di browser sehingga nominal pembayaran Midtrans menjadi Rp 10.000 untuk paket seharga Rp 500.000, dan server akan menerimanya tanpa validasi ulang ke database master.

2.  **API Modifikasi State Tanpa Proteksi (State Manipulator API):**
    Endpoint `/actions/operational-state.php` menerima input JSON state global dari client melalui POST tanpa pemeriksaan token CSRF. Validasi yang dilakukan server hanya membatasi agar order yang dikirim memiliki nama `username` yang sama dengan session user.
    *Dampak:* Pengguna biasa dapat mengirimkan payload berisi status booking mereka sendiri yang diubah menjadi "Paid" (Lunas) atau mengubah status penugasan karyawan tanpa melalui gerbang pembayaran atau persetujuan admin.

3.  **Kerentanan Double Booking Sesi (Concurrency Issue):**
    Pemeriksaan kapasitas sesi jadwal dilakukan sepenuhnya di browser menggunakan Javascript. Tidak ada mekanisme database lock (`SELECT FOR UPDATE`) atau index unik sesi harian.
    *Dampak:* Jika ada 2 customer melakukan booking pada tanggal dan sesi yang sama secara bersamaan, database akan menyimpan kedua order tersebut tanpa ada penolakan, menyebabkan bentrok jadwal operasional di lapangan.

### B. Duplicate Code & Redundansi Data

1.  **Tiga Sumber Master Data Layanan:**
    Master data paket layanan disimpan di:
    1.  Tabel relasional `services`
    2.  Tabel relasional `service_settings`
    3.  JSON string didalam tabel `operational_state` dengan key `service_settings`.
    *Dampak:* Memperbarui satu harga layanan mengharuskan admin melakukan update di tiga tempat berbeda. Jika salah satu gagal, terjadi ketidaksinkronan harga visual.

2.  **Duplikasi Logika Perhitungan Keuangan:**
    Kalkulasi laba kotor, komisi helper, dan alokasi dana operasional ditulis berulang kali pada berkas `admin/keuangan.php` dan `includes/finance.php`.

### C. Code Smell

1.  **Monolitik Script Frontend (`js/script.js`):**
    Berkas JS tunggal sebesar 632 KB dengan 15.700 baris kode menampung logika perutean UI, pricing engine, integrasi map, penanganan chat, pengelolaan keranjang belanja e-commerce lokal, hingga data mock-up. Berkas ini sangat sulit dipelihara (*unmaintainable*) dan rentan mengalami regresi fungsional saat dimodifikasi.
    
2.  **Penyimpanan Gambar Base64 di Database:**
    Foto dokumentasi before/after dari karyawan dikonversi menjadi string base64 dan disimpan di dalam JSON blob order. Hal ini membuat database MySQL membengkak dengan cepat dan memperlambat query penarikan data transaksi.

---

## 9. Identifikasi Kekurangan UI/UX

*   **Formulir Booking Terlalu Padat:** Halaman booking mengumpulkan input kendaraan, paket, kuesioner inspeksi yang panjang, pemilihan jadwal, area, dan kode promo dalam satu layar panjang. Ini menurunkan konversi pengguna karena kelelahan informasi (*cognitive overload*).
*   **Sinkronisasi State Lambat:** UI sangat bergantung pada keberhasilan POST state JSON global. Ketika jaringan buruk, aksi seperti memilih jadwal tidak memberikan feedback visual instan atau memicu error tak terduga.
*   **Fitur E-commerce Setengah Jadi:** Halaman toko produk (`customer-products.php`) membingungkan karena tidak terintegrasi ke sistem pembayaran online Midtrans Snap (hanya disimpan di localStorage lokal).
*   **Inkonsistensi Bahasa & Encoding:** Terdapat percampuran bahasa (Inggris/Indonesia) pada UI, serta masalah mojibake (karakter aneh seperti `â€œ` akibat ketidaksesuaian encoding UTF-8 pada pembacaan log/excel).

---

## 10. Rekomendasi Arsitektur untuk Rebuild (CHIVAL V2)

Agar seluruh kelemahan di atas tidak terulang, berikut arsitektur baru yang wajib diimplementasikan di **CHIVAL V2** menggunakan Laravel 13:

### Resolusi Bug Kritis pada CHIVAL V2:
1.  **Server-Side Pricing Authority:** Seluruh perhitungan harga paket, add-on, biaya jangkauan area, diskon voucher, dan DP wajib dikalkulasi ulang di dalam `BookingService` di server Laravel saat booking disimpan atau payment diinisiasi. Data dari browser hanya berupa ID referensi (`service_id`, `addon_ids`, `vehicle_id`).
2.  **Restricted State Machine:** Status pemesanan dan pembayaran tidak boleh diubah secara bebas oleh API state generik. Status wajib menggunakan State Machine terproteksi (misal: booking hanya bisa diubah menjadi 'Paid' oleh webhook Midtrans resmi atau verifikasi manual admin).
3.  **Atomic Slot Reservation:** Gunakan fitur database transaction (`DB::transaction`) dan locking `lockForUpdate()` saat customer memilih sesi jadwal, untuk memastikan kapasitas slot diperiksa secara real-time dan aman dari balapan data (*race condition*).
4.  **Laravel Storage File Handler:** Seluruh foto profil dan dokumentasi before/after disimpan sebagai berkas fisik di storage Laravel (`storage/app/public/documentation/`) dan database hanya menyimpan path URL string-nya saja.
5.  **Normalisasi Skema Database:** Menghapus penggunaan tabel `operational_state` JSON blob untuk data relasional utama, dan beralih ke tabel relational murni yang dilindungi referensinya oleh *Foreign Key* sejati.
