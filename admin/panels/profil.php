<?php
$u = current_user();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'change_pass') {
            $old = $_POST['old_password'] ?? '';
            $new = $_POST['new_password'] ?? '';
            $conf = $_POST['confirm_password'] ?? '';
            // verifikasi password lama dari DB
            $stmt = db()->prepare('SELECT password_hash FROM admin_users WHERE id=?');
            $stmt->execute([$u['id']]);
            $row = $stmt->fetch();
            if (!$row || !password_verify($old, $row['password_hash'])) throw new Exception('Password lama salah.');
            if (strlen($new) < 8) throw new Exception('Password baru minimal 8 karakter.');
            if ($new !== $conf) throw new Exception('Konfirmasi password tidak cocok.');
            db()->prepare('UPDATE admin_users SET password_hash=? WHERE id=?')
                ->execute([password_hash($new, PASSWORD_DEFAULT), $u['id']]);
            $msg = 'Password berhasil diubah.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}
?>
<?php if ($msg): ?><div class="card" style="border-color:<?= $msg==='Password berhasil diubah.'?'#198754':'#dc3545' ?>"><p><?= htmlspecialchars($msg) ?></p></div><?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-user-gear"></i> Profil & Keamanan</h2>
  <div class="grid" style="margin-bottom:20px">
    <div class="field"><label>Nama</label><input type="text" value="<?= htmlspecialchars($u['name']) ?>" disabled></div>
    <div class="field"><label>Username</label><input type="text" value="<?= htmlspecialchars($u['username']) ?>" disabled></div>
    <div class="field"><label>Level Akses</label><input type="text" value="<?= htmlspecialchars($u['role_name']) ?>" disabled></div>
  </div>
</div>

<div class="card">
  <h2><i class="fa-solid fa-key"></i> Ganti Password</h2>
  <p class="hint" style="margin-top:-8px;margin-bottom:16px">Password minimal 8 karakter.</p>
  <form method="post" action="?tab=profil" style="max-width:400px">
    <input type="hidden" name="action" value="change_pass">
    <div class="field"><label>Password Lama</label><input type="password" name="old_password" required></div>
    <div class="field"><label>Password Baru</label><input type="password" name="new_password" minlength="8" required></div>
    <div class="field"><label>Konfirmasi Password Baru</label><input type="password" name="confirm_password" minlength="8" required></div>
    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Password</button>
  </form>
</div>
