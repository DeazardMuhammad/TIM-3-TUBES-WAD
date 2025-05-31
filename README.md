# 🔍 Lost & Found Campus

<div align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
  <img src="https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white" alt="Bootstrap">
  <img src="https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
</div>

<div align="center">
  <h3>📱 Aplikasi Lost & Found berbasis web untuk kampus</h3>
  <p>Platform digital untuk membantu mahasiswa menemukan barang yang hilang atau melaporkan barang yang ditemukan di area kampus</p>
</div>

---

## ✨ Fitur Utama

### 👥 **Untuk Mahasiswa**
- 🔐 **Autentikasi Aman** - Login/Register dengan validasi NIM
- 📝 **Lapor Barang Hilang** - Upload foto dan deskripsi detail
- 🎯 **Lapor Barang Ditemukan** - Bantu teman menemukan barang mereka
- 🔍 **Pencarian Cerdas** - Filter berdasarkan kategori, lokasi, dan tanggal
- 📊 **Dashboard Personal** - Statistik laporan dan status terkini
- 📱 **Responsive Design** - Akses mudah dari desktop dan mobile

### 👨‍💼 **Untuk Admin**
- 🛠️ **Manajemen Kategori** - CRUD kategori barang
- 📈 **Dashboard Admin** - Overview statistik keseluruhan
- ✅ **Verifikasi Laporan** - Approve/reject laporan mahasiswa
- 👥 **Manajemen User** - Kelola akun mahasiswa
- 📋 **Export Data** - Laporan dalam format yang diinginkan

---

## 🏗️ Teknologi yang Digunakan

| Teknologi | Versi | Deskripsi |
|-----------|-------|-----------|
| **Laravel** | 10.x | PHP Framework untuk backend |
| **MySQL** | 8.0+ | Database relational |
| **Bootstrap** | 5.3 | CSS Framework untuk UI |
| **Laravel Sanctum** | 3.x | API authentication |
| **Blade** | - | Template engine Laravel |

---

## 📋 Prerequisites

Pastikan sistem Anda memiliki:

- ✅ PHP >= 8.1
- ✅ Composer
- ✅ MySQL >= 8.0
- ✅ Node.js & NPM (opsional untuk asset compilation)
- ✅ Web server (Apache/Nginx) atau PHP built-in server

---

## 🚀 Instalasi

### 1️⃣ Clone Repository
```bash
git clone https://github.com/username/lost-and-found.git
cd lost-and-found
```

### 2️⃣ Install Dependencies
```bash
composer install
```

### 3️⃣ Environment Setup
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4️⃣ Database Configuration
Edit file `.env` dan sesuaikan konfigurasi database:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lost_and_found
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5️⃣ Database Setup
```bash
# Jalankan migrasi
php artisan migrate

# Seed data awal
php artisan db:seed

# Buat symbolic link untuk storage
php artisan storage:link
```

### 6️⃣ Jalankan Aplikasi
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

---

## 👤 Default User Accounts

Setelah menjalankan seeder, Anda dapat menggunakan akun berikut:

### 🔑 Admin Account
- **Email:** admin@lostandfound.com
- **Password:** admin123
- **Role:** admin

### 👨‍🎓 Student Account
- **Email:** mahasiswa@lostandfound.com
- **Password:** mahasiswa123
- **Role:** mahasiswa

---

### 🔗 Storage Link
Jika symbolic link belum dibuat:
```bash
php artisan storage:link
```

---

<div align="center">
  <p><strong>⭐ Untuk Memenuhi Tugas Besar WAD Semester 4 ⭐</strong></p>
</div>
