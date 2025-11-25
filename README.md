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
Untuk mode development
```bash
npm run dev
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

## 7, Lampiran

- **Flowchart + Panduan Pengguna (PDF)** — https://drive.google.com/file/d/1P9bYps2saGEFZdPPBi6oBqsH-uwXTUNs/view?usp=sharing
- **Template CSV Pegawai** — https://drive.google.com/file/d/1cK40atDtofidZO9b5VnuJyfx8k6Lsyoa/view?usp=sharing
- **CSV Provinsi** — https://drive.google.com/file/d/1jYid6JMtIua7YZPoZzitW9u3P8Kk7NV2/view?usp=sharing
- **CSV Kota** — https://drive.google.com/file/d/1A5OOoSgOFDpW7RwGtVvF7l7_B72ggXHn/view?usp=sharing
- **Contoh File Kop Surat** — https://drive.google.com/file/d/1u-XURR1WTupR_CQtJSstqrG9tSP9ZpLP/view?usp=sharing

