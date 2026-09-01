<?php
require_once __DIR__ . '/../app/config.php';

// Jika sudah login, langsung masuk dashboard
if (is_logged_in()) {
    header('Location: ' . BASE_URL . '/admin/');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi.';
    } else {
        $stmt = db()->prepare(
            'SELECT u.*, r.slug AS role_slug, r.name AS role_name
             FROM admin_users u
             JOIN roles r ON r.id = u.role_id
             WHERE u.username = ?'
        );
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            if ((int)$user['is_active'] !== 1) {
                $error = 'Akun Anda dinonaktifkan. Hubungi Super Admin.';
            } else {
                // Regenerasi session id keamanan
                session_regenerate_id(true);
                $_SESSION['admin_id'] = (int)$user['id'];
                header('Location: ' . BASE_URL . '/admin/');
                exit;
            }
        } else {
            $error = 'Username atau password salah.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin | Royal Haramain</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body {
      font-family:'Inter',sans-serif;
      min-height:100vh;
      display:flex; align-items:center; justify-content:center;
      background:linear-gradient(135deg,#0f5132,#198754);
    }
    .login-card {
      background:#fff; border-radius:16px; box-shadow:0 20px 60px rgba(0,0,0,.3);
      width:100%; max-width:400px; padding:40px 36px;
    }
    .login-card .logo { display:block; width:80px; height:80px; object-fit:cover; border-radius:12px; margin:0 auto 16px; }
    .login-card h1 { font-size:22px; text-align:center; color:#0f5132; margin-bottom:4px; }
    .login-card .sub { text-align:center; color:#6c757d; font-size:13px; margin-bottom:24px; }
    .field { margin-bottom:16px; }
    .field label { display:block; font-size:13px; font-weight:600; color:#495057; margin-bottom:6px; }
    .field input {
      width:100%; padding:11px 14px; border:1px solid #ced4da; border-radius:8px;
      font-size:14px; transition:border-color .15s;
    }
    .field input:focus { outline:none; border-color:#198754; box-shadow:0 0 0 3px rgba(25,135,84,.15); }
    .btn {
      width:100%; padding:12px; background:#198754; color:#fff; border:none; border-radius:8px;
      font-size:15px; font-weight:700; cursor:pointer; transition:background .15s;
    }
    .btn:hover { background:#157347; }
    .error {
      background:#f8d7da; color:#842029; border:1px solid #f5c2c7; border-radius:8px;
      padding:10px 12px; font-size:13px; margin-bottom:16px;
    }
    .msg {
      background:#d1e7dd; color:#0f5132; border:1px solid #badbcc; border-radius:8px;
      padding:10px 12px; font-size:13px; margin-bottom:16px; text-align:center;
    }
    .back { display:block; text-align:center; margin-top:16px; font-size:13px; color:#6c757d; text-decoration:none; }
    .back:hover { color:#198754; }
  </style>
</head>
<body>
  <div class="login-card">
    <img src="../assets/images/logo.png" alt="Logo" class="logo">
    <h1>Admin Panel</h1>
    <p class="sub">PT Royal Haramain Internasional</p>

    <?php if (!empty($error)): ?>
      <div class="error"><i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($error) ?></div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'logout'): ?>
      <div class="msg"><i class="fa-solid fa-check"></i> Anda telah keluar.</div>
    <?php endif; ?>

    <form method="post" action="login.php" autocomplete="off">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="superadmin" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="••••••••" required>
      </div>
      <button type="submit" class="btn"><i class="fa-solid fa-lock"></i> Masuk</button>
    </form>
    <a href="../index.php" class="back"><i class="fa-solid fa-arrow-left"></i> Kembali ke website</a>
  </div>
</body>
</html>
