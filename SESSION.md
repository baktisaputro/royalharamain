# Sesi Kerja Royal Haramain

File ini adalah catatan sesi. Bacalah untuk melanjutkan pekerjaan dari titik terakhir.

## Objective
Membangun kembali website royalharamain (travel haji/umroh) dari statis Vercel menjadi PHP + MySQL di shared hosting cPanel, dilengkapi panel admin ber-level (super admin/admin/viewer), tab edit hero, upload gambar lokal + tema warna seluruh website yang dikelola dari admin. Slideshow hero + popup daftar sudah diimplementasikan.

## Status Terakhir (saat sesi berakhir)
- Fix: Foto "Tentang Kami" tidak lagi bergantung Unsplash (ganti ke foto upload lokal, bisa diatur dari admin).
- Fix: Section "Dokumentasi Perjalanan" → preview akun Instagram (@royalharamainbantul) dengan grid foto perjalanan.
- Fitur baru: Keunggulan/Layanan, Galeri Foto, dan Halaman Kontak + form pesan (inbox) — SEMUA TERUJI & di-commit/push.
- Slideshow hero 5 gambar (slide_1..5.webp) berjalan, overlay gelap ditambahkan agar teks terbaca.
- Kotak `.hero-image` (gambar kanan yang mengulang slide) DIHAPUS.
- Tombol "Daftar 1-Klik" di header DIHAPUS (desktop & mobile).
- Tombol "Daftar Sekarang" kini ada di: badges-strip (section setelah hero), tengah hero, dan tombol paket. Semua membuka POPUP booking.
- Section form `#daftar` DIHAPUS; form dipindah ke popup booking (`#bookModal`), auto-terbuka setelah submit.
- Nav mobile (`☰`) sekarang membuka menu, bukan form.
- Tema DB dikembalikan ke emerald-gold (#046a38 / #023d1f / #d4af37).
- Perubahan slideshow + hero/popup BELUM di-commit/push ke GitHub.

## Important Details
- Teknologi: PHP 8.0.30 + MariaDB 10.4.32 (XAMPP lokal). Target deploy: shared hosting cPanel (PHP+MySQL).
- Repo GitHub: `baktisaputro/royalharamain` (branch `master`), clone di `C:\Users\IESPA 000\royalharamain`.
- Git identity lokal repo: name `baktisaputro`, email `baktisaputro@users.noreply.github.com`.
- Junction lokal: `C:\xampp\htdocs\royalharamain` → folder repo. BASE_URL = `http://localhost/royalharamain`.
- Kredensial lokal: DB `royalharamain` (user `root`, password kosong). Login admin: `superadmin` / lihat `.credentials.local` (password sudah di-set & diverifikasi; jangan commit plaintext password ke GitHub).
- 3 level akses: `super_admin` (kelola user+semua), `admin` (kelola konten), `viewer` (read-only). Panel admin gaya "netral modern".
- Tema: preset + color picker (keduanya), berlaku seluruh website. Warna dasar logo: emerald #046a38, emerald-dark #023d1f, gold #d4af37.
- GD aktif (`extension=gd` di `C:\xampp\php\php.ini`), terverifikasi web mode.
- Git last push: commit `eb47c0b` (slideshow hero 5 gambar + overlay, hero 1 kolom, popup booking, upload gambar hero).
- Sistem lama (Vercel/Supabase) masih ada sebagai arsip: `index.html`, `admin.html`, `js/supabaseClient.js`, `supabase/schema.sql`, `vercel.json`.
- PowerShell 5.1 `Invoke-WebRequest` tidak punya `-Form`; multipart upload diuji via `curl.exe`.

## Work State

### Completed
- Fix Tentang Kami: foto dari `settings.about_image` (lokal), fallback logo; URL Unsplash dihapus dari section publik.
- Section Galeri diubah jadi preview profil Instagram: header akun (avatar, handle, stats, bio, tombol Ikuti → `instagram.com/<handle>`) + grid foto 3 kolom dari `gallery_images`. Handle IG & foto tentang bisa diubah di panel Hero (tab hero → card "Tentang Kami & Instagram", upload ke `uploads/tentang/`).
- `settings` + kolom `about_image`, `instagram_handle` (database.sql & lokal); tema DB dikembalikan ke emerald-gold.
- Fitur Keunggulan/Layanan (tabel `features`), Galeri Foto (tabel `gallery_images`, upload via `handle_image_upload` ke `uploads/galeri/`), Kontak/Inbox (tabel `contact_messages` + form publik + panel admin `kontak.php` + detail modal).
- Panel admin baru: `admin/panels/fitur.php`, `admin/panels/galeri.php`, `admin/panels/kontak.php`; tab & menu terdaftar di `admin/index.php` (peran viewer read-only).
- Section publik baru di `index.php`: `#keunggulan` (grid `.features`), `#galeri` (grid `.gallery-grid`), `#kontak` (layout `.contact-wrap`, form pesan → tabel `contact_messages`).
- `.credentials.local` (gitignored) menyimpan password admin & DB lokal — jangan commit.
- XAMPP aktif, GD aktif. DB `royalharamain` + `database.sql` (roles, admin_users, hero_content, packages, package_facilities, articles, leads, promos, settings).
- Seed super admin + bcrypt valid; seed 3 paket + 3 artikel + tema emerald-gold.
- Backend PHP lengkap & teruji: `app/config.php`, `app/upload.php`; `admin/login.php`, `admin/logout.php`, `admin/index.php` (tab per role); panels `hero.php`, `paket.php`, `artikel.php`, `leads.php`, `promo.php`, `users.php`, `profil.php`.
- Halaman publik `index.php` dinamis (hero, paket+fasilitas, artikel, popup promo) dengan CSS `style_pub.css`.
- Uji: login superadmin, pembatasan role, simpan leads, tambah paket, ganti password, simpan tema, upload+kompres gambar.
- Bug HY093 diperbaiki (hapus `$vals[]=1`); panel hero ditulis ulang (upload + editor tema preset/custom).
- `.htaccess`, `DEPLOY.md`, `.gitignore` dibuat. Commit `2b82a81` di-push.
- 5 gambar user dikompres jadi `slide_1.webp`..`slide_5.webp` (≈195–340KB, max-width 1920, quality 72).
- Slideshow hero (scan `uploads/hero/slide_*.webp`, CSS animasi `heroFade`, overlay gelap) — DIPERIKSA berjalan.
- Hero 1 kolom center + tombol "Daftar" di akhir; popup booking modal + auto-open setelah submit; mobile nav toggle.

### Active
- (empty)

### Next Move
1. Lanjut go-live: minta kredensial DB cPanel → set `app/config.php` → upload ke `public_html` + import `database.sql` → uji.

### Blocked
- Go-live cPanel belum bisa: belum ada kredensial DB hosting (nama DB, user, password).
- Tidak bisa verifikasi visual gambar (tanpa dukungan input gambar) — mengandalkan pilihan user.
- Perlu konfirmasi visual user atas tampilan baru hero/popup di browser sebelum commit.

## Next Move
1. Konfirmasi visual kolaborator atas hasil tampilan baru (slideshow + overlay + popup) di `http://localhost/royalharamain/`. (Sudah dikonfirmasi & di-commit pada `eb47c0b`.)
2. Lanjut go-live: minta kredensial DB cPanel → set `app/config.php` → upload ke `public_html` + import `database.sql` → uji.

## Relevant Files
- `C:\Users\IESPA 000\royalharamain\index.php`: halaman publik; slideshow (`$hero_slides`, `.hero-slide`, `.hero-overlay`), hero 1 kolom + tombol daftar, popup booking `#bookModal`, JS `openBooking/closeBooking/toggleNav`.
- `C:\Users\IESPA 000\royalharamain\admin\panels\hero.php`: form edit hero + upload + editor tema.
- `C:\Users\IESPA 000\royalharamain\app\config.php`: kredensial DB + BASE_URL.
- `C:\Users\IESPA 000\royalharamain\app\upload.php`: helper upload+kompres (max 5MB, resize 1600px, WebP q78).
- `C:\Users\IESPA 000\royalharamain\database.sql`: skema + seed + tema (untuk import hosting).
- `C:\Users\IESPA 000\royalharamain\DEPLOY.md`: panduan deploy cPanel.
- `C:\Users\IESPA 000\royalharamain\css\style_pub.css`: styling publik (hero, badges, popup, responsive).
- `C:\Users\IESPA 000\royalharamain\css\admin_panel.css`: styling admin.
- `C:\Users\IESPA 000\royalharamain\uploads\hero\`: `slide_1.webp`..`slide_5.webp` (dipakai slideshow), `1 (1).webp`..`1 (5).webp` (orisinil user, di-gitignore), file test dihapus.
- `C:\Users\IESPA 000\royalharamain\SESSION.md`: file ini.
- `C:\xampp\mysql\bin\mysql.exe` & `C:\xampp\php\php.exe`: CLI untuk query DB & uji PHP.

## Kredensial & Akses
- Kredensial lokal (username/password admin & DB) tersimpan di `.credentials.local` (gitignored, RAHASIA — jangan commit/push).
- Admin lokal: `http://localhost/royalharamain/admin/login.php` (username & password lihat `.credentials.local`).
- Supabase lama: `https://yxdpovmnkxpqdudgwtaz.supabase.co` (arsip).