Proyek Web Transaksi (Stok & Penjualan)
Proyek ini adalah aplikasi web sederhana yang berfungsi sebagai sistem kasir (Point of Sale) untuk mengelola stok, mencatat transaksi penjualan, dan menghasilkan laporan. Aplikasi ini dibangun menggunakan framework CodeIgniter 4 dan terbagi menjadi dua peran utama: Admin dan Konsumen.

Tujuan Proyek
Proyek ini bertujuan untuk menyediakan solusi lengkap bagi UMKM untuk mengelola bisnis mereka secara digital, mulai dari manajemen inventaris hingga pencatatan transaksi dan pelaporan keuangan.

Fitur Utama
Manajemen Produk & Kategori: Mengelola data barang, harga, diskon, stok, dan kategori.

Manajemen Stok: Mencatat proses restok (penerimaan barang dari supplier) dan retur (pengembalian barang).

Pembelian Online: Konsumen bisa memilih barang dan memproses pembayaran.

Integrasi Pembayaran: Menggunakan Xendit untuk memproses pembayaran secara online.

Pelaporan Lengkap: Menghasilkan berbagai laporan dalam format PDF dan Excel, serta mengunggahnya ke Google Drive.

Autentikasi: Mendukung login dengan akun Google.

Alur Penggunaan
1. Alur untuk Admin
Sebagai Admin, Anda dapat mengelola seluruh operasional toko.

Dashboard: Setelah login, Anda akan melihat ringkasan penjualan harian, jumlah transaksi baru, dan daftar produk dengan stok menipis.

Manajemen Barang: Anda dapat menambah, mengubah, menghapus, dan melihat daftar barang. Ada juga fitur untuk impor data barang dari file Excel.

Manajemen Stok: Anda dapat mencatat restok dari supplier dan mengonfirmasi barang yang sudah diterima. Anda juga dapat mengelola data supplier (restoker).

Laporan: Sistem dapat membuat laporan dalam format PDF dan Excel, seperti laporan penjualan harian, mingguan, produk terlaris, dan laba/rugi. Laporan ini dapat langsung diunggah ke Google Drive Anda.

2. Alur untuk Konsumen
Sebagai Konsumen, Anda dapat melakukan pembelian barang.

Menu Pembelian: Anda dapat melihat daftar produk, menambahkan produk ke keranjang belanja, dan memilih beberapa produk sekaligus.

Checkout: Anda mengisi nama dan nomor telepon, lalu sistem akan membuat invoice pembayaran melalui Xendit. Setelah pembayaran berhasil, stok barang akan otomatis berkurang.

Riwayat: Anda dapat melihat semua riwayat pembelian yang pernah Anda lakukan dan mencetak struk transaksi.

Pengembalian: Anda dapat mengajukan retur untuk barang yang sudah dibeli, dan statusnya akan diperbarui oleh admin.

Cara Memasang Proyek
Persyaratan Sistem
PHP: Versi 8.1 atau lebih tinggi.

Web Server: Apache atau Nginx.

Database: MySQL, MariaDB, atau PostgreSQL.

Composer: Untuk mengelola dependensi PHP.

Ekstensi PHP: ext-intl, ext-mbstring, ext-dom, ext-zip, dan ext-zlib.

Langkah-langkah Instalasi
Clone Repositori:

git clone [URL_repositori_Anda]
cd [nama_folder]

Instal Dependensi:

composer install

Konfigurasi Lingkungan:

Salin file .env.example menjadi .env.

Buka file .env dan atur variabel-variabel berikut:

# Konfigurasi Database
database.default.hostname = 'localhost'
database.default.username = 'your_username'
database.default.password = 'your_password'
database.default.database = 'your_database_name'

# Kunci API Xendit untuk Pembayaran
XENDIT_SECRET_KEY = 'your_xendit_secret_key'
XENDIT_CALLBACK_TOKEN = 'your_xendit_callback_token'

# Konfigurasi Google OAuth untuk Login & Google Drive
GOOGLE_CLIENT_ID = 'your_google_client_id'
GOOGLE_CLIENT_SECRET = 'your_google_client_secret'
GOOGLE_REDIRECT_URI = 'your_google_redirect_uri'

# Konfigurasi Email
email.SMTPUser = 'your_email@gmail.com'
email.SMTPPass = 'your_app_password'

Jalankan Migrasi Database:

Pastikan Anda sudah membuat database kosong.

Jalankan perintah migrasi untuk membuat tabel-tabel yang diperlukan:

php spark migrate

Jalankan Aplikasi:

php spark serve

