# Setup Royal Haramain di macOS (mobile)

Panduan lanjut kerja dari perangkat macOS. Proyek sudah ada di GitHub:
`baktisaputro/royalharamain` (branch `master`).

## 1. Prasyarat

- macOS dengan Terminal
- [XAMPP for macOS](https://www.apachefriends.org/) (PHP 8 + MariaDB/MySQL)
- Git (biasanya sudah ada, cek dengan `git --version`)

## 2. Clone repo

```bash
git clone https://github.com/baktisaputro/royalharamain.git
cd royalharamain
```

## 3. Tautkan folder ke htdocs

XAMPP macOS biasanya menggunakan `/Applications/XAMPP/htdocs`.

```bash
ln -s "$HOME/royalharamain" "/Applications/XAMPP/htdocs/royalharamain"
```

Jika XAMPP diinstal di lokasi lain, ganti path di atas sesuai letak `htdocs`.

## 4. Nyalakan server & buat database

1. Buka XAMPP Control, start **Apache** dan **MySQL**.
2. Buka `http://localhost/phpmyadmin` di browser, buat database bernama `royalharamain`.
3. Import `database.sql` (tab Import di phpMyAdmin).

Atau lewat baris perintah:

```bash
# path MySQL XAMPP macOS
/Applications/XAMPP/xamppfiles/bin/mysql -u root < database.sql
```

## 5. Cek config

`app/config.php` berisi kredensial DB. Kredensial lokal default XAMPP:
- DB: `royalharamain`
- user: `root`
- password: kosong

Sesuaikan `app/config.php` bila berbeda.

## 6. Kredensial admin

> **PENTING:** `.credentials.local` dan password admin **TIDAK ikut di GitHub**
> (file ini di-gitignore). Harus kamu salin manual dari perangkat lama
> (flashdisk/cloud pribadi) atau reset password admin.

Kalau tidak punya salinannya, reset password lewat phpMyAdmin:
- Buka tabel `admin_users`, edit baris admin, ganti kolom `password`
  dengan hash bcrypt (contoh [bcrypt-generator.com](https://bcrypt-generator.com/)).

## 7. Jalankan via opencode

```bash
opencode
```

Lalu ketik: **"lanjut dari SESSION.md"**.

Cek hasil di browser: `http://localhost/royalharamain/`
Panel admin: `http://localhost/royalharamain/admin/login.php`

## Catatan

- BASE_URL default: `http://localhost/royalharamain` (sesuaikan bila port XAMPP beda).
- Bila port MySQL beda (mis. 3307), ubah `app/config.php`.
- Setelah kerja, commit & push — semua perubahan otomatis tersimpan di GitHub.
