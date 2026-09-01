<?php
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $id = (int)($_POST['id'] ?? 0);
            $title = trim($_POST['title'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $date = trim($_POST['date'] ?? '');
            $image = trim($_POST['image_url'] ?? '');
            $url = trim($_POST['url'] ?? '#');
            if ($title === '' || $excerpt === '') throw new Exception('Judul & ringkasan wajib diisi.');
            if ($id > 0) {
                $stmt = db()->prepare('UPDATE articles SET title=?, excerpt=?, date=?, image_url=?, url=? WHERE id=?');
                $stmt->execute([$title, $excerpt, $date, $image, $url, $id]);
            } else {
                $stmt = db()->prepare('INSERT INTO articles (title, excerpt, date, image_url, url) VALUES (?,?,?,?,?)');
                $stmt->execute([$title, $excerpt, $date, $image, $url]);
            }
            $msg = 'Artikel disimpan.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('DELETE FROM articles WHERE id=?')->execute([$id]);
            $msg = 'Artikel dihapus.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$articles = db()->query('SELECT * FROM articles ORDER BY id DESC')->fetchAll();
$editing = null;
if (isset($_GET['edit'])) foreach ($articles as $x) if ((int)$x['id'] === (int)$_GET['edit']) { $editing = $x; break; }
?>
<?php if ($msg): ?><div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($msg) ?></p></div><?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-newspaper"></i> <?= $editing ? 'Edit Artikel' : 'Tambah Artikel Baru' ?></h2>
  <form method="post" action="?tab=artikel">
    <?php if ($editing): ?><input type="hidden" name="id" value="<?= (int)$editing['id'] ?>"><?php endif; ?>
    <input type="hidden" name="action" value="save">
    <div class="grid">
      <div class="field"><label>Judul *</label><input type="text" name="title" value="<?= htmlspecialchars($editing['title'] ?? '') ?>" required></div>
      <div class="field"><label>Tanggal</label><input type="text" name="date" value="<?= htmlspecialchars($editing['date'] ?? '') ?>" placeholder="12 Agustus 2026"></div>
      <div class="field" style="grid-column:1/-1"><label>URL Gambar</label><input type="text" name="image_url" value="<?= htmlspecialchars($editing['image_url'] ?? '') ?>" placeholder="https://..."></div>
      <div class="field" style="grid-column:1/-1"><label>Link Artikel</label><input type="text" name="url" value="<?= htmlspecialchars($editing['url'] ?? '#') ?>"></div>
      <div class="field" style="grid-column:1/-1"><label>Ringkasan *</label><textarea name="excerpt" rows="3" required><?= htmlspecialchars($editing['excerpt'] ?? '') ?></textarea></div>
    </div>
    <div style="display:flex;gap:10px">
      <button type="submit" class="btn btn-primary" <?= $readonly?'disabled':'' ?>><i class="fa-solid fa-floppy-disk"></i> Simpan Artikel</button>
      <?php if ($editing): ?><a href="?tab=artikel" class="btn btn-secondary">Batal</a><?php endif; ?>
    </div>
  </form>
</div>

<div class="card">
  <h2><i class="fa-solid fa-list"></i> Daftar Artikel (<span><?= count($articles) ?></span>)</h2>
  <div class="table-wrap">
    <table class="admin-table">
      <thead><tr><th>Gambar</th><th>Judul</th><th>Tanggal</th><th>Aksi</th></tr></thead>
      <tbody>
        <?php foreach ($articles as $x): ?>
          <tr>
            <td><?php if ($x['image_url']): ?><img src="<?= htmlspecialchars($x['image_url']) ?>" class="img-thumb" onerror="this.style.opacity=.2"><?php endif; ?></td>
            <td><strong><?= htmlspecialchars($x['title']) ?></strong><br><span style="color:#6c757d;font-size:12px"><?= htmlspecialchars(mb_substr($x['excerpt'],0,80)) ?>…</span></td>
            <td><?= htmlspecialchars($x['date']) ?></td>
            <td>
              <div class="actions">
                <a href="?tab=artikel&edit=<?= (int)$x['id'] ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                <?php if (!$readonly): ?>
                  <form method="post" action="?tab=artikel" onsubmit="return confirm('Hapus artikel ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$articles): ?><tr><td colspan="4" class="empty">Belum ada artikel.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
