# Nia's Bucket (PHP + MySQL + Bootstrap 5)

Bagian website: header, navbar, hero (beranda), koleksi produk (tombol Pesan membuka form pembelian), tentang kami, kontak. Data pesanan masuk ke database dan tampil di dashboard admin.

## Pasang di XAMPP
1. Ekstrak folder `nias_bucket` ke `C:\xampp\htdocs\nias_bucket`. Jalankan **Apache** dan **MySQL**.
2. Buka `http://localhost/phpmyadmin` > tab **Import** > pilih `database/sekar_florist.sql` > **Import**. Database `sekar_florist` dibuat otomatis.
3. Buka website: `http://localhost/nias_bucket/`
4. Login admin: `http://localhost/nias_bucket/admin/login.php`, username `admin`, kata sandi `Sekar2026!`. Segera ganti lewat menu **Ganti sandi**.

## Nama database dan tabel
Database: `sekar_florist`

| Tabel | Isi | Kolom |
|---|---|---|
| `admin` | akun admin | id, username, password_hash, created_at |
| `customers` | akun pelanggan | id, nama, email, telepon, password_hash, created_at |
| `bouquets` | produk (buket) | id, nama, deskripsi, harga, gambar, aktif, created_at |
| `purchases` | pesanan | id, pelanggan_id, nama_pelanggan, telepon, produk_id, jumlah, harga_satuan, total_harga, tanggal_pengambilan, alamat, catatan, status, created_at |

## Yang harus diganti kalau berbeda dengan phpMyAdmin kamu
1. **Nama database / user / password:** edit `config/database.php` (`$dbname`, `$username`, `$password`). Contoh: database kamu bernama `buket` maka `$dbname = 'buket';`.
2. **Nama tabel:** query memakai `bouquets`, `purchases`, `customers`, `admin`. Kalau tabelmu bernama lain, ganti di file ini: `index.php`, `masuk.php`, `pembelian.php`, `pesanan_saya.php`, `simpan_pembelian.php`, `admin/dashboard.php`, `admin/produk.php`, `admin/hapus_produk.php`, `admin/toggle_produk.php`, `admin/ubah_status.php`, `admin/pembeli.php`, `admin/password.php`, `admin/login.php`. Cari kata `FROM`, `JOIN`, `INTO`, `UPDATE`.
3. **Nama kolom:** harus persis sama dengan tabel di atas, termasuk ejaan.
4. **Error umum:** *Unknown database* = impor SQL dulu atau samakan `$dbname`. *Table doesn't exist* = nama tabel beda. *Unknown column* = nama kolom beda. *Access denied* = user/password MySQL salah.
5. **Kontak:** ganti nomor WhatsApp dan Instagram di `index.php` (bagian `id="kontak"`).

## Dashboard admin
- `admin/dashboard.php`: jumlah pesanan, produk, pembeli, pendapatan, daftar pesanan, ubah status.
- `admin/pembeli.php`: data pembeli (nama, WhatsApp, apa yang dibeli, total belanja).
- `admin/produk.php`: tambah, edit (nama, harga, deskripsi), ganti foto, sembunyikan, hapus. Perubahan langsung tampil di website. Foto tersimpan di `assets/img/produk/`. Produk awal memakai gambar di `assets/img/seed/` yang bisa diganti kapan saja dari dashboard.
- Produk yang sudah pernah dipesan tidak bisa dihapus (agar riwayat aman). Gunakan Sembunyikan.

## Sebelum online
Ganti kata sandi admin, gunakan HTTPS, dan jangan tampilkan error database ke pengunjung.
