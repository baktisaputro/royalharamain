<?php
/** Panel: Edit Hero Page + Tema Website (seluruh halaman publik) */
require_once __DIR__ . '/../../app/upload.php';

$readonly = ($role === 'viewer');
$save_msg = '';

// ============ SIMPAN (konten hero + upload gambar + tema) ============
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$readonly) {

    try {
        // --- bagian gambar: prioritas upload file > URL ---
        $new_bg = '';
        $new_bg_path = '';
        $has_file = isset($_FILES['bg_file']) && isset($_FILES['bg_file']['name']) && $_FILES['bg_file']['name'] !== '';
        if ($has_file) {
            $up = handle_image_upload($_FILES['bg_file'], 'hero');
            if (!$up['ok']) {
                throw new Exception($up['error']);
            }
            $new_bg_path = $up['path'];
            $new_bg = BASE_URL . '/' . $up['path'];
        } else {
            // pakai URL manual (jika diisi dan bukan path upload lama)
            $url = trim($_POST['background_image'] ?? '');
            if ($url !== '' && strpos($url, BASE_URL . '/uploads/') !== 0) {
                $new_bg = $url;
            }
        }

        $fields = [
            'title'             => trim($_POST['title'] ?? ''),
            'subtitle'          => trim($_POST['subtitle'] ?? ''),
            'quote'             => trim($_POST['quote'] ?? ''),
            'quote_source'      => trim($_POST['quote_source'] ?? ''),
            'primary_btn_text'  => trim($_POST['primary_btn_text'] ?? ''),
            'primary_btn_url'   => trim($_POST['primary_btn_url'] ?? ''),
            'secondary_btn_text'=> trim($_POST['secondary_btn_text'] ?? ''),
            'secondary_btn_url' => trim($_POST['secondary_btn_url'] ?? ''),
            'badge_line'        => trim($_POST['badge_line'] ?? ''),
        ];

        // ambil data lama untuk menghapus file lama bila diganti
        $old = db()->query('SELECT background_image, hero_bg_path FROM hero_content WHERE id=1')->fetch();

        // background: jika ada upload baru, pakai itu; jika tidak, jaga nilai lama bila kosong
        if ($new_bg_path !== '') {
            $fields['background_image'] = $new_bg;
            $fields['hero_bg_path'] = $new_bg_path;
        } else {
            // kalau user menghapus URL & tidak upload, simpan sesuai yang diketik (bisa empty)
            $fields['background_image'] = $new_bg !== '' ? $new_bg : ($old && $old['hero_bg_path'] ? $old['background_image'] : $new_bg);
            $fields['hero_bg_path'] = $old['hero_bg_path'] ?? '';
        }

        // legal badges
        $badges = array_values(array_filter(array_map('trim', explode("\n", $_POST['legal_badges'] ?? '')), 'strlen'));
        $fields['legal_badges'] = json_encode($badges, JSON_UNESCAPED_UNICODE);

        $set = ''; $vals = [];
        foreach ($fields as $k => $v) { $set .= ($set ? ', ' : '') . "`$k` = ?"; $vals[] = $v; }
        db()->prepare("UPDATE hero_content SET $set WHERE id = 1")->execute($vals);

        // hapus file lama bila diganti upload baru
        if ($new_bg_path !== '' && $old && !empty($old['hero_bg_path']) && $old['hero_bg_path'] !== $new_bg_path) {
            delete_upload($old['hero_bg_path']);
        }

        // --- TEMA WEBSITE ---
        $theme_preset = trim($_POST['theme_preset'] ?? 'emerald-gold');
        $primary   = trim($_POST['primary_color'] ?? '#046a38');
        $secondary = trim($_POST['secondary_color'] ?? '#023d1f');
        $accent    = trim($_POST['accent_color'] ?? '#d4af37');
        // validasi hex
        $hex = function ($c) { return preg_match('/^#[0-9a-fA-F]{3,8}$/', $c) ? $c : '#046a38'; };
        db()->prepare('UPDATE settings SET theme_preset=?, primary_color=?, secondary_color=?, accent_color=? WHERE id=1')
            ->execute([$theme_preset, $hex($primary), $hex($secondary), $hex($accent)]);

        $save_msg = 'Berhasil disimpan.';
    } catch (Exception $e) {
        $save_msg = 'Gagal menyimpan: ' . $e->getMessage();
    }
}

$hero = db()->query('SELECT * FROM hero_content WHERE id = 1')->fetch();
$legal_badges = json_decode($hero['legal_badges'] ?? '[]', true) ?: [];

$settings = db()->query('SELECT * FROM settings WHERE id=1')->fetch() ?: [];

// SOS (digunakan untuk preview tema di panel)
$sos = [
    'primary'   => $settings['primary_color'] ?: '#046a38',
    'secondary' => $settings['secondary_color'] ?: '#023d1f',
    'accent'    => $settings['accent_color'] ?: '#d4af37',
];
?>
<?php if ($save_msg): ?>
  <div class="card" style="border-color:<?= strpos($save_msg,'Gagal')===0?'#dc3545':'#198754' ?>"><p><?= htmlspecialchars($save_msg) ?></p></div>
