<?php
/** Panel: Galeri Foto (upload & kelola dari admin) */
require_once __DIR__ . '/../../app/upload.php';

$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'upload') {
            $caption = trim($_POST['caption'] ?? '');
            $has_file = isset($_FILES['image_file']) && isset($_FILES['image_file']['name']) && $_FILES['image_file']['name'] !== '';
            if ($has_file) {
                $up = handle_image_upload($_FILES['image_file'], 'galeri');
                if (!$up['ok']) throw new Exception($up['error']);
                db()->prepare('INSERT INTO gallery_images (image_path, caption, sort_order) VALUES (?,?,?)')
                    ->execute([$up['path'], $caption, (int)($_POST['sort_order'] ?? 0)]);
                $msg = 'Gambar berhasil diunggah.';
            } else {
                throw new Exception('Pilih file gambar terlebih dahulu.');
            }
        }
        elseif ($action === 'update') {
            $id = (int)($_POST['id'] ?? 0);
            $caption = trim($_POST['caption'] ?? '');
            $sort = (int)($_POST['sort_order'] ?? 0);
            db()->prepare('UPDATE gallery_images SET caption=?, sort_order=? WHERE id=?')->execute([$caption, $sort, $id]);
            $msg = 'Caption diperbarui.';
        }
        elseif ($action === 'toggle') {
            $id = (int)($_POST['id'] ?? 0);
            db()->prepare('UPDATE gallery_images SET is_active = 1 - is_active WHERE id=?')->execute([$id]);
            $msg = 'Status diubah.';
        }
        elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            $old = db()->prepare('SELECT image_path FROM gallery_images WHERE id=?');
            $old->execute([$id]);
            $row = $old->fetch();
            db()->prepare('DELETE FROM gallery_images WHERE id=?')->execute([$id]);
            if ($row && !empty($row['image_path'])) delete_upload($row['image_path']);
            $msg = 'Gambar dihapus.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$images = db()->query('SELECT * FROM gallery_images ORDER BY sort_order ASC, id DESC')->fetchAll();
?>
<?php if ($msg): ?><div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($msg) ?></p></div><?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-upload"></i> Upload Foto Baru</h2>
  <form method="post" action="?tab=galeri" enctype="multipart/form-data">
    <input type="hidden" name="action" value="upload">
    <div class="grid">
      <div class="field" style="grid-column:1/-1">
        <label>Pilih Gambar <span class="hint">(otomatis dikompres, max 5MB)</span></label>
        <input type="file" name="image_file" accept="image/jpeg,image/png,image/webp,image/gif" required <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field"><label>Keterangan (caption)</label><input type="text" name="caption" placeholder="Contoh: Jamaah di Masjid Nabawi" <?= $readonly?'disabled':'' ?>></div>
      <div class="field"><label>Urutan</label><input type="number" name="sort_order" value="0" <?= $readonly?'disabled':'' ?>></div>
    </div>
    <button type="submit" class="btn btn-primary" <?= $readonly?'disabled':'' ?>><i class="fa-solid fa-cloud-arrow-up"></i> Unggah</button>
  </form>
</div>

<div class="card">
  <h2><i class="fa-solid fa-images"></i> Galeri Foto (<span><?= count($images) ?></span>)</h2>
  <?php if (!$images): ?>
    <p class="empty"><i class="fa-solid fa-image"></i><br>Belum ada foto. Unggah foto pertama di atas.</p>
  <?php else: ?>
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px">
      <?php $edit_id = (int)($_GET['edit'] ?? 0); ?>
      <?php foreach ($images as $x): ?>
        <div style="border:1px solid var(--border);border-radius:10px;overflow:hidden;background:#fff">
          <a href="<?= BASE_URL.'/'.htmlspecialchars($x['image_path']) ?>" target="_blank">
            <img src="<?= BASE_URL.'/'.htmlspecialchars($x['image_path']) ?>" alt="<?= htmlspecialchars($x['caption']) ?>" style="width:100%;height:130px;object-fit:cover;display:block">
          </a>
          <div style="padding:10px">
            <?php if ($edit_id === (int)$x['id']): ?>
              <form method="post" action="?tab=galeri">
                <input type="hidden" name="action" value="update"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                <input type="text" name="caption" value="<?= htmlspecialchars($x['caption']) ?>" placeholder="Keterangan" style="width:100%;padding:6px 8px;border:1px solid #ced4da;border-radius:6px;font-size:13px;margin-bottom:6px">
                <input type="number" name="sort_order" value="<?= (int)$x['sort_order'] ?>" style="width:100%;padding:6px 8px;border:1px solid #ced4da;border-radius:6px;font-size:13px;margin-bottom:8px">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
                <a href="?tab=galeri" class="btn btn-sm btn-secondary">Batal</a>
              </form>
            <?php else: ?>
              <div style="font-size:12px;color:#495057;margin-bottom:8px;min-height:18px"><?= htmlspecialchars($x['caption'] ?: '—') ?></div>
              <div style="display:flex;gap:6px;flex-wrap:wrap">
                <?php if (!$readonly): ?>
                  <a href="?tab=galeri&edit=<?= (int)$x['id'] ?>" class="btn btn-sm btn-outline"><i class="fa-solid fa-pen"></i></a>
                  <form method="post" action="?tab=galeri" style="display:inline">
                    <input type="hidden" name="action" value="toggle"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-outline" title="Aktif/Nonaktif"><i class="fa-solid fa-eye<?= $x['is_active']?'-slash':'' ?>"></i></button>
                  </form>
                  <form method="post" action="?tab=galeri" onsubmit="return confirm('Hapus foto ini?')" style="display:inline">
                    <input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$x['id'] ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button>
                  </form>
                <?php else: ?>
                  <span class="badge <?= $x['is_active']?'badge-active':'badge-inactive' ?>"><?= $x['is_active']?'Aktif':'Nonaktif' ?></span>
                <?php endif; ?>
              </div>
            <?php endif; ?>
            <?php if (!$readonly): ?>
              <div style="margin-top:6px"><span class="badge <?= $x['is_active']?'badge-active':'badge-inactive' ?>"><?= $x['is_active']?'Aktif':'Nonaktif' ?></span></div>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
