# Sesi Kerja Royal Haramain

File ini adalah catatan sesi. Bacalah untuk melanjutkan pekerjaan dari titik terakhir.

## Objective
- Merampungkan audit & perbaikan bertahap website `royalharamain.com` (Step 1–9) sebelum go-live.
- Deploy hasil perbaikan ke hosting cPanel, lalu verifikasi live.
- (Sampingan, sudah tuntas) Perbaikan routing model AI di 9router → pakai `ds/deepseek-v4-flash`.

## Status Terakhir (saat sesi berakhir)
- **Semua pekerjaan kode Step 1–10 SUDAH di-commit & push ke GitHub `master`.**
- Commit terakhir: `4456f88` (Step 10: hapus link admin footer + section FAQ & Testimoni + optimasi gambar hero).
- **BELUM di-deploy ke hosting.** Website live masih versi lama.
- Sisa pekerjaan hanya **aksi manual oleh user di cPanel** (lihat "Next Move"): `git pull`, update `app/config.php`, opsional rotasi anon key Supabase.

## Important Details
- Teknologi: PHP 8.0.30 + MariaDB (XAMPP lokal). Target: shared hosting cPanel (LiteSpeed).
- Repo GitHub: `baktisaputro/royalharamain` (branch `master`), clone lokal di `C:\Users\IESPA 000\royalharamain`.
- Hosting: cPanel Rumahweb `pesm4254_royalharamain`; folder `public_html/royalharamain.com`.
- Domain: `royalharamain.com` (BASE_URL `https://www.royalharamain.com`). HTTPS + canonical `www` aktif.
- Database: `pesm4254_royalharamain` (user: `pesm4254_royalharamain`). `app/config.php` sudah diisi kredensial hosting → **gitignored, JANGAN commit**; di hosting di-protect `git update-index --skip-worktree`.
- Website = single-page dinamis dari MySQL (`index.php`). Section: `#beranda #keunggulan #paket #tentang #galeri #berita #kontak #lokasi`. `.htaccess` redirect `/index.php` → `/`.
- Tema: emerald `#046a38`, emerald-dark `#023d1f`, gold `#d4af37`, cream `#f8fbf9`.
- Deploy: via cPanel Terminal `git pull origin master`; panduan di `deploy.sh` + `DEPLOY_GIT.md`.
- 9router: proxy AI port 20128; SQLite `%APPDATA%\9router\db\data.sqlite`; provider `deepseek` (`tenan_rejekey`, isActive=1) aktif; `cbai/glm-5.2` error karena provider `codebuddy-intl` sudah dihapus. `opencode.json` sudah memakai `9router/ds/deepseek-v4-flash`.

## Work State

### Completed
- **Step 1** CSRF protection (`ad15c09`) ✔
- **Step 2** display_errors off + ganti password seed (`e34d337`) ✔
- **Step 3** SEO meta tags + favicon (`126bc6d`) ✔
- **Step 4** robots.txt + sitemap.xml (`471bc88`) ✔
- **Step 5** Security headers + `.htaccess` hardening (`fa6817f`) ✔
    - Redirect HTTP→HTTPS + canonical `www` (hanya domain produksi; localhost aman)
    - Header: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection, Referrer-Policy, Permissions-Policy, HSTS
    - CSP kompatibel (inline style/script + Font Awesome + Google Fonts + Supabase admin)
    - Blokir eksekusi PHP di `uploads/`, blokir `/app/`, proteksi file sensitif (`*.sql`, `.env`, `.credentials.local`)
- **Step 6** Performa (`40301dc`) ✔
    - `logo.png` 366KB → 58KB (trim + resize 512px)
    - `loading="lazy"` + `decoding="async"` di gambar bawah-lipatan
    - Preload hero slide pertama (LCP) + `fetchpriority="high"`
    - Kompresi gzip (mod_deflate) + cache font/svg
    - Tambah `#berita` di sitemap
- **Step 7** Aksesibilitas & SEO (`2f18812`) ✔
    - `rel="noopener noreferrer"` di 16 link `target="_blank"`
    - `aria-label` tombol menu mobile
    - `theme-color` + structured data JSON-LD `TravelAgency`
- **Step 8** Audit keamanan (`dbc59c0`) ✔
    - Fix bug: form Hapus artikel & paket tidak punya token CSRF → ditambahkan
    - `delete_upload()` diperkuat anti path-traversal (realpath di dalam `uploads/`)
    - Session cookie: `httponly`, `samesite=Lax`, `secure` auto (HTTPS)
- **Step 9** Bersih-bersih & anti brute-force (`93e7674`) ✔
    - Hapus 14 file legacy: `admin.html`, `index.html`, `index2.html`, `index3.html`, `erorlogin.png`, `rhi.png`, `css/style.css`, `css/admin.css`, `js/main.js`, `js/data.js`, `js/supabaseClient.js`, `supabase/schema.sql`, `vercel.json`, `.env.example` (memuat anon key Supabase asli)
    - Login kini ada lockout: maks 5 gagal → terkunci 5 menit (berbasis sesi)
    - Semua 20 file PHP lolos `php -l`
