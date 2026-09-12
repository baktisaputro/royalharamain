# Sesi Kerja Royal Haramain

File ini adalah catatan sesi. Bacalah untuk melanjutkan pekerjaan dari titik terakhir.

## Objective
Sync konten website Royal Haramain (travel haji/umroh) dengan head office `royalharamain.id` — kontak, paket, branding, warna, CTA.

## Status Terakhir (saat sesi berakhir)
- **Commit & push sukses:** `9186cff` Sync konten dengan royalharamain.id (master → origin/master)
- **File renamed:** `index.php` (coming soon) → `index_soon.php`; `index_main.php` → `index.php` (halaman utama)
- **Paket diupdate** sesuai royalharamain.id: Umrah Hemat 2026 (Rp 29,9 Jt/9Hr), Umrah Premium 2026 (Rp 39,9 Jt/12Hr), Program Umrah 10 Hari (Rp 33,9 Jt), Haji Khusus 2027 (USD 13.400/23Hr)
- **Fasilitas lengkap** sesuai website pusat (11 fasilitas Umrah, 9 fasilitas Program 10 Hari, 10 fasilitas Haji)
- **Database diupdate** via `update_packages.sql` — paket, fasilitas, features, hero WhatsApp link
- **Warna dominan putih** seperti royalharamain.id (header putih, badges putih, contact putih, footer gelap)
- **Kontak:** WhatsApp 0812 1515 1552 (`https://wa.me/6281215151552`), Telepon 0812 1515 1552, Kantor Bantul
- **Hero:** "Travel Umrah Ramah Lansia" eyebrow
- **Section titles:** "Pilihan Paket Umrah", "Kenapa Royal Haramain — Didampingi dari Awal Hingga Pulang ke Rumah", "4 Kantor Siap Melayani Anda"
- **Legal badges:** PPIU No. 21092200513570003, PIHK No. 694/2020 by. Hajar Aswad
- **Social media:** IG @royalumrah.jogja, TikTok @royalumrah.jogja, FB Royal Haramain Internasional
- **Admin:** `superadmin` / `b1sm1ll4hAdmin@`

## Important Details
- Teknologi: PHP 8.0.30 + MariaDB 10.4.32 (XAMPP lokal). Target deploy: shared hosting cPanel (PHP+MySQL).
- Repo GitHub: `baktisaputro/royalharamain` (branch `master`), clone di `C:\Users\IESPA 000\royalharamain`.
- Database: `pesm4254_royalharamain` (user: `pesm4254_royalharamain`)
- `app/config.php` sudah diisi kredensial hosting. ⚠️ JANGAN commit ke GitHub.
- WhatsApp utama: `https://wa.me/6281215151552` (Bantul office)

## Work State

### Completed
- Sinkronisasi konten dengan royalharamain.id (paket, kontak, branding, warna, CTA)
- Header dipermudah: logo + "Royal Haramain" saja (tidak ada subtitle)
- Semua CTA → `https://wa.me/6281215151552`
- Warna dominan putih (header, badges, contact, body)
- Footer: alamat Kantor Bantul, sosial media
- Features: Berpengalaman, Berizin, Bimbingan, Fleksibel (4 saja, sesuai website)
- Database diupdate via `update_packages.sql` (sukses)
- File renamed: `index.php` → `index_soon.php`, `index_main.php` → `index.php`
- Commit & push ke GitHub master (commit `9186cff`)

### Active
- (empty)

### Progres

- Step 1 CSRF (`ad15c09`) ✔
- Step 2 display_errors + password (`e34d337`) ✔
- Step 3 SEO meta tags + favicon (`126bc6d`) ✔
- Step 4 robots.txt + sitemap.xml (`471bc88`) ✔

## Next Move
1. Go-live cPanel (tunggu DNS propagate)
2. Visual check `index.php` di browser setelah deploy
3. Pull di hosting cPanel via Git untuk update live site

### Blocked
- Go-live cPanel: DNS domain belum aktif

## Relevant Files
- `C:\Users\IESPA 000\royalharamain\index.php`: halaman utama (dari index_main.php)
- `C:\Users\IESPA 000\royalharamain\index_soon.php`: halaman coming soon
- `C:\Users\IESPA 000\royalharamain\css\style_pub.css`: styling putih dominant
- `C:\Users\IESPA 000\royalharamain\database.sql`: skema + seed
- `C:\Users\IESPA 000\royalharamain\update_packages.sql`: script update paket via phpMyAdmin
- `C:\Users\IESPA 000\royalharamain\app\config.php`: kredensial DB + BASE_URL (jangan commit)

## Kredensial & Akses
- Admin: `superadmin` / `b1sm1ll4hAdmin@`
- DB: `pesm4254_royalharamain` / lihat `app/config.php`
