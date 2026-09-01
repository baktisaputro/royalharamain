<?php
$readonly = ($role === 'viewer');
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'save') {
            $enabled = isset($_POST['enabled']) ? 1 : 0;
            $badge = trim($_POST['badge'] ?? '');
            $title = trim($_POST['title'] ?? '');
            $message = trim($_POST['message'] ?? '');
            $image = trim($_POST['image_url'] ?? '');
            $link = trim($_POST['link'] ?? '#');
            $delay = (int)($_POST['delay'] ?? 3);
            $show_once = isset($_POST['show_once']) ? 1 : 0;
            db()->prepare('INSERT INTO promos (id, enabled, badge, title, message, image_url, link, delay, show_once)
                VALUES (1,?,?,?,?,?,?,?,?)
                ON DUPLICATE KEY UPDATE enabled=VALUES(enabled), badge=VALUES(badge), title=VALUES(title),
                message=VALUES(message), image_url=VALUES(image_url), link=VALUES(link),
                delay=VALUES(delay), show_once=VALUES(show_once)')
                ->execute([$enabled, $badge, $title, $message, $image, $link, $delay, $show_once]);
            $msg = 'Popup promo disimpan.';
        }
    } catch (Exception $e) { $msg = 'Gagal: ' . $e->getMessage(); }
}

$p = db()->query('SELECT * FROM promos WHERE id=1')->fetch() ?: [];
?>
<?php if ($msg): ?><div class="card" style="border-color:<?= strpos($msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($msg) ?></p></div><?php endif; ?>

<div class="card">
  <h2><i class="fa-solid fa-bullhorn"></i> Popup Promo</h2>
  <p class="hint" style="margin-top:-8px;margin-bottom:16px">Popup yang muncul di halaman depan website.</p>
  <form method="post" action="?tab=promo">
    <input type="hidden" name="action" value="save">
    <div class="field checkbox-row mt">
      <input type="checkbox" name="enabled" id="p_enabled" <?= !empty($p['enabled']) ? 'checked' : '' ?>>
      <label for="p_enabled">Aktifkan popup promo</label>
    </div>
    <div class="grid">
      <div class="field"><label>Badge</label><input type="text" name="badge" value="<?= htmlspecialchars($p['badge'] ?? '') ?>" placeholder="PROMO SPESIAL"></div>
      <div class="field"><label>Judul</label><input type="text" name="title" value="<?= htmlspecialchars($p['title'] ?? '') ?>"></div>
      <div class="field"><label>Link / URL</label><input type="text" name="link" value="<?= htmlspecialchars($p['link'] ?? '#') ?>"></div>
      <div class="field"><label>Delay (detik)</label><input type="number" name="delay" value="<?= (int)($p['delay'] ?? 3) ?>" min="0"></div>
      <div class="field" style="grid-column:1/-1"><label>URL Gambar</label><input type="text" name="image_url" value="<?= htmlspecialchars($p['image_url'] ?? '') ?>"></div>
    </div>
    <div class="field"><label>Pesan</label><textarea name="message" rows="3"><?= htmlspecialchars($p['message'] ?? '') ?></textarea></div>
    <div class="field checkbox-row mt">
      <input type="checkbox" name="show_once" id="p_show_once" <?= !empty($p['show_once']) ? 'checked' : '' ?>>
      <label for="p_show_once">Tampilkan sekali saja per pengunjung</label>
    </div>
    <div style="display:flex;gap:10px">
      <button type="submit" class="btn btn-primary" <?= $readonly?'disabled':'' ?>><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
    </div>
  </form>
</div>
