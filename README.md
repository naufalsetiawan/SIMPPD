# SIMPPD — Sistem Informasi Manajemen Pengawasan Perjalanan Dinas

## Requirements
- CodeIgniter 4  
- PHP 8.1+  
- Composer  
- MySQL / MariaDB  
- TailwindCSS 4 (CLI)  
- Node.js + npm  
- XAMPP (Apache + MySQL)  

---

## Installation Guide

### 1. Clone Repository

**Via Git**
```bash
cd C:\xampp\htdocs
git clone https://github.com/naufalsetiawan/SIMPPD
cd SIMPPD
```

**Atau Upload Manual**
1. Download ZIP dari GitHub  
2. Extract ke folder:
```
C:\xampp\htdocs\SIMPPD
```

---

### 2. Install PHP Dependencies (Composer)

```bash
composer install
```

---

### 3. Install Frontend Dependencies

```bash
npm install
npm run build
```

---

### 4. Copy & Konfigurasi File `.env`

```bash
cp env .env
```

Kemudian edit file `.env`:

```
CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
```

---

## 5. Migrasi Database

Jalankan migrasi:
```bash
php spark migrate
```

Jika ingin menjalankan seeder:
```bash
php spark db:seed NamaSeeder
```

---

## ⚠️ Catatan Keamanan (PENTING!)

Seeder akan membuat akun admin default dengan **username + password bawaan**.

👉 **Sebelum menjalankan seeder, ubah password admin default terlebih dahulu.**

### Buka file seeder:
```
app/Database/Seeds/AdminSeeder.php
```

### Cari bagian berikut:
```php
'username' => 'admin',
'password' => password_hash('admin123', PASSWORD_DEFAULT),
'id_pegawai' => NULL,
'created_at' => $currentTimestamp,
```

### Ubah menjadi:
```php
'password' => password_hash('password_baru_anda', PASSWORD_DEFAULT),
```

## 6. Jalankan Project

```bash
php spark serve
```

Akses melalui browser:

```
http://localhost:8080
```
