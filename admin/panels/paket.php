<?php
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $price_old = trim($_POST['price_old'] ?? '');
            $duration = trim($_POST['duration'] ?? '');
            $badge = trim($_POST['badge'] ?? '');
            $featured = isset($_POST['featured']) ? 1 : 0;
            $image = trim($_POST['image_url'] ?? '');
            $url = trim($_POST['url'] ?? '#');
            if ($title === '' || $price === '') throw new Exception('Judul & harga wajib diisi.');

            if ($id > 0) {
                $stmt = db()->prepare('UPDATE packages SET title=?, price=?, price_old=?, duration=?, badge=?, featured=?, image_url=?, url=? WHERE id=?');
                $stmt->execute([$title, $price, $price_old ?: null, $duration, $badge, $featured, $image, $url, $id]);
            } else {
                $stmt = db()->prepare('INSERT INTO packages (title, price, price_old, duration, badge, featured, image_url, url) VALUES (?,?,?,?,?,?,?,?)');
                $stmt->execute([$title, $price, $price_old ?: null, $duration, $badge, $featured, $image, $url]);
                $id = (int)db()->lastInsertId();
            }
            // fasilitas
            db()->prepare('DELETE FROM package_facilities WHERE package_id=?')->execute([$id]);
            $fac = array_values(array_filter(array_map('trim', explode("\n", $_POST['facilities'] ?? '')), 'strlen'));
            $ins = db()->prepare('INSERT INTO package_facilities (package_id, facility) VALUES (?,?)');
            foreach ($fac as $f) $ins->execute([$id, $f]);
            $msg = 'Paket disimpan.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('DELETE FROM packages WHERE id=?')->execute([$id]);
            $msg = 'Paket dihapus.';
        }
    } catch (Exception $e) {
        $msg = 'Gagal: ' . $e->getMessage();
    }
}

$packages = db()->query('SELECT * FROM packages ORDER BY sort_order ASC, id ASC')->fetchAll();

$editing = null;
if (isset($_GET['edit'])) {
    foreach ($packages as $x) if ((int)$x['id'] === (int)$_GET['edit']) { $editing = $x; break; }
    if ($editing) {
        $fs = db()->prepare('SELECT facility FROM package_facilities WHERE package_id=? ORDER BY id');
        $fs->execute([$editing['id']]);
        $editing['facilities_list'] = array_column($fs->fetchAll(), 'facility');
    }
}
?>
<?php if ($msg): ?>
  <div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($msg) ?></p></div>
<?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-box-open"></i> <?= $editing ? 'Edit Paket' : 'Tambah Paket Baru' ?></h2>
  <?php if ($editing): ?><p class="hint" style="margin-top:-10px;margin-bottom:14px">Edit paket #<?= (int)$editing['id'] ?></p><?php endif; ?>
  <form method="post" action="?tab=paket">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
    <input type="hidden" name="action" value="save">
    <div class="grid">
      <div class="field"><label>Nama Paket *</label><input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required></div>
      <div class="field"><label>Harga *</label><input type="text" name="price" value="<?= htmlspecialchars($editing['price'] ?? '') ?>" required></div>
      <div class="field"><label>Harga Coret (opsional)</label><input type="text" name="price_old" value="<?= htmlspecialchars($editing['price_old'] ?? '') ?>" placeholder="Rp 32.000.000"></div>
      <div class="field"><label>Durasi</label><input type="text" name="duration" value="<?= htmlspecialchars($editing['duration'] ?? '') ?>" placeholder="9 Hari"></div>
      <div class="field"><label>Badge (opsional)</label><input type="text" name="badge" value="<?= htmlspecialchars($editing['badge'] ?? '') ?>" placeholder="PALING DIMINATI"></div>
      <div class="field"><label>Link Daftar</label><input type="text" name="url" value="<?= htmlspecialchars($editing['url'] ?? '#') ?>"></div>
      <div class="field" style="grid-column:1/-1"><label>URL Gambar</label><input type="text" name="image_url" value="<?= htmlspecialchars($editing['image_url'] ?? '') ?>"><p class="hint">URL atau path /assets/images/xxx.jpg</p></div>
    </div>
    <div class="field">
      <label>Fasilitas (satu per baris)</label>
      <textarea name="facilities" rows="5"><?= htmlspecialchars(implode("\n", $editing['facilities_list'] ?? [])) ?></textarea>
    </div>
    <div class="field checkbox-row"><input type="checkbox" name="featured" id="pf" <?= !empty($editing['featured']) ? 'checked' : '' ?>><label for="pf">Jadikan Paket Unggulan (Featured)</label></div>
    <div style="display:flex;gap:10px">
      <button type="submit" class="btn btn-primary" <?= $readonly?'disabled':'' ?>><i class="fa-solid fa-floppy-disk"></i> Simpan Paket</button>
      <?php if ($editing): ?><a href="?tab=paket" class="btn btn-secondary">Batal</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <h2><i class="fa-solid fa-list"></i> Daftar Paket (<span><?= count($packages) ?></span>)</h2>
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Gambar</th><th>Nama Paket</th><th>Harga</th><th>Durasi</th><th>Unggulan</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($packages as $x): ?>
          <tr>
            <td><?php if ($x['image_url']): ?><img src="<?= htmlspecialchars($x['image_url']) ?>" class="img-thumb" onerror="this.style.opacity=.2"><?php endif; ?></td>
            <td><strong><?= htmlspecialchars($x['title']) ?></strong><?= $x['badge'] ? ' <span class="badge badge-active">'.htmlspecialchars($x['badge']).'</span>' : '' ?></td>
            <td><?= htmlspecialchars($x['price']) ?></td>
            <td><?= htmlspecialchars($x['duration']) ?></td>
            <td><?= $x['featured'] ? '<span class="badge badge-active">★ Unggulan</span>' : '<span class="muted" style="color:#6c757d">—</span>' ?></td>
            <td>
              <div class="actions">
                <a href="?tab=paket&edit=<?= (int)$x['id'] ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=paket" onsubmit="return confirm('Hapus paket ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$packages): ?><tr><td colspan="6" class="empty">Belum ada paket.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
