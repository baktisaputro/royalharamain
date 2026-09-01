<?php
/**
 * Royal Haramain - Konfigurasi & Koneksi Database (MySQL)
 * Sesuaikan kredensial dengan database di hosting cPanel Anda.
 * Untuk uji lokal (XAMPP), gunakan default di bawah.
 */

// ===== KONFIGURASI DATABASE =====
// Ganti sesuai database hosting Anda (cPanel > Databases > MySQL Databases)
define('DB_HOST', 'localhost');        // shared hosting: biasanya 'localhost'
define('DB_NAME', 'royalharamain');    // nama database Anda
define('DB_USER', 'root');             // cPanel: user_dbuser
define('DB_PASS', '');                 // cPanel: your_db_password

// URL dasar aplikasi (untuk redirect/link). Contoh:
//   Lokal  : http://localhost/royalharamain
//   Hosting: https://mydomain.com
define('BASE_URL', 'http://localhost/royalharamain');

// ===== SESI =====
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

// ===== KONEKSI =====
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die('Koneksi database gagal: ' . htmlspecialchars($e->getMessage()));
        }
    }
    return $pdo;
}

// ===== HELPERS SESSION / ROLE =====
/** Cek apakah user sudah login */
function is_logged_in(): bool
{
    return isset($_SESSION['admin_id']);
}

/** Ambil data user yang login (cache per request) */
function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        if (!is_logged_in()) {
            $user = null;
        } else {
            $stmt = db()->prepare(
                'SELECT u.*, r.slug AS role_slug, r.name AS role_name
                 FROM admin_users u
                 JOIN roles r ON r.id = u.role_id
                 WHERE u.id = ? AND u.is_active = 1'
            );
            $stmt->execute([$_SESSION['admin_id']]);
            $user = $stmt->fetch() ?: null;
            if (!$user) {
                session_unset();
                session_destroy();
            }
        }
    }
    return $user;
}

/** Periksa izin: user harus login + role tertentu */
function require_role(array $roles): void
{
    $u = current_user();
    if (!$u) {
        header('Location: ' . BASE_URL . '/admin/login.php');
        exit;
    }
    if (!in_array($u['role_slug'], $roles, true)) {
        http_response_code(403);
        die('Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
    }
}
