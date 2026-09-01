<?php
/** Panel: Manajemen Admin - HANYA Super Admin yang berhak akses */
require_role(['super_admin']);

$msg = '';
// Aksi: tambah / ubah / hapus / toggle aktif / reset password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        if ($action === 'create') {
            $name = trim($_POST['name'] ?? '');
            $username = strtolower(trim($_POST['username'] ?? ''));
            $email = trim($_POST['email'] ?? '');
            $role_id = (int)($_POST['role_id'] ?? 2);
            $pass = $_POST['password'] ?? '';
            if ($name === '' || $username === '' || $pass === '') throw new Exception('Nama, username, dan password wajib diisi.');
            if (strlen($pass) < 6) throw new Exception('Password minimal 6 karakter.');
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = db()->prepare('INSERT INTO admin_users (role_id, name, username, email, password_hash) VALUES (?,?,?,?,?)');
            $stmt->execute([$role_id, $name, $username, $email ?: null, $hash]);
            $msg = 'Admin baru berhasil ditambahkan.';
        }

        elseif ($action === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $role_id = (int)($_POST['role_id'] ?? 2);
            $stmt = db()->prepare('UPDATE admin_users SET name=?, email=?, role_id=? WHERE id=?');
            $stmt->execute([$name, $email ?: null, $role_id, $id]);
            $msg = 'Data admin diperbarui.';
        }

        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id === (int)$u['id']) throw new Exception('Tidak bisa menghapus akun sendiri.');
            $stmt = db()->prepare('DELETE FROM admin_users WHERE id=? AND role_id != 1');
            $stmt->execute([$id]);
            $msg = 'Admin dihapus.';
        }

        elseif ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id === (int)$u['id']) throw new Exception('Tidak bisa menonaktifkan akun sendiri.');
            $stmt = db()->prepare('UPDATE admin_users SET is_active = 1 - is_active WHERE id=? AND role_id != 1');
            $stmt->execute([$id]);
            $msg = 'Status akun diubah.';
        }

        elseif ($action === 'reset_pass') {
            $id = (int)($_POST['id'] ?? 0);
            $pass = $_POST['password'] ?? '';
            if (strlen($pass) < 6) throw new Exception('Password minimal 6 karakter.');
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $stmt = db()->prepare('UPDATE admin_users SET password_hash=? WHERE id=?');
            $stmt->execute([$hash, $id]);
            $msg = 'Password berhasil direset.';
        }
    } catch (Exception $e) {
        $msg = 'Gagal: ' . $e->getMessage();
    }
}

$users = db()->query(
    'SELECT u.*, r.slug AS role_slug, r.name AS role_name
     FROM admin_users u JOIN roles r ON r.id=u.role_id
     ORDER BY u.role_id ASC, u.name ASC'
)->fetchAll();
$roles = db()->query('SELECT * FROM roles ORDER BY id')->fetchAll();

$editing = null;
if (isset($_GET['edit'])) {
    foreach ($users as $x) if ((int)$x['id'] === (int)$_GET['edit']) { $editing = $x; break; }
}
?>
<?php if ($msg): ?>
  <div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>">
    <p><?= htmlspecialchars($msg) ?></p>
  </div>
<?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-user-plus"></i> <?= $editing ? 'Edit Admin' : 'Tambah Admin Baru' ?></h2>
  <form method="post" action="?tab=users">
    <?php if ($editing): ?>
      <input type="hidden" name="action" value="update">
      <input type="hidden" name="id" value="<?= (int)$editing['id'] ?>">
    <?php else: ?>
      <input type="hidden" name="action" value="create">
    <?php endif; ?>
    <div class="grid">
      <div class="field">
        <label>Nama Lengkap</label>
        <input type="text" name="name" value="<?= htmlspecialchars($editing['name'] ?? '') ?>" required>
      </div>
      <div class="field">
        <label>Username</label>
        <input type="text" name="username" value="<?= htmlspecialchars($editing['username'] ?? '') ?>" <?= $editing?'readonly':'' ?> required>
      </div>
      <div class="field">
        <label>Email (opsional)</label>
        <input type="email" name="email" value="<?= htmlspecialchars($editing['email'] ?? '') ?>">
      </div>
      <div class="field">
        <label>Level Akses</label>
        <select name="role_id">
          <?php foreach ($roles as $r): ?>
            <option value="<?= (int)$r['id'] ?>" <?= (int)($editing['role_id'] ?? 2) === (int)$r['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($r['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <?php if (!$editing): ?>
        <div class="field">
          <label>Password Awal</label>
          <input type="password" name="password" minlength="6" placeholder="min. 6 karakter" required>
        </div>
      <?php endif; ?>
    </div>
    <div style="display:flex;gap:10px;margin-top:6px">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> <?= $editing?'Perbarui':'Simpan Admin' ?></button>
      <?php if ($editing): ?><a href="?tab=users" class="btn btn-secondary">Batal</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <h2><i class="fa-solid fa-user-shield"></i> Daftar Admin (<span id="userCount"><?= count($users) ?></span>)</h2>
  <p class="hint" style="margin-top:-8px;margin-bottom:14px">
    Super Admin dapat menambah, mengubah level, menonaktifkan, menghapus, dan mereset password admin lain.
  </p>
  <div class="table-wrap">
    <table class="admin-table">
      <thead>
        <tr><th>Nama</th><th>Username</th><th>Level</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr>
      </thead>
      <tbody>
        <?php foreach ($users as $x): $isSelf = ((int)$x['id'] === (int)$u['id']); ?>
          <tr>
            <td><strong><?= htmlspecialchars($x['name']) ?></strong><?= $isSelf ? ' <span class="badge badge-active">Anda</span>' : '' ?></td>
            <td><?= htmlspecialchars($x['username']) ?></td>
            <td><span class="badge role-<?= $x['role_slug'] ?>"><?= htmlspecialchars($x['role_name']) ?></span></td>
            <td><span class="badge <?= $x['is_active'] ? 'badge-active' : 'badge-inactive' ?>"><?= $x['is_active'] ? 'Aktif' : 'Nonaktif' ?></span></td>
            <td><?= date('d M Y', strtotime($x['created_at'])) ?></td>
            <td>
              <div class="actions">
                <a href="?tab=users&edit=<?= (int)$x['id'] ?>" class="btn btn-sm btn-outline" title="Edit"><i class="fa-solid fa-pen"></i></a>
                <?php if (!$isSelf && $x['role_slug'] !== 'super_admin'): ?>
                  <form method="post" action="?tab=users" onsubmit="return confirm('Reset password?')" style="display:inline">
                    <input type="hidden" name="action" value="reset_pass">
                    <input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <input type="hidden" name="password" value="123456">
                    <button type="submit" class="btn btn-sm btn-outline" title="Reset password (123456)"><i class="fa-solid fa-key"></i></button>
                  </form>
                  <form method="post" action="?tab=users" style="display:inline">
                    <input type="hidden" name="action" value="toggle">
                    <input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline" title="Aktif/Nonaktifkan"><i class="fa-solid fa-power-off"></i></button>
                  </form>
                  <form method="post" action="?tab=users" onsubmit="return confirm('Hapus admin ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
