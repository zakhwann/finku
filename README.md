# 💰 Finku — Personal Finance App

> Aplikasi manajemen keuangan pribadi berbasis web yang dirancang khusus untuk mahasiswa dan anak muda Indonesia.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat)

---

## 📸 Screenshot

> *Coming soon — akan diupdate setelah deploy*

---

## ✨ Fitur Lengkap

### 💸 Manajemen Keuangan
- Catat pemasukan & pengeluaran harian
- Kategori transaksi dengan warna custom
- Filter transaksi by tipe, kategori & bulan
- Export laporan ke **PDF** dan **Excel**

### 📊 Dashboard & Analitik
- Ringkasan saldo, pemasukan & pengeluaran bulan ini
- Bar chart arus kas 6 bulan terakhir
- Doughnut chart pengeluaran per kategori
- Rasio tabungan real-time

### 🎯 Budget & Rekomendasi
- Set budget bulanan per kategori
- Notifikasi warning saat budget hampir habis
- **Rekomendasi Cerdas** — proyeksi pengeluaran akhir bulan berbasis rata-rata harian
- Analisa saving rate dengan saran perbaikan

### 🛍️ Wishlist & Price Tracker
- Tambahkan barang impian dengan target harga
- Pantau progress tabungan per wishlist item
- Estimasi kapan bisa beli berdasarkan rata-rata saving
- Priority system (Tinggi / Sedang / Rendah)

### 🧾 Split Bill
- Buat sesi makan bersama dengan anggota
- Input pesanan per orang
- Mode split rata atau custom (sesuai pesanan)
- Tambah pajak & diskon
- Simpan hasil ke hutang piutang otomatis

### 🤝 Hutang & Piutang
- Catat hutang & piutang manual
- Integrasi dengan Split Bill
- Tandai pembayaran sebagian atau lunas
- Warning jatuh tempo di dashboard

### 🌙 Lainnya
- Dark Mode
- Landing page publik
- Responsive design
- Seeder data dummy untuk demo

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Blade, Vanilla CSS |
| Database | MySQL 8.0 |
| Charts | Chart.js 4.4 |
| Auth | Laravel Breeze |
| PDF Export | barryvdh/laravel-dompdf |
| Excel Export | rap2hpoutre/fast-excel |
| Font | Plus Jakarta Sans, Fraunces |

---

## 🚀 Instalasi & Menjalankan

### Requirements
- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/USERNAMEKAMU/finku.git
cd finku
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Setup environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Konfigurasi database**

Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=finku
DB_USERNAME=root
DB_PASSWORD=
```

**5. Jalankan migration & seeder**
```bash
php artisan migrate
php artisan db:seed --class=DummyDataSeeder
```

**6. Jalankan aplikasi**
```bash
php artisan serve
npm run dev
```

Buka **http://127.0.0.1:8000**

### Demo Account
