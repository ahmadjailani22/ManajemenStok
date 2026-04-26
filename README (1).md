# 🛒 Toko Safitri — Sistem Manajemen Barang & Laporan Harian

<div align="center">

![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-XAMPP-FB7A24?style=for-the-badge&logo=apache&logoColor=white)
![GitHub](https://img.shields.io/badge/GitHub-100000?style=for-the-badge&logo=github&logoColor=white)

**Sistem informasi berbasis web untuk mengelola inventori, transaksi, supplier, dan laporan operasional Toko Safitri secara terintegrasi dan real-time.**

![Status](https://img.shields.io/badge/Status-🚧%20Dalam%20Pengembangan-orange?style=flat-square)
![Versi](https://img.shields.io/badge/Versi-1.0.0-blue?style=flat-square)
![Lisensi](https://img.shields.io/badge/Lisensi-MIT-green?style=flat-square)

</div>

---

## 📌 Deskripsi Proyek

**Toko Safitri** adalah toko kosmetik dan barang harian yang berlokasi di Pasar Sumani, Kab. Solok, Sumatera Barat. Sebelumnya, seluruh proses pengelolaan stok, transaksi, dan pelaporan dilakukan secara manual menggunakan buku catatan dan kalkulator — rawan kesalahan dan tidak efisien.

Sistem ini hadir sebagai solusi digital yang menggantikan proses manual tersebut. Dengan aplikasi berbasis web ini, pemilik dan karyawan Toko Safitri dapat:

- Memantau **stok barang secara real-time**
- Menjalankan **transaksi kasir (POS)** dengan cepat dan akurat
- Mendapatkan **laporan penjualan otomatis** harian & bulanan
- Mengelola **data supplier** dan riwayat pembelian barang

---

## 👥 Tim Pengembang

> Proyek Sistem Informasi (Capstone Project) — Kelompok 6  
> Program Studi Sistem Informasi, UIN Mahmud Yunus Batusangkar  
> Dosen Pembimbing: **Abdurrahman Niarman, M.Sc.**

| No | Nama | NIM | Peran |
|----|------|-----|-------|
| 1 | Ahmad Jaylani | 2330407028 | Project Manager & Backend Developer |
| 2 | Ariel Al Muqsith | 2330407007 | Frontend Developer |
| 3 | Nanda Rizalfi | 2430407059 | Database Administrator & Tester |

---

## ✨ Fitur Utama

### 🔐 Autentikasi & Manajemen Pengguna
- Login & logout dengan enkripsi password (bcrypt)
- Manajemen role: **Admin**, **Kasir**, **Pemilik**
- Hak akses berbeda per role
- Log aktivitas pengguna (siapa melakukan apa & kapan)

### 📦 Manajemen Produk & Inventori
- CRUD produk: nama, kode, kategori, harga beli, harga jual, satuan
- Upload foto produk
- Kelola kategori produk (kosmetik, sembako, kebersihan, dll)
- Update stok masuk dari supplier
- **Notifikasi otomatis** ketika stok ≤ batas minimum
- Histori perubahan stok dengan timestamp lengkap

### 🛒 Point of Sale (POS / Kasir)
- Transaksi penjualan digital — cepat & akurat
- Kalkulasi total otomatis
- Cetak struk / bukti pembayaran
- Riwayat transaksi dengan filter tanggal & produk

### 🚚 Manajemen Supplier
- Data supplier: nama, alamat, nomor kontak, produk yang disuplai
- Riwayat pembelian per supplier (kapan beli, jumlah, harga)

### 📈 Laporan & Analitik
- Laporan penjualan **harian** & **bulanan** dengan grafik
- Laporan produk terlaris
- Laporan kondisi stok semua produk
- **Export laporan** ke format PDF & Excel

---

## ⚙️ Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Frontend | HTML5, CSS3, JavaScript |
| Framework CSS | Bootstrap 5 |
| Backend | PHP 8.x |
| Database | MySQL |
| Web Server (lokal) | Apache via XAMPP |
| Desain UI | Figma |
| Version Control | Git & GitHub |

---

## 🗂️ Struktur Direktori

```
toko-safitri-system/
├── assets/
│   ├── css/          # Custom stylesheet
│   ├── js/           # Custom JavaScript
│   └── img/          # Gambar & logo
├── config/
│   └── database.php  # Konfigurasi koneksi database
├── modules/
│   ├── auth/         # Login, logout, session
│   ├── produk/       # CRUD produk & kategori
│   ├── stok/         # Manajemen stok & notifikasi
│   ├── pos/          # Transaksi kasir (POS)
│   ├── supplier/     # Data supplier & pembelian
│   ├── laporan/      # Laporan & export
│   └── users/        # Manajemen pengguna & role
├── database/
│   └── toko_safitri.sql  # File dump database
├── index.php         # Entry point aplikasi
└── README.md
```

---

## 🚀 Cara Instalasi Lokal

Ikuti langkah-langkah berikut untuk menjalankan proyek ini di komputer lokal:

### Prasyarat
Pastikan sudah terinstall:
- [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP 8.x)
- [Git](https://git-scm.com/)
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

**1. Clone repository ini**
```bash
git clone https://github.com/[username]/toko-safitri-system.git
```

**2. Pindahkan folder ke direktori htdocs XAMPP**
```bash
# Windows
mv toko-safitri-system C:/xampp/htdocs/

# Linux / Mac
mv toko-safitri-system /opt/lampp/htdocs/
```

**3. Jalankan XAMPP**
- Buka XAMPP Control Panel
- Start **Apache** dan **MySQL**

**4. Import database**
- Buka browser → akses `http://localhost/phpmyadmin`
- Buat database baru dengan nama: `toko_safitri`
- Klik tab **Import** → pilih file `database/toko_safitri.sql`
- Klik **Go**

**5. Konfigurasi koneksi database**

Edit file `config/database.php`:
```php
<?php
$host     = 'localhost';
$dbname   = 'toko_safitri';
$username = 'root';
$password = '';  // Sesuaikan dengan password MySQL kamu
?>
```

**6. Akses aplikasi**

Buka browser dan akses:
```
http://localhost/toko-safitri-system/
```

### Akun Default untuk Testing

| Role | Username | Password |
|------|----------|----------|
| Admin | `admin` | `admin123` |
| Kasir | `kasir1` | `kasir123` |
| Pemilik | `pemilik` | `pemilik123` |

> ⚠️ **Penting:** Ganti password default setelah login pertama kali!

---

## 📊 Status Pengembangan

| Modul | Status |
|-------|--------|
| ✅ Autentikasi & Login | Selesai |
| ✅ Manajemen Produk (CRUD) | Selesai |
| 🔄 Manajemen Stok & Notifikasi | Dalam Pengerjaan |
| 🔄 Point of Sale (Kasir) | Dalam Pengerjaan |
| ⏳ Manajemen Supplier | Belum Dimulai |
| ⏳ Modul Laporan & Export | Belum Dimulai |
| ⏳ Manajemen Pengguna & Role | Belum Dimulai |

---

## 📋 Roadmap Pengembangan

```
Fase 1 — Analisis        [✅ Selesai]   Minggu 1–2
Fase 2 — Perancangan     [✅ Selesai]   Minggu 3–4
Fase 3 — Implementasi    [🔄 Berjalan]  Minggu 5–10
Fase 4 — Testing (UAT)   [⏳ Pending]   Minggu 11–12
Fase 5 — Deployment      [⏳ Pending]   Minggu 13
Fase 6 — Serah Terima    [⏳ Pending]   Minggu 14
```

---

## 🤝 Kontribusi Tim

Setiap anggota tim berkontribusi melalui GitHub dengan pembagian tugas sebagai berikut:

- **Ahmad Jaylani** — Backend PHP, integrasi database, deployment, dokumentasi teknis
- **Ariel Al Muqsith** — Desain UI/UX (Figma), implementasi frontend HTML/CSS/JS
- **Nanda Rizalfi** — Perancangan ERD & skema database, pengujian sistem (QA & UAT), user manual

---

## 📞 Kontak Tim

| Nama | No. HP | Email |
|------|--------|-------|
| Ahmad Jaylani | 082172323573 | jailaniahmad2205@gmail.com |
| Ariel Al Muqsith | 083821480300 | — |
| Nanda Rizalfi | 081299098181 | — |

---

<div align="center">

Dibuat dengan ❤️ oleh **Kelompok 6 – Tim Develop**  
Prodi Sistem Informasi · UIN Mahmud Yunus Batusangkar · 2026

</div>
