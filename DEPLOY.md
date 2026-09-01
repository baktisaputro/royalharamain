# 🕋 Royal Haramain — Panduan Deploy (cPanel + MySQL)

Selamat, sistem sudah jadi dan teruji di lokal (XAMPP). Dokumen ini memandu
Anda **memindahkan website dari Vercel ke hosting cPanel (PHP + MySQL)**.

> Alur kerja: **Edit di lokal (XAMPP) → upload ke cPanel → selesai.**

---

## 1. Apa yang berubah dari versi lama?

| Area | Sebelum (Vercel) | Sesudah (cPanel) |
|------|------------------|------------------|
| Bahasa | HTML statis + Supabase | **PHP + MySQL** |
| Konten Hero | di file `data.js` | **editable via Admin** (tabel `hero_content`) |
| Paket / Artikel | hardcoded | **editable via Admin** |
| Data pendaftar | Supabase | tabel `leads` di MySQL |
| Login admin | Supabase Auth (email) | **username/password di MySQL** |
| Level akses | tidak ada | **Super Admin / Admin / Viewer** |

---

## 2. Isi database di hosting (wajib di awal)

1. Buka **cPanel → MySQL® Databases**.
2. **Buat database** (misal: `urusr_wisata`) dan **buat user** (+ beri semua hak akses ke database itu).
3. Buka **phpMyAdmin** → pilih database Anda → tab **Import** → pilih file **`database.sql`** dari folder ini → **Go**.
   - Ini membuat semua tabel + data contoh + **akun Super Admin awal**.

4. **⚠️ GANTI PASSWORD SUPER ADMIN** (lihat bagian 4).

---

## 3. Isi kredensial database

Edit file **`app/config.php`**:

```php
define('DB_HOST', 'localhost');        // di cPanel umumnya 'localhost'
define('DB_NAME', 'ganti_dengan_nama_db');   // contoh: urusr_wisata
define('DB_USER', 'ganti_dengan_user');      // contoh: urusr_admin
define('DB_PASS', 'ganti_dengan_password');  // password user db

// Ganti juga URL basis situs Anda:
define('BASE_URL', 'https://www.domain-anda.com');
```

> Path lengkap config: `app/config.php`

---

## 4. Akun Super Admin awal (dari database.sql)

| Field | Nilai awal |
|-------|-----------|
| Username | `superadmin` |
| Password | `PASSWORD123` |

**WAJIB:** segera login lalu ubah password lewat menu **Profil & Password**.
Atau ubah langsung hashic di tabel `admin_users` (pakai tool bcrypt generator).

---

## 5. Upload file ke cPanel

1. Buka **cPanel → File Manager** → masuk ke folder `public_html`.
2. **Hapus** isi lama bila perlu (backup dulu).
3. **Upload** seluruh folder proyek ini ke `public_html`
   (bisa zip lalu extract, atau upload per folder).
   - Pastikan file penting ikut: `index.php`, `admin/`, `app/`, `css/`, `js/`, `assets/`, `.htaccess`, `database.sql`.
   - `.htaccess` (tersembunyi) → cek Settings di File Manager → *Show Hidden Files*.

4. **index.php** otomatis diprioritaskan (via `.htaccess`) sebagai halaman utama,
   bukan `index.html` lama. Hero & konten dibaca dari database.

---

## 6. Akses halaman

| Halaman | URL |
|---------|-----|
| Website publik | `https://domain-anda.com/` |
| Login Admin | `https://domain-anda.com/admin/login.php` |
| Dashboard | `https://domain-anda.com/admin/` |

---

## 7. Level akses (berapa pun pakai panel Admin)

| Level | Bisa apa? |
|-------|-----------|
| **Super Admin** | Semua fitur + kelola admin & level akses (tab *Manajemen Admin*) |
| **Admin** | Kelola konten (hero, paket, artikel, leads, promo) — tidak bisa kelola user |
| **Viewer** | Hanya melihat, tidak bisa mengubah apa pun |

Super Admin menambah/mengubah level via **Admin → Manajemen Admin**.

---

## 8. Di mana letak fitur yang Anda minta?

- **Tab Edit Hero keseluruhan** → Admin → menu **Hero / Beranda**
  (judul, subjudul, kutipan, gambar, tombol, badge legalitas — semua editable).
- **Halaman Super Admin** → Admin → menu **Manajemen Admin**
  (kelola siapa boleh masuk + level, aktif/nonaktif, reset password, hapus).

---

## 9. Development di lokal (XAMPP)

Sudah terpasang. Jalankan **XAMPP Control Panel** → Start **Apache** & **MySQL**.
Halaman lokal: `http://localhost/royalharamain/`

> Kredensial lokal sudah diset di `config.php` (root, kosong, db `royalharamain`).
> Jangan commit `config.php` dengan kredensial hosting asli ke repo publik.

---

## ⚠️ Keamanan (penting!)

- Selalu ganti password bawaan.
- Jangan commit `app/config.php` berisi password asli database ke GitHub publik.
- Pertimbangkan menonaktifkan akun viewer yang tidak terpakai.
- Lakukan backup DB via cPanel secara berkala.
