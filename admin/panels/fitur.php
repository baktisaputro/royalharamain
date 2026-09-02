<?php
/** Panel: Keunggulan / Layanan (section "mengapa memilih kami") */
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $icon = trim($_POST['icon'] ?? 'fa-star');
            $desc = trim($_POST['description'] ?? '');
            $sort = (int)($_POST['sort_order'] ?? 0);
            if ($title === '') throw new Exception('Judul wajib diisi.');
            if ($id > 0) {
                $stmt = db()->prepare('UPDATE features SET icon=?, title=?, description=?, sort_order=? WHERE id=?');
                $stmt->execute([$icon, $title, $desc ?: null, $sort, $id]);
            } else {
                $stmt = db()->prepare('INSERT INTO features (icon, title, description, sort_order) VALUES (?,?,?,?)');
                $stmt->execute([$icon, $title, $desc ?: null, $sort]);
            }
            $msg = 'Keunggulan disimpan.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('DELETE FROM features WHERE id=?')->execute([$id]);
            $msg = 'Keunggulan dihapus.';
        }
        elseif ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('UPDATE features SET is_active = 1 - is_active WHERE id=?')->execute([$id]);
            $msg = 'Status diubah.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$features = db()->query('SELECT * FROM features ORDER BY sort_order ASC, id ASC')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) foreach ($features as $x) if ((int)$x['id'] === (int)$_GET['edit']) { $editing = $x; break; }
$allowed_icons = ['fa-star','fa-certificate','fa-hand-holding-heart','fa-plane','fa-hotel','fa-shield-halved','fa-cash-register','fa-kaaba','fa-users','fa-money-bill-wave','fa-clock','fa-globe','fa-mosque','fa-heart','fa-gem','fa-truck-fast'];
?>
<?php if ($msg): ?><div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($msg) ?></p></div><?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-star"></i> <?= $editing ? 'Edit Keunggulan #'.(int)$editing['id'] : 'Tambah Keunggulan / Layanan' ?></h2>
  <form method="post" action="?tab=fitur">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
    <input type="hidden" name="action" value="save">
    <div class="grid">
      <div class="field"><label>Judul *</label><input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required></div>
      <div class="field"><label>Ikon (FontAwesome)</label>
        <select name="icon">
          <?php foreach ($allowed_icons as $ic): ?>
            <option value="<?= $ic ?>" <?= ($editing['icon'] ?? '') === $ic ? 'selected' : '' ?>><?= $ic ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="field"><label>Urutan</label><input type="number" name="sort_order" value="<?= (int)($editing['sort_order'] ?? 0) ?>"></div>
      <div class="field" style="grid-column:1/-1"><label>Deskripsi</label><textarea name="description" rows="3"><?= htmlspecialchars($editing['description'] ?? '') ?></textarea></div>
    </div>
    <div style="display:flex;gap:10px">
      <button type="submit" class="btn btn-primary" <?= $readonly?'disabled':'' ?>><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
      <?php if ($editing): ?><a href="?tab=fitur" class="btn btn-secondary">Batal</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <h2><i class="fa-solid fa-list"></i> Daftar Keunggulan (<span><?= count($features) ?></span>)</h2>
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Ikon</th><th>Judul</th><th>Deskripsi</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($features as $x): ?>
          <tr>
            <td><span style="font-size:20px;color:var(--primary)"><i class="fa-solid <?= htmlspecialchars($x['icon']) ?>"></i></span></td>
            <td><strong><?= htmlspecialchars($x['title']) ?></strong></td>
            <td style="color:#6c757d"><?= htmlspecialchars(mb_substr($x['description'] ?? '',0,70)) ?><?= mb_strlen($x['description']??'')>70?'…':'' ?></td>
            <td><?= (int)$x['sort_order'] ?></td>
            <td><?= $x['is_active'] ? '<span class="badge badge-active">Aktif</span>' : '<span class="badge badge-inactive">Nonaktif</span>' ?></td>
            <td>
              <div class="actions">
                <a href="?tab=fitur&edit=<?= (int)$x['id'] ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=fitur" style="display:inline">
                    <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline" title="Aktif/Nonaktif"><i class="fa-solid fa-eye<?= $x['is_active']?'-slash':'' ?>"></i></button>
                  </form>
                  <form method="post" action="?tab=fitur" onsubmit="return confirm('Hapus keunggulan ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$features): ?><tr><td colspan="6" class="empty">Belum ada data keunggulan.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
