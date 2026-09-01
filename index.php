<?php
/** Royal Haramain - Halaman Publik Utama (dinamis dari MySQL) */
require_once __DIR__ . '/app/config.php';

// Handle pendaftaran (leads)
$form_msg = '';
$form_ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar'])) {
    $name   = trim($_POST['name'] ?? '');
    $phone  = trim($_POST['phone'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $city   = trim($_POST['city'] ?? '');
    $package= trim($_POST['package'] ?? '');
    $guests = max(1, (int)($_POST['guests'] ?? 1));
    $depart = trim($_POST['departure'] ?? '');
    $notes  = trim($_POST['notes'] ?? '');

    if ($name === '' || $phone === '') {
        $form_msg = 'Nama dan nomor WhatsApp wajib diisi.';
    } else {
        $digits = preg_replace('/\D/', '', $phone);
        $wa_link = $digits ? ('https://wa.me/' . $digits) : '';
        try {
            db()->prepare('INSERT INTO leads (name, phone, email, city, package, guests, departure, notes, wa_link)
                           VALUES (?,?,?,?,?,?,?,?,?)')
                ->execute([$name, $phone, $email ?: null, $city ?: null, $package, $guests, $depart ?: null, $notes ?: null, $wa_link]);
            $form_msg = 'Terima kasih! Data Anda sudah kami terima, tim kami akan segera menghubungi.';
            $form_ok = true;
        } catch (Exception $e) {
            $form_msg = 'Terjadi kesalahan, silakan coba lagi.';
        }
    }
}

// Ambil data hero
$hero = db()->query('SELECT * FROM hero_content WHERE id=1')->fetch() ?: [];
$legal_badges = json_decode($hero['legal_badges'] ?? '[]', true) ?: [];

// Ambil paket + fasilitas
$packages = db()->query('SELECT * FROM packages WHERE is_active=1 ORDER BY featured DESC, sort_order ASC, id ASC')->fetchAll();
$facStmt = db()->prepare('SELECT facility FROM package_facilities WHERE package_id=? ORDER BY id');
$facMap = [];
foreach ($packages as $pk) {
    $facStmt->execute([$pk['id']]);
    $facMap[$pk['id']] = array_column($facStmt->fetchAll(), 'facility');
}

// Ambil artikel
$articles = db()->query('SELECT * FROM articles WHERE is_active=1 ORDER BY id DESC LIMIT 3')->fetchAll();

// Ambil promo popup
$promo = db()->query('SELECT * FROM promos WHERE id=1')->fetch() ?: [];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($hero['title'] ?? 'PT Royal Haramain Internasional') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style_pub.css">
  <style>
    .hero { --hero-bg: url('<?= htmlspecialchars($hero['background_image'] ?? 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') ?>'); }
  </style>
</head>
<body>

  <header class="site">
    <div class="header-inner">
      <a href="#" class="brand">
        <img src="assets/images/logo.png" alt="Logo">
        <div><strong>Royal Haramain</strong><small>Haji • Umroh • Halal Tours</small></div>
      </a>
      <nav class="main-nav">
        <a href="#beranda">Beranda</a>
        <a href="#paket">Paket</a>
        <a href="#tentang">Tentang</a>
        <a href="#berita">Berita</a>
        <a href="#lokasi">Lokasi</a>
      </nav>
      <button type="button" class="gradient-button" onclick="openBooking()">Daftar 1-Klik →</button>
      <button class="mobile-menu" onclick="openBooking()">☰</button>
    </div>
  </header>

  <main>
    <!-- ================= HERO (dari DB) ================= -->
    <section class="hero" id="beranda">
      <div class="container hero-grid">
        <div>
          <?php if (!empty($hero['subtitle'])): ?>
            <span class="eyebrow" style="color:#f3e5ab;background:rgba(212,175,55,.15)">✦ <?= htmlspecialchars($hero['subtitle']) ?></span>
          <?php endif; ?>
          <h1><?= htmlspecialchars($hero['title'] ?? '') ?></h1>
          <?php if (!empty($hero['badge_line'])): ?>
            <p class="lead"><?= htmlspecialchars($hero['badge_line']) ?></p>
          <?php endif; ?>
          <?php if (!empty($hero['quote'])): ?>
            <div class="hero-quote">"<?= htmlspecialchars($hero['quote']) ?>"<cite>— <?= htmlspecialchars($hero['quote_source'] ?? '') ?></cite></div>
          <?php endif; ?>
          <div class="hero-actions">
            <?php if (!empty($hero['primary_btn_text'])): ?>
              <button class="gold-button" onclick="openBooking('<?= htmlspecialchars($hero['primary_btn_text']) ?>')">✈ <?= htmlspecialchars($hero['primary_btn_text']) ?></button>
            <?php endif; ?>
            <?php if (!empty($hero['secondary_btn_text'])): ?>
              <a class="gradient-button" href="<?= htmlspecialchars($hero['secondary_btn_url'] ?: '#paket') ?>"><?= htmlspecialchars($hero['secondary_btn_text']) ?> →</a>
            <?php endif; ?>
          </div>
          <div class="stats">
            <div><strong>15+</strong><span>Tahun Pengalaman</span></div>
            <div><strong>25.000+</strong><span>Jamaah Diberangkatkan</span></div>
            <div><strong>100%</strong><span>Legalitas Kemenag</span></div>
          </div>
        </div>
        <div class="hero-image">
          <img src="<?= htmlspecialchars($hero['background_image'] ?? '') ?>" alt="Tanah Suci" onerror="this.src='assets/images/logo.png'">
          <div class="hero-caption">
            <small class="gold-text">★ PAKET UNGGULAN BULAN INI</small>
            <strong><?= htmlspecialchars($hero['title'] ?? '') ?></strong>
            <small><?= htmlspecialchars($hero['badge_line'] ?? '') ?></small>
          </div>
        </div>
      </div>
    </section>

    <?php if ($legal_badges): ?>
    <div class="badges-strip">
      <div class="container">
        <?php foreach ($legal_badges as $b): ?><span class="badge-item"><?= htmlspecialchars($b) ?></span><?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- ============ PAKET (dari DB) ============ -->
    <?php if ($packages): ?>
    <section id="paket">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.1);color:var(--emerald)">Pilihan Paket Suci</span>
          <h2>Paket Umrah & Haji VIP Pilihan</h2>
          <p>Pilih perjalanan ibadah yang dirancang untuk kenyamanan Anda dan keluarga.</p>
        </div>
        <div class="packages">
          <?php foreach ($packages as $pk): ?>
            <article class="card package<?= $pk['featured'] ? ' popular' : '' ?>" style="overflow:visible">
              <?php if ($pk['featured']): ?><div class="popular-label">★ <?= htmlspecialchars($pk['badge'] ?: 'Paling Diminati') ?></div><?php endif; ?>
              <div>
                <?php if ($pk['duration']): ?><span class="eyebrow" style="background:rgba(4,106,56,.1);color:var(--emerald)"><?= htmlspecialchars($pk['duration']) ?></span><?php endif; ?>
                <h3><?= htmlspecialchars($pk['title']) ?></h3>
                <div class="price">
                  <small>Mulai dari</small>
                  <strong><?= htmlspecialchars($pk['price']) ?></strong>
                  <?php if ($pk['price_old']): ?><small style="text-decoration:line-through;color:#adb5bd"><?= htmlspecialchars($pk['price_old']) ?></small><?php endif; ?>
                </div>
                <?php if (!empty($facMap[$pk['id']])): ?>
                  <ul>
                    <?php foreach ($facMap[$pk['id']] as $f): ?><li><?= htmlspecialchars($f) ?></li><?php endforeach; ?>
                  </ul>
                <?php endif; ?>
              </div>
              <button class="gradient-button" onclick="openBooking('<?= htmlspecialchars($pk['title']) ?>')">Daftar Paket Ini →</button>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ============ TENTANG ============ -->
    <section id="tentang" style="background:var(--white)">
      <div class="container about-grid">
        <img src="https://images.unsplash.com/photo-1580418827493-f2b22c37d3b5?w=900&auto=format&fit=crop" alt="Tentang">
        <div>
          <span class="eyebrow" style="background:rgba(4,106,56,.1);color:var(--emerald)">Tentang Kami</span>
          <h2 style="font-size:32px;color:var(--emerald-dark);margin:14px 0 16px">Menjadi Sebaik-baiknya Pelayan Tamu Allah</h2>
          <p style="color:var(--muted);margin-bottom:14px">PT Royal Haramain Internasional adalah travel haji, umroh dan halal tours yang menyediakan berbagai layanan untuk memudahkan umat Muslim dalam mewujudkan tujuan mulia.</p>
          <ul class="about-list">
            <li>Hubungan interpersonal yang kekeluargaan dengan jamaah</li>
            <li>Meningkatkan pelayanan haji, umrah & halal tours sepenuh hati</li>
            <li>Memudahkan program pembayaran biaya haji dan umrah</li>
            <li>Menjaga kualitas syariat dalam setiap ibadah</li>
          </ul>
        </div>
      </div>
    </section>

    <!-- ============ BERITA (dari DB) ============ -->
    <?php if ($articles): ?>
    <section id="berita">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.1);color:var(--emerald)">Kabar Makkah & Madinah</span>
          <h2>Berita Terkini Tanah Suci</h2>
          <p>Informasi seputar kebijakan, fasilitas, dan persiapan ibadah jamaah.</p>
        </div>
        <div class="news">
          <?php foreach ($articles as $a): ?>
            <article>
              <img src="<?= htmlspecialchars($a['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($a['title']) ?>" onerror="this.style.display='none'">
              <div class="news-body">
                <?php if ($a['date']): ?><div class="news-date"><?= htmlspecialchars($a['date']) ?></div><?php endif; ?>
                <h3><?= htmlspecialchars($a['title']) ?></h3>
                <p><?= htmlspecialchars($a['excerpt']) ?></p>
              </div>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ============ LOKASI ============ -->
    <section id="lokasi" style="background:var(--white)">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.1);color:var(--emerald)">Temukan Kami</span>
          <h2>Lokasi Kantor Terdekat</h2>
          <p>Kunjungi kantor kami di berbagai lokasi strategis untuk konsultasi langsung.</p>
        </div>
        <div class="offices">
          <div class="office"><h3>Kantor Pusat — Jakarta</h3><p>Jl. Sudirman No. 88, Jakarta Pusat 10210</p><a href="tel:+6281120000180">+62 811 2000 0180</a></div>
          <div class="office"><h3>Cabang Surabaya</h3><p>Jl. Pemuda No. 45, Surabaya, Jawa Timur</p><a href="tel:+628113290037">+62 811 329 0037</a></div>
          <div class="office"><h3>Cabang Bandung</h3><p>Jl. Asia Afrika No. 12, Bandung, Jawa Barat</p><a href="tel:+628118008846">+62 811 800 8846</a></div>
          <div class="office"><h3>Cabang Makassar</h3><p>Jl. Jend. Sudirman No. 5, Makassar, Sulawesi Selatan</p><a href="tel:+628114502211">+62 811 450 2211</a></div>
        </div>
      </div>
    </section>

    <!-- ============ DAFTAR ============ -->
    <section class="register-section" id="daftar">
      <div class="container register-grid">
        <div>
          <h2 style="font-size:32px;color:#fff;margin-bottom:14px">Amankan Kursi Ibadah Anda</h2>
          <p style="color:#9db5ac;margin-bottom:20px">Isi form berikut, tim kami akan segera menghubungi Anda untuk konsultasi gratis.</p>
          <ul class="register-list">
            <li>Konsultasi gratis & tanpa biaya</li>
            <li>Penawaran paket terbaru</li>
            <li>Bantuan pengurusan dokumen</li>
            <li>Pembimbingan lengkap ke Tanah Suci</li>
          </ul>
        </div>
        <div class="form-card">
          <h3>Daftar & Konsultasi</h3>
          <?php if ($form_msg): ?>
            <div class="form-msg <?= $form_ok ? 'ok' : 'err' ?>"><?= htmlspecialchars($form_msg) ?></div>
          <?php endif; ?>
          <form method="post" action="#daftar">
            <input type="hidden" name="daftar" value="1">
            <div class="fm"><label>Nama Lengkap *</label><input type="text" name="name" required></div>
            <div class="fm"><label>Nomor WhatsApp *</label><input type="text" name="phone" placeholder="08xxxxxxxxxx" required></div>
            <div class="fm"><label>Email (opsional)</label><input type="email" name="email"></div>
            <div class="fm"><label>Kota Asal</label><input type="text" name="city"></div>
            <div class="fm"><label>Pilihan Paket</label>
              <select name="package">
                <?php foreach ($packages as $pk): ?><option value="<?= htmlspecialchars($pk['title']) ?>"><?= htmlspecialchars($pk['title']) ?></option><?php endforeach; ?>
                <option value="Konsultasi Umum">Konsultasi Umum</option>
              </select>
            </div>
            <div class="fm"><label>Jumlah Jamaah</label><input type="number" name="guests" value="1" min="1"></div>
            <div class="fm"><label>Rencana Keberangkatan</label><input type="text" name="departure" placeholder="Contoh: Desember 2026"></div>
            <div class="fm"><label>Catatan</label><textarea name="notes" rows="2"></textarea></div>
            <button type="submit" class="gold-button" style="width:100%">Kirim & Konsultasi Gratis</button>
          </form>
        </div>
      </div>
    </section>
  </main>

  <footer class="site">
    <div class="container footer-grid">
      <div>
        <div class="brand-text" style="font-family:'Cinzel',serif;font-size:18px;color:#fff">Royal Haramain</div>
        <p style="font-size:13px;margin-bottom:14px">Travel haji, umroh & halal tours terpercaya yang melayani para tamu Allah dengan sepenuh hati.</p>
        <p style="font-size:13px">Jl. Sudirman No. 88, Jakarta Pusat, DKI Jakarta 10210</p>
      </div>
      <div>
        <h4>Navigasi</h4>
        <a href="#beranda">Beranda</a><a href="#paket">Paket</a><a href="#daftar">Daftar</a>
      </div>
      <div>
        <h4>Layanan</h4>
        <a href="#paket">Umroh Reguler</a><a href="#paket">Umroh Plus</a><a href="#paket">Haji Khusus</a>
      </div>
      <div>
        <h4>Kontak</h4>
        <a href="tel:+6281120000180">+62 811 2000 0180</a>
        <a href="mailto:info@royalharamain.com">info@royalharamain.com</a>
        <a href="admin/login.php">Login Admin</a>
      </div>
    </div>
    <div class="footer-bottom"><div class="container">© <?= date('Y') ?> PT ROYAL HARAMAIN INTERNASIONAL. All rights reserved.</div></div>
  </footer>

  <?php if (!empty($promo['enabled'])): ?>
  <div class="modal-overlay" id="promoModal">
    <div class="modal-card">
      <button class="modal-close" onclick="closePromo()">✕</button>
      <?php if ($promo['image_url']): ?><img src="<?= htmlspecialchars($promo['image_url']) ?>" alt="Promo"><?php endif; ?>
      <div class="modal-body">
        <?php if ($promo['badge']): ?><span class="eyebrow" style="background:rgba(212,175,55,.2);color:#8a6d1f"><?= htmlspecialchars($promo['badge']) ?></span><?php endif; ?>
        <h3 style="margin-top:8px"><?= htmlspecialchars($promo['title']) ?></h3>
        <p><?= htmlspecialchars($promo['message']) ?></p>
        <button class="gold-button" onclick="goPromo('<?= htmlspecialchars($promo['link'] ?: '#daftar') ?>')">Lihat Penawaran</button>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <script>
    function openBooking(pkg) {
      var el = document.querySelector('#daftar');
      if (el) {
        el.scrollIntoView({ behavior: 'smooth' });
        var sel = document.querySelector('select[name=package]');
        if (sel && pkg) { for (var i=0;i<sel.options.length;i++){ if(sel.options[i].text===pkg){ sel.selectedIndex=i; break; } } }
      }
    }
    function closePromo(){ document.getElementById('promoModal').classList.remove('show'); }
    function goPromo(url){ closePromo(); if(url && url!=='#') window.location.href=url; else openBooking(); }
    <?php if (!empty($promo['enabled'])): ?>
    setTimeout(function(){ var m=document.getElementById('promoModal'); if(m) m.classList.add('show'); }, (parseInt('<?= (int)($promo['delay'] ?? 3) ?>')||3)*1000);
    <?php endif; ?>
  </script>
</body>
</html>
