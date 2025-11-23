SIMPPD — Sistem Informasi Manajemen Pengawasan Perjalanan Dinas

Sistem berbasis CodeIgniter 4 untuk mengelola pengawasan perjalanan dinas secara terstruktur, modern, dan mudah digunakan.

Requirements

CodeIgniter 4

PHP 8.1+

Composer

MySQL / MariaDB

TailwindCSS 4 (CLI)

Node.js + npm

XAMPP (Apache + MySQL)

Installation Guide
1. Clone Repository

Via Git

cd C:\xampp\htdocs
git clone https://github.com/naufalsetiawan/SIMPPD
cd SIMPPD


Atau Upload Manual

Download ZIP dari GitHub

Extract ke: C:\xampp\htdocs\SIMPPD

2. Install PHP Dependencies (Composer)
composer install

3. Install Frontend Dependencies
npm install
npm run build

4. Copy & Konfigurasi File .env
cp env .env


Kemudian edit .env:

CI_ENVIRONMENT = development

database.default.hostname = localhost
database.default.database = nama_database
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi

5. Migrasi Database
php spark migrate


Jika menggunakan seeder:

php spark db:seed NamaSeeder

Catatan Keamanan (PENTING!)

Seeder akan membuat akun admin default dengan username dan password bawaan.

👉 Sebelum menjalankan seeder, ubah password admin default terlebih dahulu.

Buka file seeder:
app/Database/Seeds/AdminSeeder.php

Cari bagian berikut:
'username' => 'admin',
'password' => password_hash('admin123', PASSWORD_DEFAULT),
'id_pegawai' => NULL,
'created_at' => $currentTimestamp,

Ubah menjadi:
'password' => password_hash('password_baru_anda', PASSWORD_DEFAULT),


⚠️ Sangat disarankan mengganti password sebelum seeding
karena akun admin biasanya hanya satu dan tidak ada halaman GUI untuk manajemen admin.

6. Jalankan Project
php spark serve


Akses melalui browser:

http://localhost:8080
