# 🛒 Toko Safitri — Sistem Manajemen Barang & Laporan Harian

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![AdminLTE](https://img.shields.io/badge/AdminLTE-3-3C8DBC?style=for-the-badge)

**Sistem informasi berbasis web untuk mengelola inventori, transaksi, supplier, dan laporan operasional Toko Safitri secara terintegrasi dan real-time.**

![Status](https://img.shields.io/badge/Status-🚧%20Dalam%20Pengembangan-orange?style=flat-square)
![Versi](https://img.shields.io/badge/Versi-1.0.0-blue?style=flat-square)

</div>

---

## 📌 Deskripsi Proyek

**Toko Safitri** adalah toko kosmetik dan barang harian yang berlokasi di Pasar Sumani, Kab. Solok, Sumatera Barat. Sebelumnya, seluruh proses pengelolaan stok, transaksi, dan pelaporan dilakukan secara manual menggunakan buku catatan dan kalkulator — rawan kesalahan dan tidak efisien.

Sistem ini hadir sebagai solusi digital berbasis **Laravel** yang menggantikan proses manual tersebut. Dengan aplikasi ini, pemilik dan karyawan Toko Safitri dapat:

- Memantau **stok barang secara real-time**
- Mengelola **data produk, kategori, dan supplier** dengan mudah
- Mendapatkan **laporan penjualan otomatis** harian & bulanan
- Mengakses sistem melalui **browser** di komputer maupun tablet

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

### 🔐 Autentikasi
- Login & logout dengan sistem session Laravel
- Proteksi halaman dengan middleware `auth` & `guest`

### 📦 Manajemen Barang
- Tambah, edit, hapus, dan lihat detail barang
- Data barang: nama, kode, kategori, harga beli, harga jual, stok, satuan
- Upload foto produk

### 🗂️ Manajemen Kategori
- CRUD kategori produk (kosmetik, sembako, kebersihan, dll)
- Relasi kategori ke data barang

### 🚚 Manajemen Supplier
- CRUD data supplier: nama, alamat, kontak
- Riwayat pembelian per supplier

### 📊 Dashboard
- Ringkasan data: total barang, kategori, supplier
- Notifikasi stok minimum

---

## ⚙️ Teknologi yang Digunakan

| Komponen | Teknologi |
|----------|-----------|
| Framework Backend | Laravel 11 |
| Bahasa Pemrograman | PHP 8.x |
| Database | MySQL |
| Frontend | Blade Template, HTML5, CSS3, JavaScript |
| Framework CSS | Bootstrap 5 |
| Template Admin | AdminLTE 3 |
| Build Tool | Vite |
| Version Control | Git & GitHub |
| Server Lokal | Apache via XAMPP |

---

## 🗂️ Struktur Direktori

```
toko-safitri-system/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── AuthController.php        # Login & logout
│   │       ├── BarangController.php      # CRUD barang
│   │       ├── DashboardController.php   # Halaman dashboard
│   │       ├── KategoriController.php    # CRUD kategori
│   │       ├── SupplierController.php    # CRUD supplier
│   │       └── Controller.php
│   ├── Models/
│   │   ├── Barang.php                    # Model data barang
│   │   ├── Kategori.php                  # Model kategori
│   │   ├── User.php                      # Model pengguna
│   │   └── supplier.php                  # Model supplier
│   └── Providers/
│       └── AppServiceProvider.php
│
├── bootstrap/
│   ├── app.php
│   ├── providers.php
│   └── cache/
│
├── config/
│   ├── adminlte.php                      # Konfigurasi template AdminLTE
│   ├── app.php
│   ├── auth.php
│   ├── database.php                      # Konfigurasi koneksi database
│   └── ...
│
├── database/
│   ├── migrations/
│   │   ├── 2026_04_06_..._create_sessions_table.php
│   │   └── 2026_04_20_..._create_suppliers_table.php
│   ├── seeders/
│   │   └── DatabaseSeeder.php
│   └── factories/
│       └── UserFactory.php
│
├── public/
│   ├── index.php                         # Entry point aplikasi
│   ├── .htaccess
│   └── vendor/                           # Asset publik (AdminLTE, dll)
│
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── auth/
│       │   └── login.blade.php           # Halaman login
│       ├── barang/
│       │   ├── index.blade.php           # Daftar barang
│       │   ├── create.blade.php          # Form tambah barang
│       │   ├── edit.blade.php            # Form edit barang
│       │   └── show.blade.php            # Detail barang
│       ├── kategori/
│       │   ├── index.blade.php           # Daftar kategori
│       │   ├── create.blade.php          # Form tambah kategori
│       │   └── edit.blade.php            # Form edit kategori
│       ├── supplier/
│       │   ├── index.blade.php           # Daftar supplier
│       │   ├── create.blade.php          # Form tambah supplier
│       │   └── edit.blade.php            # Form edit supplier
│       ├── layouts/
│       │   └── app.blade.php             # Layout utama (AdminLTE)
│       ├── dashboard.blade.php           # Halaman dashboard
│       └── welcome.blade.php
│
├── routes/
│   ├── web.php                           # Definisi semua route web
│   └── console.php
│
├── storage/
│   ├── app/
│   ├── framework/
│   └── logs/
│
├── tests/
│   ├── Feature/
│   │   └── ExampleTest.php
│   ├── Unit/
│   │   └── ExampleTest.php
│   └── TestCase.php
│
├── .env                                  # Konfigurasi environment (tidak di-commit)
├── .env.example                          # Template konfigurasi environment
├── .gitignore
├── artisan                               # Laravel CLI
├── composer.json                         # Dependensi PHP
├── package.json                          # Dependensi Node.js
├── vite.config.js                        # Konfigurasi Vite
└── README.md
```

---

## 🚀 Cara Instalasi Lokal

### Prasyarat

Pastikan sudah terinstall:
- [XAMPP](https://www.apachefriends.org/) (PHP 8.x + MySQL + Apache)
- [Composer](https://getcomposer.org/)
- [Node.js & NPM](https://nodejs.org/)
- [Git](https://git-scm.com/)

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/[username]/toko-safitri-system.git
cd toko-safitri-system
```

**2. Install dependensi PHP**
```bash
composer install
```

**3. Install dependensi Node.js**
```bash
npm install
```

**4. Salin file konfigurasi environment**
```bash
cp .env.example .env
```

**5. Generate application key**
```bash
php artisan key:generate
```

**6. Konfigurasi database**

Edit file `.env`, sesuaikan bagian berikut:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=toko_safitri
DB_USERNAME=root
DB_PASSWORD=
```

**7. Buat database**

Buka phpMyAdmin di `http://localhost/phpmyadmin`, lalu buat database baru bernama `toko_safitri`.

**8. Jalankan migrasi database**
```bash
php artisan migrate
```

**9. (Opsional) Jalankan seeder untuk data awal**
```bash
php artisan db:seed
```

**10. Build asset frontend**
```bash
npm run build
```

**11. Jalankan server**
```bash
php artisan serve
```

Akses aplikasi di browser:
```
http://127.0.0.1:8000
```

---

## 🔑 Akun Default untuk Testing

| Role | Email | Password |
|------|-------|----------|
| Admin | `admin@tokosafitri.com` | `password` |

> ⚠️ **Penting:** Ganti password default setelah login pertama kali!

---

## 📊 Status Pengembangan

| Modul | Status |
|-------|--------|
| ✅ Autentikasi (Login / Logout) | Selesai |
| ✅ Manajemen Barang (CRUD) | Selesai |
| ✅ Manajemen Kategori (CRUD) | Selesai |
| ✅ Manajemen Supplier (CRUD) | Selesai |
| ✅ Dashboard Ringkasan | Selesai |
| 🔄 Manajemen Stok & Notifikasi Minimum | Dalam Pengerjaan |
| 🔄 Point of Sale (Kasir) | Dalam Pengerjaan |
| ⏳ Modul Laporan & Export PDF/Excel | Belum Dimulai |
| ⏳ Manajemen Role & Hak Akses | Belum Dimulai |

---

## 🔀 Routes Tersedia

| Method | URI | Controller | Keterangan |
|--------|-----|------------|------------|
| GET | `/` | — | Redirect ke login |
| GET | `/login` | AuthController@showLogin | Halaman login |
| POST | `/login` | AuthController@login | Proses login |
| POST | `/logout` | AuthController@logout | Logout |
| GET | `/dashboard` | DashboardController@index | Dashboard utama |
| Resource | `/barang` | BarangController | CRUD barang |
| Resource | `/supplier` | SupplierController | CRUD supplier |
| Resource | `/kategori` | KategoriController | CRUD kategori |

---

## 🤝 Kontribusi Tim

| Nama | Kontribusi di GitHub |
|------|----------------------|
| Ahmad Jaylani | Backend controllers, migrations, routes, deployment |
| Ariel Al Muqsith | Blade views, layout AdminLTE, CSS & JavaScript |
| Nanda Rizalfi | Database schema, seeders, testing, dokumentasi |

---

## 📞 Kontak Tim

| Nama | No. HP | Email |
|------|--------|-------|
| Ahmad Jaylani | 082172323573 | jailaniahmad2205@gmail.com |
| Ariel Al Muqsith | 083821480300 | arielgaming3632r@gmail.com |
| Nanda Rizalfi | 081299098181 | — |

---

<div align="center">

Dibuat dengan ❤️ oleh **Kelompok 6 – Tim Develop**  
Prodi Sistem Informasi · UIN Mahmud Yunus Batusangkar · 2026

</div>