- **Step 10** Footer & kredibilitas (`4456f88`) ✔
    - Hapus link `admin/login.php` dari footer publik (satu-satunya temuan risiko keamanan nyata)
    - Section FAQ expandable (native `<details>`, tanpa JS): cicilan/tabungan, dokumen, refund, isi paket, pendampingan
    - Section Testimoni khusus, siap diisi foto (`uploads/testimoni/`) + teks via array `$testimonials` di `index.php`; fallback monogram inisial jika foto belum ada
    - Optimasi 5 gambar hero `slide_*.webp` → <180KB (backup di `%TEMP%\opencode\webp-opt\backup`)

### Active
- Menunggu user melakukan deploy manual di cPanel (git pull + update config.php) — lihat Next Move.
- Testimoni di halaman publik masih **placeholder** — user wajib mengisi foto (`uploads/testimoni/`) + teks asli di array `$testimonials` (`index.php`) sebelum go-live; jawaban FAQ refund/pembatalan juga perlu konfirmasi kebijakan user.

### Blocked
- **Deploy cPanel belum dilakukan** → live masih versi lama: `robots.txt`/`sitemap.xml` 404, homepage 29210 byte judul "Travel Haji, Umroh dan Halal Tours", tanpa security header. Commit 1–10 sudah di GitHub tapi belum di-pull.
- `app/config.php` di hosting wajib di-update manual (tidak ikut git pull).
- Saran (opsional): rotasi anon key Supabase lama bila project-nya masih dipakai (pernah ter-push publik).

## Next Move (aksi manual user di cPanel)

### TUGAS 1 — `git pull` di hosting
cPanel → **Advanced → Terminal**:
```bash
cd public_html/royalharamain.com
git pull origin master
```
Bila muncul `config.php differs` / `would be overwritten by merge` (normal, karena config di-skip):
```bash
git update-index --skip-worktree app/config.php
git pull origin master
```

### TUGAS 2 — Update `app/config.php` di hosting (manual)
File Manager → `public_html/royalharamain.com/app/` → Edit `config.php`.
Ganti baris berikut:
```php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');
```
menjadi:
```php
$__https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (($_SERVER['SERVER_PORT'] ?? '') == 443);
session_set_cookie_params([
    'lifetime' => 0,
    'path'     => '/',
    'httponly' => true,
    'secure'   => $__https,
    'samesite' => 'Lax',
]);
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');
```
Jangan sentuh `DB_HOST/DB_NAME/DB_USER/DB_PASS` dan `BASE_URL`. Save.

### TUGAS 3 — (Opsional) Rotasi anon key Supabase
Hanya jika project Supabase masih dipakai: dashboard Supabase → Settings → API → Reset/Rotate anon key → update di semua tempat pemakaian.

### Verifikasi live (setelah Tugas 1 & 2)
- `https://www.royalharamain.com/robots.txt` → bukan 404
- `https://www.royalharamain.com/sitemap.xml` → XML tampil
- `https://www.royalharamain.com/` → halaman utama, logo lebih cepat
- `https://www.royalharamain.com/admin/login.php` → form login normal

### Update ke depan (setelah semua kelar)
Dari lokal: `git add -A` → `git commit -m "..."` → `git push origin master`.
Di cPanel: `cd public_html/royalharamain.com` → `git pull origin master`.

## Relevant Files
- `C:\Users\IESPA 000\royalharamain\index.php`: halaman publik utama (Step 3/6/7 diedit)
- `C:\Users\IESPA 000\royalharamain\.htaccess`: hardening Step 5 + gzip/cache Step 6
- `C:\Users\IESPA 000\royalharamain\robots.txt`, `sitemap.xml`: Step 4 (+`#berita`)
- `C:\Users\IESPA 000\royalharamain\admin\login.php`: lockout brute-force Step 9
- `C:\Users\IESPA 000\royalharamain\admin\panels\artikel.php`, `admin\panels\paket.php`: fix CSRF Step 8
- `C:\Users\IESPA 000\royalharamain\app\upload.php`: hardening path-traversal
- `C:\Users\IESPA 000\royalharamain\app\config.example.php`: template (session hardening + display_errors off)
- `C:\Users\IESPA 000\royalharamain\app\config.php`: kredensial DB + BASE_URL (jangan commit)
- `C:\Users\IESPA 000\royalharamain\database.sql`: skema + seed
- `C:\Users\IESPA 000\royalharamain\DEPLOY_GIT.md`, `deploy.sh`: panduan/script deploy cPanel
- `C:\Users\IESPA 000\opencode.json`: model `9router/ds/deepseek-v4-flash`
- `C:\Users\IESPA 000\AppData\Roaming\9router\db\data.sqlite`: status provider/kombo 9router

## Kredensial & Akses
- Admin username: `superadmin`
- Admin password: seed di `database.sql` = `@#$Admin321` (⚠️ password DB existing bisa jadi masih `b1sm1ll4hAdmin@`; ganti segera setelah login pertama)
- DB: `pesm4254_royalharamain` / lihat `app/config.php`
- 9router apiKey: `sk-7b55d1a8bba4cc11-p532kd-f28f1a4a`
