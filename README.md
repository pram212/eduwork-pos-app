<p align="center">
    <a href="http://evening-citadel-97574.herokuapp.com/payments#" target="_blank">
        <h1>Aplikasi Point Of Sales (Kasir)</h1>
    </a>
</p>

# Tentang Aplikasi

Aplikasi ini saya buat untuk memenuhi tugas akhir saat mengikuti bootcamp di eduwork.id kelas laravel vue.js Aplikasi ini merupakan sistem informasi yang berfungsi untuk mengelola penjualan dan pembelian. Dibuat dengan laravel dan vue.js disertai juga dengan ajax.

# Teknologi

- [Laravel](https://laravel.com).
- [Vue.js](https://vuejs.org/).
- [Bootstrap](https://getbootstrap.com/).
- [jQuery](https://laravel.com/docs/eloquent).
- [PHP](https://laravel.com/docs/migrations).
- [MySQL](https://laravel.com/docs/queues).
- [Javascript](https://laravel.com/docs/broadcasting).
- [Axios](https://laravel.com/docs/broadcasting).

# Persiapan Instalasi
Sebelum menginstal aplikasi pastikan bahwa di  komputer kamu sudah terinstal:
- composer
- php versi >= 7.4
- mySQL
- web server (Xampp)
- git
- jaringan internet

# Instalasi
- download aplikasi. 
Boleh menggunakan git dengan "git clone " atau download zip.
- nyalakan web server
- jika kamu menggunakan xampp, buka phpmyadmin dan buat database baru
- buka aplikasi di code editor
- buat file .env (copy saja dari file .env.example)
- buka file .env yang sudah dibuat
- ubah DB_DATABASE dengan nama database yang sudah dibuat di phpmyadmin
- ubah DB_USERNAME dan DB_PASSWORD, sesuaikan dengan username dan password phpmyadmin kamu
- buka terminal dan arahkan ke root directory aplikasi
- jalankan perintah "composer install"
- jalankan perintah "php artisan key:generate"
- jalankan perintah "php artisan migrate --seed"
- jalankan aplikasi dengan perintah "php artisan serve"
- buka browser kemudian akses http://localhost:8000

# Akses Login
### Superadmin
- username : pramono@mail.com
- password : pramono
### Admin 
- username : dimas@mail.com
- password : dimas
### Kasir 
- username : iwan@mail.com
- password : iwan

# Fitur
- Manajemen Produk
- Manajemen Penjualan
- Manajemen Pembelian
- Manajemen User
- Manajemen Hak Akses User
- Monitor Aktifitas User
