# Panduan Deploy Royal Haramain ke cPanel via Git

## Persiapan

Anda butuh:
- Akses cPanel (dengan Terminal/SSH)
- Repository GitHub: `https://github.com/baktisaputro/royalharamain`

---

## SETUP PERTAMA KALI

### Step 1: Buka cPanel Terminal

1. Login ke cPanel hosting Anda
2. Cari section **Advanced** → klik **Terminal**
3. Akan terbuka tab baru dengan command prompt

### Step 2: Backup Config.php

**Via File Manager:**
1. Buka cPanel → **File Manager**
2. Navigasi ke `public_html/royalharamain.com/app/`
3. Download `config.php` sebagai backup di komputer Anda
4. Catat isi `config.php` (password DB, dll)

**Atau via Terminal:**
```bash
cd public_html/royalharamain.com
cp app/config.php ~/config.php.backup
```

### Step 3: Setup Git

Copy-paste command ini satu per satu ke Terminal:

```bash
cd public_html/royalharamain.com
```

```bash
git init
```

```bash
git remote add origin https://github.com/baktisaputro/royalharamain.git
```

```bash
git fetch origin master
```

```bash
git checkout -b backup-local
```

```bash
git add -A
```

```bash
git commit -m "backup local sebelum deploy"
```

### Step 4: Pull dari GitHub

```bash
git checkout master
```

**Catatan:** Kalau error karena ada file yang bentrok, pakai:
```bash
git checkout -f master
```

### Step 5: Restore Config.php

```bash
cp ~/config.php.backup app/config.php
```

**Atau kalau backup di folder yang sama:**
```bash
cp app/config.php.bak app/config.php
```

### Step 6: Protect Config.php

Supaya `config.php` tidak ditimpa oleh `git pull` di masa depan:

```bash
git update-index --skip-worktree app/config.php
```

### Step 7: Test Website

Buka browser dan cek:
- `https://royalharamain.com` → halaman Coming Soon
- `https://royalharamain.com/admin/login.php` → halaman login admin

Login dengan:
- Username: `superadmin`
- Password: (yang sudah Anda atur)

---

## UPDATE KE DEPAN

Setiap kali ada perubahan di GitHub:

### Via Terminal:

```bash
cd public_html/royalharamain.com
./deploy.sh
```

Atau langsung:
```bash
cd public_html/royalharamain.com
git pull origin master
```

**Selesai!** Website otomatis ter-update.

---

## CARA DEPLOY DARI LOKAL KE GITHUB

Dari komputer lokal Anda (bukan cPanel):

### 1. Commit perubahan:
```bash
cd C:\Users\IESPA 000\royalharamain
git add -A
git commit -m "deskripsi perubahan"
```

### 2. Push ke GitHub:
```bash
git push origin master
```

### 3. Deploy ke cPanel:
```bash
# Via cPanel Terminal
cd public_html/royalharamain.com
./deploy.sh
```

---

## TROUBLESHOOTING

### Error: "config.php" differs from index

Artinya `config.php` berubah di server tapi bukan di repo. Aman di-skip:
```bash
git update-index --skip-worktree app/config.php
git pull origin master
```

### Error: "would be overwritten by merge"

Ada file yang bentrok. Force checkout:
```bash
git checkout -f master
```
Lalu restore config.php:
```bash
cp ~/config.php.backup app/config.php
```

### Website error setelah pull

Cek error log di cPanel → **Error Log**. Biasanya:
- `config.php` hilang → restore dari backup
- `.htaccess` bentrok → cek isi `.htaccess`

### Rollback ke versi sebelumnya

Cari commit yang berfungsi:
```bash
git log --oneline -10
```

Lalu checkout ke commit tersebut:
```bash
git checkout <commit-hash> -- .
```

---

## CATATAN PENTING

### File yang TIDAK boleh di-gitignore (sudah di-commit):
- `app/config.example.php`
- `app/csrf.php`
- `app/upload.php`
- `index.php`
- Semua file PHP di `admin/`
- `css/style_pub.css`, `css/admin_panel.css`
- `js/admin_panel.js`
- `database.sql`

### File yang DI-ignore (tidak di-commit):
- `app/config.php` (password DB)
- `app/config.php.bak`
- `.credentials.local`
- `uploads/artikel/*`, `uploads/paket/*`, dll (gambar user)

---

## CEKLIST SETUP

- [ ] Config.php di-backup
- [ ] Git sudah ter-setup di cPanel
- [ ] Config.php sudah di-restore setelah pull
- [ ] Config.php sudah di-protect (skip-worktree)
- [ ] Website bisa diakses
- [ ] Admin panel bisa login
- [ ] deploy.sh sudah dibuat dan di-chmod
