# 📦 Sistem Barang – Praktikum 10 PHP OOP

Aplikasi Sistem Manajemen Data Barang berbasis PHP Modular (OOP) menggunakan MySQL (mysqli).
Aplikasi ini merupakan lanjutan dari Praktikum 9 dengan penerapan struktur modular, login, session, dan keamanan dasar.

## 👤 Identitas Praktikum

- Mata Kuliah : Pemrograman Web

- Praktikum : Praktikum 10 – PHP OOP

- Topik : Sistem Barang (CRUD + Login)

- Framework : Native PHP (Tanpa Framework)

- Database : MySQL

- Server : XAMPP

## ✨ Fitur Aplikasi

### - 🔐 Login & Logout (Session)

### - 📋Dashboard

### - 📦 Manajemen Data Barang

- Tambah Barang

- Ubah Barang

- Hapus Barang

- Upload Gambar Produk

### - 🛡️ Keamanan Dasar

- Prepared Statement (mysqli)

- CSRF Token

- Session Protection

### - 🎨 UI Sederhana & Rapi

- Card Layout

- Form Terpusat

- Responsive

## 🗂️ Struktur Folder
```
LAB10_PHP_OOP/
│
├── assets/
│   ├── css/
│   │   └── style.css
│   │
│   ├── img/
│   │   ├── hp_oppo.jpg
│   │   ├── hp_samsung.jpg
│   │   └── hp_xiaomi.jpg
│   │
│   └── js/
│       └── main.js
│
├── config/
│   ├── database.php
│   └── DatabaseClass.php
│
├── modules/
│   ├── auth/
│   │   ├── login.php
│   │   └── logout.php
│   │
│   └── user/
│       ├── hapus.php
│       ├── list.php
│       ├── tambah.php
│       ├── ubah.php
│       └── form.php
│
├── views/
│   ├── dashboard.php
│   ├── header.php
│   └── footer.php
│
└── index.php
```

## 🗄️ Struktur Database
### Database
```
CREATE DATABASE latihan1;
USE latihan1;
```
### Tabel Users
```
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50),
  password_hash VARCHAR(255)
);
```
### Tabel Data Barang
```
CREATE TABLE data_barang (
  id_barang INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  kategori VARCHAR(50),
  gambar VARCHAR(255),
  harga_beli INT,
  harga_jual INT,
  stok INT
);
```

## 🖼️ Screenshot Dokumentasi
### 1️⃣ Halaman Login
Menampilkan form login untuk autentikasi pengguna sebelum masuk ke sistem.
![foto](https://github.com/dirarohmaeni/Lab10Web/blob/4fa24036a367398b1cd6cf8c7c9d895531ea1bcb/lab10/login.png)

### 2️⃣ Dashboard
Menampilkan ringkasan sistem dan navigasi utama.
![foto](https://github.com/dirarohmaeni/Lab10Web/blob/3553d8e05eb51ac48d21145188f4b05e77322caf/dashboard.png)

### 3️⃣ Data Barang
Menampilkan daftar barang lengkap dengan gambar produk, harga, stok, dan aksi.
![foto](https://github.com/dirarohmaeni/Lab10Web/blob/4fa24036a367398b1cd6cf8c7c9d895531ea1bcb/lab10/list.png)

### 4️⃣ Tambah Barang
Form untuk menambahkan data barang baru beserta upload gambar.
![foto](https://github.com/dirarohmaeni/Lab10Web/blob/4fa24036a367398b1cd6cf8c7c9d895531ea1bcb/lab10/tambah.png)

### 5️⃣ Ubah Barang
Form edit data barang dengan tampilan yang sama seperti tambah barang.
![foto](https://github.com/dirarohmaeni/Lab10Web/blob/4fa24036a367398b1cd6cf8c7c9d895531ea1bcb/lab10/ubah.png)

## ⚙️ Cara Menjalankan Aplikasi

1. Jalankan Apache & MySQL di XAMPP

2. Import database ke phpMyAdmin

3. Pastikan folder berada di:
```
C:\xampp\htdocs\lab10_php_oop
```
4. lab10_php_oop
```
http://localhost/lab10_php_oop
```

## 🧪 Catatan Pengembangan

- File tes_koneksi.php & generate_hash.php boleh dihapus setelah aplikasi berjalan

- Aplikasi menggunakan mysqli procedural

- Tidak menggunakan framework eksternal

- Cocok untuk pembelajaran konsep modular PHP

© 2025 – Praktikum 10 PHP OOP - Universitas Pelita Bangsa
