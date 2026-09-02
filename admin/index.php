<?php
require_once __DIR__ . '/../app/config.php';

// Semua role yang sudah login berhak masuk dashboard (tapi menu dibatasi per role)
$u = current_user();
if (!$u) {
    header('Location: ' . BASE_URL . '/admin/login.php');
    exit;
}

$role = $u['role_slug'];
$allowed_tabs = ['hero', 'paket', 'artikel', 'galeri', 'fitur', 'leads', 'kontak', 'promo'];
if ($role === 'super_admin') {
    $allowed_tabs[] = 'users';
}
if ($role === 'viewer') {
    // viewer hanya lihat: hero, paket, artikel, galeri, fitur, leads, kontak (read-only)
    $allowed_tabs = ['hero', 'paket', 'artikel', 'galeri', 'fitur', 'leads', 'kontak'];
}
// setiap yang login bisa akses profil (ganti password sendiri)
$allowed_tabs[] = 'profil';

$tab = $_GET['tab'] ?? 'hero';
if (!in_array($tab, $allowed_tabs, true)) {
    $tab = $allowed_tabs[0];
}

// Pemetaan file panel
$panels = [
    'hero'     => __DIR__ . '/panels/hero.php',
    'paket'    => __DIR__ . '/panels/paket.php',
    'artikel'  => __DIR__ . '/panels/artikel.php',
    'galeri'   => __DIR__ . '/panels/galeri.php',
    'fitur'    => __DIR__ . '/panels/fitur.php',
    'leads'    => __DIR__ . '/panels/leads.php',
    'kontak'   => __DIR__ . '/panels/kontak.php',
    'promo'    => __DIR__ . '/panels/promo.php',
    'users'    => __DIR__ . '/panels/users.php',
    'profil'   => __DIR__ . '/panels/profil.php',
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel | Royal Haramain</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="../css/admin_panel.css">
</head>
<body>
  <div class="layout">
    <!-- ===== SIDEBAR ===== -->
    <aside class="sidebar">
      <div class="sidebar-brand">
        <img src="../assets/images/logo.png" alt="Logo" class="brand-logo">
        <div class="brand-text">
          <strong>Royal Haramain</strong>
          <span>Admin Panel</span>
        </div>
      </div>
      <nav class="side-nav">
        <?php
        $menu = [
            'hero'    => ['fa-house', 'Hero / Beranda'],
            'paket'   => ['fa-box-open', 'Paket Umroh'],
            'artikel' => ['fa-newspaper', 'Artikel'],
            'galeri'  => ['fa-images', 'Galeri Foto'],
            'fitur'   => ['fa-star', 'Keunggulan'],
            'leads'   => ['fa-users', 'Data Pendaftar'],
    'kontak'  => ['fa-envelope-open-text', 'Pesan Masuk'],
    'promo'   => ['fa-bullhorn', 'Popup Promo'],
    'users'   => ['fa-user-shield', 'Manajemen Admin'],
    'profil'  => ['fa-user-gear', 'Profil & Password'],
];
        foreach ($menu as $key => $item) {
            if (!in_array($key, $allowed_tabs, true)) continue;
            $active = ($tab === $key) ? 'active' : '';
            $icon = $item[0]; $label = $item[1];
            echo "<a class=\"nav-item $active\" href=\"?tab=$key\"><i class=\"fa-solid $icon\"></i> $label</a>";
        }
        ?>
      </nav>
      <div class="sidebar-footer">
        <div class="user-chip">
          <div class="avatar"><?= strtoupper(substr($u['name'] ?: $u['username'], 0, 1)) ?></div>
          <div>
            <strong><?= htmlspecialchars($u['name'] ?: $u['username']) ?></strong>
            <span class="role-badge role-<?= $role ?>"><?= htmlspecialchars($u['role_name']) ?></span>
          </div>
        </div>
        <a href="../admin/logout.php" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Keluar</a>
      </div>
    </aside>

    <!-- ===== MAIN ===== -->
    <main class="main">
      <header class="topbar">
        <h1><?= htmlspecialchars(ucfirst($tab)) ?></h1>
        <a href="../index.php" target="_blank" class="btn-view"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website</a>
      </header>

      <div class="content">
        <?php if (isset($panels[$tab]) && file_exists($panels[$tab])): ?>
          <?php include $panels[$tab]; ?>
        <?php else: ?>
          <p>Panel tidak ditemukan.</p>
        <?php endif; ?>
      </div>
    </main>
  </div>

  <div id="toast" class="toast"></div>
  <div id="modal" class="modal" hidden></div>
  <script src="../js/admin_panel.js"></script>
  <script>window.RH_ADMIN = { tab: <?= json_encode($tab) ?>, role: <?= json_encode($role) ?> };</script>
</body>
</html>
