Proyek Web Transaksi (Stok & Penjualan)
Proyek ini adalah aplikasi web kasir sederhana yang dikembangkan dengan framework CodeIgniter 4. Aplikasi ini mencakup fungsionalitas untuk mengelola stok barang, mencatat transaksi penjualan, mengelola pengembalian barang, serta menghasilkan laporan penjualan harian dan mingguan.

Fitur Utama
Manajemen Barang (Admin):

CRUD (Create, Read, Update, Delete) barang.

Impor data barang dari file Excel.

Transaksi Pembelian (Konsumen):

Menampilkan daftar barang yang tersedia.

Menambahkan barang ke keranjang belanja.

Memproses pembelian dan mengurangi stok secara otomatis.

Riwayat Transaksi:

Melihat riwayat pembelian oleh konsumen.

Melihat riwayat transaksi penjualan oleh admin.

Manajemen Pengembalian:

Konsumen dapat mengajukan pengembalian (retur) barang.

Admin dapat memproses pengembalian dan mengembalikan stok barang.

Laporan Penjualan (Admin):

Menghasilkan laporan penjualan dalam format PDF dan Excel.

Menampilkan grafik penjualan harian dan mingguan.

Persyaratan Sistem
PHP versi 7.4 atau lebih tinggi

Ekstensi PHP: intl, mbstring, php-dom

Web Server (Apache atau Nginx)

Database (MySQL, MariaDB, atau PostgreSQL)

Composer

Cara Memasang
Clone Repositori:

git clone [https://github.com/USERNAME/NAMA_REPOS_ANDA.git](https://github.com/USERNAME/NAMA_REPOS_ANDA.git)
cd NAMA_REPOS_ANDA

Instal Dependensi Composer:

composer install

Konfigurasi File .env:

Salin file env menjadi .env.

cp env .env

Buka file .env dan atur konfigurasi database Anda.

Jalankan Migrasi Database:

Buat database kosong dan jalankan migrasi.

php spark migrate

Jalankan Aplikasi:

php spark serve

Aplikasi akan berjalan di http://localhost:8080.

Penggunaan
Admin: Akses fitur admin melalui rute http://localhost:8080/admin/items.

Konsumen: Akses fitur pembelian melalui rute http://localhost:8080/konsumen/pembelian.

Kontribusi
Kontribusi dalam bentuk apapun sangat dihargai. Jika Anda ingin berkontribusi, silakan fork repositori ini, buat branch baru, dan kirimkan Pull Request.