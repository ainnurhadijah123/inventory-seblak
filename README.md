# Seblak Ronyok - Inventory & Sales Management System

Seblak Ronyok adalah aplikasi sistem kasir (Point of Sales) dan manajemen inventaris berbasis web yang dibangun menggunakan **Laravel**. Proyek ini memiliki fokus utama pada konsistensi data stok, keamanan transaksi, dan manajemen hak akses (Role-Based Access Control).

## 1. Project Overview
Aplikasi ini memungkinkan warung Seblak Ronyok untuk mencatat master data (produk, kategori, pemasok) dan mengelola transaksi harian (penjualan ke pelanggan dan restok dari pemasok). Fitur utamanya adalah **pembaruan stok otomatis** yang aman dari bentrokan data (menggunakan database transaction dan lock).

## 2. Requirements
Pastikan sistem Anda telah menginstal perangkat lunak berikut:
*   PHP >= 8.1
*   Composer
*   MySQL / MariaDB
*   Node.js & NPM (untuk kompilasi aset frontend)
*   Git

## 3 & 4. Installation & Environment Setup
Langkah-langkah untuk menginstal dan menjalankan proyek ini di lingkungan lokal (bersih):

1. **Clone Repository**
   ```bash
   git clone (https://github.com/ainnurhadijah123/inventory-seblak)
   cd seblak-ronyok
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   npm run build
   ```

3. **Konfigurasi Environment**
   Salin file konfigurasi bawaan dan sesuaikan kredensial database Anda.
   ```bash
   cp .env.example .env
   ```

4. **Generate Application Key**
   ```bash
   php artisan key:generate
   ```

## 5. Migration & Seeding
Untuk membuat tabel dan mengisi data awal (akun demo, kategori, dan produk dummy), jalankan perintah berikut:
```bash
php artisan migrate --seed
```

Setelah itu, jalankan server lokal:
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser di `http://127.0.0.1:8000`.

## 6. Demo Account
Sistem ini menggunakan Role-Based Access.

## 7. Features
*   **Authentication & Authorization:** Login, logout, dan proteksi rute berdasarkan role (Admin, Staff, Customer).
*   **Master Data Management:** CRUD Kategori, Produk, dan Pemasok (Supplier).
*   **Sales Transaction (Penjualan):** Pelanggan dapat memesan menu, sistem akan mengecek stok, dan stok akan berkurang secara atomik (*Database Transaction*).
*   **Purchase Transaction (Pembelian/Restok):** Kasir/Admin dapat mencatat barang masuk dari supplier, stok bertambah otomatis.
*   **Dynamic Dashboard & Chart:** Ringkasan total pendapatan, jumlah produk, peringatan stok menipis, dan grafik pendapatan 7 hari terakhir.
*   **Usability:** Pencarian data (Search), Pagination, dan filter status pesanan.

## 8. Known Limitations
aplikasi ini memiliki beberapa batasan:
*   Tidak terintegrasi dengan Payment Gateway asli (pembayaran dianggap tunai/selesai saat status diubah).
*   Hanya mendukung satu lokasi/gudang utama (Single Warehouse).
*   Fitur cetak struk berbentuk tampilan HTML, belum dioptimalkan untuk printer thermal POS.
