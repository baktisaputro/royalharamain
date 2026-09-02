-- ============================================================
-- Royal Haramain Internasional - Database Schema (MySQL)
-- Persiapan: pindah total ke shared hosting cPanel (PHP + MySQL)
-- Cara pakai:
--   1. Buka cPanel > phpMyAdmin > pilih database Anda
--   2. Tab "Import" > pilih file database.sql ini > Go
--   3. Ganti username/password super admin di kolom paling bawah
--      (tabel admin_users) sebelum go-live!
-- ============================================================

SET NAMES utf8mb4;

-- ------------------------------------------------------------
-- Tabel ROLE / LEVEL AKSES
-- id 1 = super_admin (tertinggi, bisa semua fitur & kelola admin)
-- id 2 = admin       (bisa kelola konten, tidak bisa kelola user)
-- id 3 = viewer      (hanya bisa melihat data, tidak bisa edit)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS roles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(32) NOT NULL UNIQUE,
  name VARCHAR(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO roles (id, slug, name) VALUES
  (1, 'super_admin', 'Super Admin'),
  (2, 'admin',       'Admin'),
  (3, 'viewer',      'Viewer')
ON DUPLICATE KEY UPDATE name = VALUES(name);

-- ------------------------------------------------------------
-- Tabel ADMIN USERS (siapa yang boleh masuk panel admin)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  role_id INT NOT NULL DEFAULT 2,
  name VARCHAR(100) NOT NULL,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(120) NULL,
  password_hash VARCHAR(255) NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_admin_role FOREIGN KEY (role_id)
    REFERENCES roles(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabel SETTINGS (tema warna website + pengaturan global)
-- id 1 = tema website
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS settings (
  id INT PRIMARY KEY,
  theme_preset VARCHAR(32) NOT NULL DEFAULT 'emerald-gold',
  primary_color VARCHAR(20) NOT NULL DEFAULT '#046a38',
  secondary_color VARCHAR(20) NOT NULL DEFAULT '#023d1f',
  accent_color VARCHAR(20) NOT NULL DEFAULT '#d4af37',
  about_image VARCHAR(500) NULL,
  instagram_handle VARCHAR(60) NULL,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO settings (id, theme_preset, primary_color, secondary_color, accent_color, about_image, instagram_handle)
VALUES (1, 'emerald-gold', '#046a38', '#023d1f', '#d4af37', 'uploads/hero/slide_1.webp', 'royalharamainbantul')
ON DUPLICATE KEY UPDATE id = id;

-- ------------------------------------------------------------
-- Tabel HERO PAGE (konten hero dikustomisasi via admin)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS hero_content (
  id INT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  subtitle VARCHAR(255),
  quote TEXT,
  quote_source VARCHAR(255),
  background_image VARCHAR(500),
  hero_image_path VARCHAR(255),
  primary_btn_text VARCHAR(100),
  primary_btn_url VARCHAR(255),
  secondary_btn_text VARCHAR(100),
  secondary_btn_url VARCHAR(255),
  badge_line VARCHAR(255),
  legal_badges TEXT,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO hero_content (id, title, subtitle, quote, quote_source, background_image,
  primary_btn_text, primary_btn_url, secondary_btn_text, secondary_btn_url, badge_line, legal_badges)
VALUES (1,
  'Travel Haji, Umroh dan Halal Tours',
  'Resmi, Amanah & Berizin Kemenag RI',
  'Ikutkanlah umroh kepada haji, karena keduanya menghilangkan kemiskinan dan dosa-dosa sebagaimana pembakaran menghilangkan karat pada besi, emas, dan perak. Sementara tidak ada pahala bagi haji yang mabrur kecuali surga.',
  'HR. An Nasai, Tirmidzi dan Ahmad',
  'https://images.unsplash.com/photo-1591604466107-ec97de577aff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80',
  'Konsultasi Gratis', '#lokasi',
  'Lihat Paket', '#paket',
  'Wujudkan Niat Suci Tanpa Hambatan',
  '["AMPHURI","PIHK No. 394","PPIU No. U.533","Kemenag","Siskopatuh"]')
ON DUPLICATE KEY UPDATE id = id;

-- ------------------------------------------------------------
-- Tabel PAKET UMROH / HAJI
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS packages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  price VARCHAR(120) NOT NULL,
  price_old VARCHAR(120),
  duration VARCHAR(50),
  badge VARCHAR(80),
  featured TINYINT(1) NOT NULL DEFAULT 0,
  image_url VARCHAR(500),
  url VARCHAR(255) DEFAULT '#',
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Fasilitas paket (1 paket -> banyak fasilitas)
CREATE TABLE IF NOT EXISTS package_facilities (
  id INT AUTO_INCREMENT PRIMARY KEY,
  package_id INT NOT NULL,
  facility VARCHAR(255) NOT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  CONSTRAINT fk_pf_package FOREIGN KEY (package_id)
    REFERENCES packages(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed paket contoh (dapat diedit via admin)
INSERT INTO packages (title, price, price_old, duration, badge, featured, image_url, url, sort_order, is_active) VALUES
 ('Umrah VIP Reguler', 'Rp 28.5 Juta', NULL, '9 Hari', NULL, 0,
  'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=800&auto=format&fit=crop', '#', 1, 1),
 ('Umrah Plus — Nabawi', 'Rp 39 Juta', 'Rp 42 Juta', '12 Hari', 'PALING DIMINATI', 1,
  'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=800&auto=format&fit=crop', '#', 2, 1),
 ('Haji Khusus', 'Rp 65 Juta', NULL, '25 Hari', NULL, 0,
  'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=800&auto=format&fit=crop', '#', 3, 1);

INSERT INTO package_facilities (package_id, facility, sort_order) VALUES
 (1, 'Hotel Bintang 4 (Makkah & Madinah)', 1),
 (1, 'Pesawat Garuda / Saudia', 2),
 (1, 'Muthawwif Berpengalaman', 3),
 (1, 'Visa Umroh & Perlengkapan', 4),
 (2, 'Hotel Bintang 5 (Pelataran Masjidil Haram & Nabawi)', 1),
 (2, 'Penerbangan Direct (Saudia)', 2),
 (2, 'Kereta Cepat Haramain', 3),
 (2, 'Eksklusif Lounge & Fast Track', 4),
 (2, 'City Tour Madinah & Ziarah Nabawi', 5),
 (3, 'Hotel Bintang 5 (Makkah & Madinah)', 1),
 (3, 'Muthawwif & Pembimbing Haji', 2),
 (3, 'Visa Haji & Perlengkapan', 3);

-- ------------------------------------------------------------
-- Tabel ARTIKEL
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  excerpt TEXT NOT NULL,
  date VARCHAR(64),
  image_url VARCHAR(500),
  url VARCHAR(255) DEFAULT '#',
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed artikel contoh (dapat diedit via admin)
INSERT INTO articles (title, excerpt, date, image_url, url, sort_order, is_active) VALUES
 ('Niat & Doa Ihram di Miqat', 'Kumpulan doa shahih saat memulai ihram dari Miqat hingga memasuki Masjidil Haram.', '12 Agustus 2026',
  'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=600&auto=format&fit=crop', '#', 1, 1),
 ('Adab Ziarah Masjid Nabawi', 'Panduan adab dan doa saat ziarah ke Masjid Nabawi dan makam Rasulullah SAW.', '5 Agustus 2026',
  'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=600&auto=format&fit=crop', '#', 2, 1),
 ('Tata Cara Tawaf Mengelilingi K\'abah', 'Panduan lengkap melakukan tawaf 7 putaran beserta doa di setiap putarannya.', '28 Juli 2026',
  'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=600&auto=format&fit=crop', '#', 3, 1);

-- ------------------------------------------------------------
-- Tabel KEUNGGULAN / LAYANAN (section "mengapa memilih kami")
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS features (
  id INT AUTO_INCREMENT PRIMARY KEY,
  icon VARCHAR(64) DEFAULT 'fa-star',
  title VARCHAR(200) NOT NULL,
  description TEXT,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO features (icon, title, description, sort_order, is_active) VALUES
 ('fa-certificate', 'Berizin & Resmi', 'Terdaftar resmi Kemenag RI, AMPHURI, PIHK, dan Siskopatuh.', 1, 1),
 ('fa-hand-holding-heart', 'Melayani Sepenuh Hati', 'Pendampingan kekeluargaan dari pendaftaran hingga kembali ke tanah air.', 2, 1),
 ('fa-plane', 'Penerbangan Terjamin', 'Maskapai terbaik (Garuda / Saudia) dengan rute paling nyaman.', 3, 1),
 ('fa-hotel', 'Hotel Bintang 5', 'Penginapan premium dekat Masjidil Haram & Masjid Nabawi.', 4, 1),
 ('fa-shield-halved', 'Amanah & Terpercaya', 'Lebih dari 15 tahun pengalaman memberangkatkan puluhan ribu jamaah.', 5, 1),
 ('fa-cash-register', 'Pembayaran Mudah', 'Fasilitas cicilan biaya haji & umroh untuk memudahkan Anda.', 6, 1)
ON DUPLICATE KEY UPDATE title = VALUES(title);

-- ------------------------------------------------------------
-- Tabel GALERI FOTO (upload & kelola dari admin)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS gallery_images (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(500) NOT NULL,
  caption VARCHAR(255) DEFAULT '',
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabel KONTAK / PESAN MASUK (dari form kontak website)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(120),
  phone VARCHAR(50),
  subject VARCHAR(200),
  message TEXT NOT NULL,
  status ENUM('baru','dibaca','selesai') NOT NULL DEFAULT 'baru',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabel LEADS (data pendaftar dari form website)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS leads (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  phone VARCHAR(50) NOT NULL,
  email VARCHAR(120),
  city VARCHAR(100),
  package VARCHAR(150),
  guests INT DEFAULT 1,
  departure VARCHAR(100),
  notes TEXT,
  wa_link VARCHAR(255),
  status ENUM('baru','dihubungi','selesai') NOT NULL DEFAULT 'baru',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------------------------------------------------
-- Tabel PROMO (popup)
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS promos (
  id INT PRIMARY KEY,
  enabled TINYINT(1) NOT NULL DEFAULT 0,
  badge VARCHAR(100),
  title VARCHAR(200),
  message TEXT,
  image_url VARCHAR(500),
  link VARCHAR(255),
  delay INT DEFAULT 3,
  show_once TINYINT(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- SEED SUPER ADMIN (role_id = 1)
-- !! PENTING: ganti username & password_hash sebelum go-live !!
-- username : superadmin
-- password : PASSWORD123  (ganti segera!)
--   (password_hash = sha1 dari password, karena memakai PHP
--    password_verify dengan format bcrypt juga bisa. Di bawah
--    dicontohkan hash bcrypt dari: "PASSWORD123")
-- ============================================================
-- Untuk membuat hash bcrypt, jalankan script PHP berikut:
--   echo password_hash("PASSWORD123", PASSWORD_DEFAULT);
-- lalu tempel hasilnya di bawah ini.
INSERT INTO admin_users (role_id, name, username, email, password_hash, is_active)
VALUES (1, 'Super Admin', 'superadmin', 'superadmin@royalharamain.co.id',
  '$2y$10$e0MYzXyjpJS7Pd0RVvHwHeFxGyRZisPDNNxRQy1HcF0jT0zT8O3SO', 1)
ON DUPLICATE KEY UPDATE username = username;