<?php endif; ?>

<form method="post" action="?tab=hero" enctype="multipart/form-data">
  <!-- ============ KONTEN HERO ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-house"></i> Konten Utama Hero</h2>
    <div class="grid">
      <div class="field" style="grid-column:1/-1"><label>Judul Utama (Title)</label><input type="text" name="title" value="<?= htmlspecialchars($hero['title'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
      <div class="field" style="grid-column:1/-1"><label>Sub Judul / Tagline</label><input type="text" name="subtitle" value="<?= htmlspecialchars($hero['subtitle'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
      <div class="field" style="grid-column:1/-1"><label>Baris Aksi (badge_line)</label><input type="text" name="badge_line" value="<?= htmlspecialchars($hero['badge_line'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
    </div>
  </div>

  <!-- ============ KUTIPAN ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-quote-right"></i> Kutipan (Hadits)</h2>
    <div class="field"><label>Teks Kutipan</label><textarea name="quote" rows="3" <?= $readonly?'disabled':'' ?>><?= htmlspecialchars($hero['quote'] ?? '') ?></textarea></div>
    <div class="field"><label>Sumber Kutipan</label><input type="text" name="quote_source" value="<?= htmlspecialchars($hero['quote_source'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
  </div>

  <!-- ============ GAMBAR LATAR ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-image"></i> Gambar Latar (Background)</h2>
    <div class="field">
      <label>Upload Gambar dari Komputer <span class="hint">(otomatis dikompres, max 5MB)</span></label>
      <input type="file" name="bg_file" accept="image/jpeg,image/png,image/webp,image/gif" <?= $readonly?'disabled':'' ?>>
      <p class="hint">ATAU gunakan URL gambar di bawah (salah satu).</p>
    </div>
    <div class="field">
      <label>URL Gambar Background</label>
      <input type="text" name="background_image" value="<?= htmlspecialchars($hero['background_image'] ?? '') ?>" placeholder="https://..." <?= $readonly?'disabled':'' ?>>
    </div>
    <?php $show_bg = $hero['background_image'] ?? ''; $show_bg_disp = $show_bg ? (strpos($show_bg,'http')===0?$show_bg:(BASE_URL.'/'.$show_bg)) : ''; ?>
    <?php if ($show_bg_disp): ?>
      <div class="field">
        <img src="<?= htmlspecialchars($show_bg_disp) ?>" alt="Preview" style="max-width:100%;border-radius:10px;max-height:200px;object-fit:cover;border:1px solid var(--border)">
        <p class="hint">Preview gambar latar saat ini. Unggah gambar baru di atas untuk mengganti.</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- ============ TOMBOL AKSI ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-arrow-pointer"></i> Tombol Aksi</h2>
    <div class="grid">
      <div class="field"><label>Tombol Utama - Teks</label><input type="text" name="primary_btn_text" value="<?= htmlspecialchars($hero['primary_btn_text'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
      <div class="field"><label>Tombol Utama - URL</label><input type="text" name="primary_btn_url" value="<?= htmlspecialchars($hero['primary_btn_url'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
      <div class="field"><label>Tombol Kedua - Teks</label><input type="text" name="secondary_btn_text" value="<?= htmlspecialchars($hero['secondary_btn_text'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
      <div class="field"><label>Tombol Kedua - URL</label><input type="text" name="secondary_btn_url" value="<?= htmlspecialchars($hero['secondary_btn_url'] ?? '') ?>" <?= $readonly?'disabled':'' ?>></div>
    </div>
  </div>

  <!-- ============ LEGALITAS ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-shield-halved"></i> Legalitas / Badge</h2>
    <div class="field">
      <label>Badge Legalitas (satu per baris)</label>
      <textarea name="legal_badges" rows="4" <?= $readonly?'disabled':'' ?>><?= htmlspecialchars(implode("\n", $legal_badges)) ?></textarea>
      <p class="hint">Contoh: AMPHURI, PIHK No. 394, PPIU No. U.533, Kemenag</p>
    </div>
  </div>

  <!-- ============ TEMA WEBSITE (seluruh halaman) ============ -->
  <div class="card">
    <h2><i class="fa-solid fa-palette"></i> Tema Website (warna seluruh halaman)</h2>
    <div class="field">
      <label>Preset Tema Cepat</label>
      <select name="theme_preset" id="themePreset" <?= $readonly?'disabled':'' ?>>
        <option value="emerald-gold" <?= ($settings['theme_preset']??'')==='emerald-gold'?'selected':'' ?>>Emerald & Gold (logo)</option>
        <option value="blue-gold" <?= ($settings['theme_preset']??'')==='blue-gold'?'selected':'' ?>>Biru & Emas</option>
        <option value="red-gold" <?= ($settings['theme_preset']??'')==='red-gold'?'selected':'' ?>>Merah & Emas</option>
        <option value="navy-silver" <?= ($settings['theme_preset']??'')==='navy-silver'?'selected':'' ?>>Navy & Perak</option>
        <option value="custom" <?= ($settings['theme_preset']??'')==='custom'?'selected':'' ?>>Kustom (atur manual)</option>
      </select>
      <p class="hint">Pilih preset maka warna otomatis terisi. Pilih "Kustom" untuk mengatur sendiri.</p>
    </div>
    <div class="grid" style="margin-top:6px">
      <div class="field">
        <label>Warna Primer (tombol/aksen utama)</label>
        <div style="display:flex;gap:10px;align-items:center">
          <input type="color" name="primary_color" id="primary_color" value="<?= htmlspecialchars($sos['primary']) ?>" class="color-swatch" <?= $readonly?'disabled':'' ?>>
          <input type="text" id="primary_color_txt" value="<?= htmlspecialchars($sos['primary']) ?>" class="color-hex" <?= $readonly?'disabled':'' ?>>
        </div>
      </div>
      <div class="field">
        <label>Warna Gelap (header/footer)</label>
        <div style="display:flex;gap:10px;align-items:center">
          <input type="color" name="secondary_color" id="secondary_color" value="<?= htmlspecialchars($sos['secondary']) ?>" class="color-swatch" <?= $readonly?'disabled':'' ?>>
          <input type="text" id="secondary_color_txt" value="<?= htmlspecialchars($sos['secondary']) ?>" class="color-hex" <?= $readonly?'disabled':'' ?>>
        </div>
      </div>
      <div class="field">
        <label>Warna Aksen (emas/highlight)</label>
        <div style="display:flex;gap:10px;align-items:center">
          <input type="color" name="accent_color" id="accent_color" value="<?= htmlspecialchars($sos['accent']) ?>" class="color-swatch" <?= $readonly?'disabled':'' ?>>
          <input type="text" id="accent_color_txt" value="<?= htmlspecialchars($sos['accent']) ?>" class="color-hex" <?= $readonly?'disabled':'' ?>>
        </div>
      </div>
    </div>
    <div class="theme-preview" style="margin-top:14px;padding:16px;border-radius:12px;background:linear-gradient(135deg,var(--sos-secondary,#023d1f),#01170d);color:#fff">
      <small style="opacity:.8;letter-spacing:1px;text-transform:uppercase;font-size:11px">Pratinjau</small>
      <strong style="display:block;font-family:'Cinzel',serif;font-size:20px;margin:6px 0">Warna Tema Anda</strong>
      <div style="display:flex;gap:10px;margin-top:8px">
        <span style="flex:1;height:34px;border-radius:8px;background:var(--sos-primary,var(--sos-primary,#046a38))"></span>
        <span style="flex:1;height:34px;border-radius:8px;background:var(--sos-secondary,#023d1f)"></span>
        <span style="flex:1;height:34px;border-radius:8px;background:var(--sos-accent,#d4af37)"></span>
      </div>
    </div>
    <style id="sosStyle"></style>
    <script>
      (function(){
        function upd(){
          var p=txt('primary_color'), s=txt('secondary_color'), a=txt('accent_color');
          document.documentElement.style.setProperty('--sos-primary',p);
          document.documentElement.style.setProperty('--sos-secondary',s);
          document.documentElement.style.setProperty('--sos-accent',a);
        }
        function txt(id){
          return document.getElementById(id+'_txt') ? document.getElementById(id+'_txt').value : '#000';
        }
        var presets={ 'emerald-gold':['#046a38','#023d1f','#d4af37'], 'blue-gold':['#0d4fa0','#08316b','#d4af37'], 'red-gold':['#a5232c','#6d1218','#d4af37'], 'navy-silver':['#1c2b4f','#101a33','#c0c8d8'], 'custom':null };
        var sel=document.getElementById('themePreset');
        if(sel){
          sel.addEventListener('change',function(){
            var p=presets[this.value];
            if(!p)return;
            setPrc('primary_color',p[0]); setPrc('secondary_color',p[1]); setPrc('accent_color',p[2]);
          });
        }
        function setPrc(id,val){
          var sw=document.getElementById(id); if(sw)sw.value=val;
          var tx=document.getElementById(id+'_txt'); if(tx)tx.value=val;
          upd();
        }
        ['primary_color','secondary_color','accent_color'].forEach(function(id){
          var sw=document.getElementById(id), tx=document.getElementById(id+'_txt');
          if(sw) sw.addEventListener('input',function(){ if(tx)tx.value=this.value; upd(); });
          if(tx) tx.addEventListener('input',function(){ var v=this.value; if(/^#[0-9a-fA-F]{6}$/.test(v)&&sw){sw.value=v; upd();} });
        });
        upd();
      })();
    </script>
  </div>

  <?php if (!$readonly): ?>
    <div class="card" style="display:flex;justify-content:flex-end">
      <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
    </div>
  <?php else: ?>
    <div class="card"><p class="hint" style="margin:0"><i class="fa-solid fa-eye"></i> Mode viewer: hanya bisa melihat.</p></div>
  <?php endif; ?>
</form>

<style>
  input.color-swatch { width:60px; height:46px; padding:2px; border:1px solid #ced4da; border-radius:8px; }
  input.color-hex { width:140px; padding:10px 12px; border:1px solid #ced4da; border-radius:8px; font-size:14px; font-family:monospace; }
</style>
