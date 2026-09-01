<?php
/** Panel: Edit Hero Page (Beranda atas) - semua role aktif bisa edit kecuali viewer */
$readonly = ($role === 'viewer');
$u = current_user();

// Proses simpan
$save_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {
    $fields = [
        'title'             => trim($_POST['title'] ?? ''),
        'subtitle'          => trim($_POST['subtitle'] ?? ''),
        'quote'             => trim($_POST['quote'] ?? ''),
        'quote_source'      => trim($_POST['quote_source'] ?? ''),
        'background_image'  => trim($_POST['background_image'] ?? ''),
        'primary_btn_text'  => trim($_POST['primary_btn_text'] ?? ''),
        'primary_btn_url'   => trim($_POST['primary_btn_url'] ?? ''),
        'secondary_btn_text'=> trim($_POST['secondary_btn_text'] ?? ''),
        'secondary_btn_url' => trim($_POST['secondary_btn_url'] ?? ''),
        'badge_line'        => trim($_POST['badge_line'] ?? ''),
    ];
    // legal badges: textarea -> array -> json
    $badges = array_values(array_filter(array_map('trim', explode("\n", $_POST['legal_badges'] ?? '')), 'strlen'));
    $fields['legal_badges'] = json_encode($badges, JSON_UNESCAPED_UNICODE);

    $set = '';
    $vals = [];
    foreach ($fields as $k => $v) {
        $set .= ($set ? ', ' : '') . "`$k` = ?";
        $vals[] = $v;
    }
    $vals[] = 1;
    try {
        $stmt = db()->prepare("UPDATE hero_content SET $set WHERE id = 1");
        $stmt->execute($vals);
        $save_msg = 'Berhasil disimpan.';
    } catch (Exception $e) {
        $save_msg = 'Gagal menyimpan: ' . $e->getMessage();
    }
}

$hero = db()->query('SELECT * FROM hero_content WHERE id = 1')->fetch();
if (!$hero) {
    // fallback default
    $hero = ['title'=>''];
}
$legal_badges = json_decode($hero['legal_badges'] ?? '[]', true) ?: [];
?>
<?php if ($save_msg): ?>
  <div class="card" style="border-color:<?= strpos($save_msg,'Gagal')===0?'#dc3545':'#198754' ?>">
    <p><?= htmlspecialchars($save_msg) ?></p>
  </div>
<?php endif; ?>

<form method="post" action="?tab=hero">
  <div class="card">
    <h2><i class="fa-solid fa-house"></i> Konten Utama Hero</h2>
    <div class="grid">
      <div class="field" style="grid-column:1/-1">
        <label>Judul Utama (Title)</label>
        <input type="text" name="title" value="<?= htmlspecialchars($hero['title'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label>Sub Judul / Tagline</label>
        <input type="text" name="subtitle" value="<?= htmlspecialchars($hero['subtitle'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field" style="grid-column:1/-1">
        <label>Baris Aksi (badge_line)</label>
        <input type="text" name="badge_line" value="<?= htmlspecialchars($hero['badge_line'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
    </div>
  </div>

  <div class="card">
    <h2><i class="fa-solid fa-quote-right"></i> Kutipan (Hadits)</h2>
    <div class="field">
      <label>Teks Kutipan</label>
      <textarea name="quote" rows="3" <?= $readonly?'disabled':'' ?>><?= htmlspecialchars($hero['quote'] ?? '') ?></textarea>
    </div>
    <div class="field">
      <label>Sumber Kutipan</label>
      <input type="text" name="quote_source" value="<?= htmlspecialchars($hero['quote_source'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
    </div>
  </div>

  <div class="card">
    <h2><i class="fa-solid fa-image"></i> Gambar Latar (Background)</h2>
    <div class="field">
      <label>URL Gambar Background</label>
      <input type="text" name="background_image" value="<?= htmlspecialchars($hero['background_image'] ?? '') ?>" placeholder="https://images.unsplash.com/..." <?= $readonly?'disabled':'' ?>>
      <p class="hint">Tempel URL gambar (mis. dari Unsplash) atau upload file ke folder assets/images lalu isi path-nya.</p>
    </div>
    <?php if (!empty($hero['background_image'])): ?>
      <img src="<?= htmlspecialchars($hero['background_image']) ?>" alt="Preview" style="max-width:100%;border-radius:10px;margin-top:8px;max-height:180px;object-fit:cover;">
    <?php endif; ?>
  </div>

  <div class="card">
    <h2><i class="fa-solid fa-arrow-pointer"></i> Tombol Aksi</h2>
    <div class="grid">
      <div class="field">
        <label>Tombol Utama - Teks</label>
        <input type="text" name="primary_btn_text" value="<?= htmlspecialchars($hero['primary_btn_text'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field">
        <label>Tombol Utama - URL</label>
        <input type="text" name="primary_btn_url" value="<?= htmlspecialchars($hero['primary_btn_url'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field">
        <label>Tombol Kedua - Teks</label>
        <input type="text" name="secondary_btn_text" value="<?= htmlspecialchars($hero['secondary_btn_text'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
      <div class="field">
        <label>Tombol Kedua - URL</label>
        <input type="text" name="secondary_btn_url" value="<?= htmlspecialchars($hero['secondary_btn_url'] ?? '') ?>" <?= $readonly?'disabled':'' ?>>
      </div>
    </div>
  </div>

  <div class="card">
    <h2><i class="fa-solid fa-shield-halved"></i> Legalitas / Badge</h2>
    <div class="field">
      <label>Badge Legalitas (satu per baris)</label>
      <textarea name="legal_badges" rows="4" <?= $readonly?'disabled':'' ?>><?= htmlspecialchars(implode("\n", $legal_badges)) ?></textarea>
      <p class="hint">Contoh: AMPHURI, PIHK No. 394, PPIU No. U.533, Kemenag</p>
    </div>
  </div>

  <?php if (!$readonly): ?>
    <div class="card" style="display:flex;justify-content:flex-end">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
    </div>
  <?php else: ?>
    <div class="card"><p class="hint" style="margin:0"><i class="fa-solid fa-eye"></i> Mode viewer: hanya bisa melihat, tidak bisa mengubah.</p></div>
  <?php endif; ?>
</form>
