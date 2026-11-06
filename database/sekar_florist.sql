-- Nia's Bucket | database: sekar_florist
-- Tabel: admin, customers, bouquets, purchases
-- Impor: phpMyAdmin > tab Import > pilih file ini > Import (database dibuat otomatis).
CREATE DATABASE IF NOT EXISTS sekar_florist CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sekar_florist;
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS admin (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(60) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customers (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL UNIQUE,
  telepon VARCHAR(25) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bouquets (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(120) NOT NULL,
  deskripsi TEXT NULL,
  harga DECIMAL(12,2) NOT NULL DEFAULT 0,
  gambar VARCHAR(500) NULL,
  aktif TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS purchases (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  pelanggan_id INT UNSIGNED NULL,
  nama_pelanggan VARCHAR(100) NOT NULL,
  telepon VARCHAR(25) NOT NULL,
  produk_id INT UNSIGNED NOT NULL,
  jumlah INT UNSIGNED NOT NULL DEFAULT 1,
  harga_satuan DECIMAL(12,2) NOT NULL,
  total_harga DECIMAL(12,2) NOT NULL,
  tanggal_pengambilan DATE NULL,
  alamat VARCHAR(500) NOT NULL,
  catatan TEXT NULL,
  status ENUM('Menunggu','Diproses','Selesai','Dibatalkan') NOT NULL DEFAULT 'Menunggu',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_purchases_bouquets FOREIGN KEY (produk_id) REFERENCES bouquets(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Akun admin awal (jika sudah ada, kata sandinya direset ke nilai ini; tidak akan error duplikat): username admin, kata sandi Sekar2026!  (ganti lewat menu Ganti sandi)
INSERT INTO admin (username, password_hash) VALUES ('admin', '$2y$12$WCnhErvJ3b/ZcnE9I7C5UOHBGasFEInBOg2ffTRwvi4EhhmVqveAi')
  ON DUPLICATE KEY UPDATE password_hash = VALUES(password_hash);

-- Produk awal (hanya dimasukkan jika tabel masih kosong, jadi aman diimpor ulang). Kolom gambar berisi file di folder assets/img/seed, bisa diganti dari dashboard.
INSERT INTO bouquets (nama, deskripsi, harga, gambar, aktif)
SELECT t.nama, t.deskripsi, t.harga, t.gambar, t.aktif FROM (
  SELECT 'Buket Rona Muda' AS nama, 'Mawar dan peony merah muda dengan sentuhan kuning lembut.' AS deskripsi, 95000 AS harga, 'assets/img/seed/buket-1.svg' AS gambar, 1 AS aktif
  UNION ALL SELECT 'Buket Wisuda Nia', 'Rangkaian putih dan emas untuk merayakan kelulusan.', 135000, 'assets/img/seed/buket-2.svg', 1
  UNION ALL SELECT 'Buket Lavender Senja', 'Ungu lembut yang menenangkan, cocok untuk hadiah ulang tahun.', 110000, 'assets/img/seed/buket-3.svg', 1
  UNION ALL SELECT 'Buket Mentari', 'Warna jingga hangat yang ceria untuk menyemangati harimu.', 120000, 'assets/img/seed/buket-4.svg', 1
  UNION ALL SELECT 'Buket Putih Suci', 'Bunga putih bersih untuk pernikahan dan momen sakral.', 150000, 'assets/img/seed/buket-5.svg', 1
  UNION ALL SELECT 'Buket Premium Nia', 'Rangkaian besar dengan bunga pilihan untuk momen paling istimewa.', 225000, 'assets/img/seed/buket-6.svg', 1
) AS t
WHERE NOT EXISTS (SELECT 1 FROM bouquets);
