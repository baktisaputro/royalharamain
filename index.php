<?php
/** Royal Haramain - Halaman Publik Utama (dinamis dari MySQL) */
require_once __DIR__ . '/app/config.php';
require_once __DIR__ . '/app/csrf.php';

// Handle pendaftaran (leads)
$form_msg = '';
$form_ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar'])) {
    if (!csrf_validate($_POST['csrf_token'] ?? '')) {
        $form_msg = 'Sesi telah berakhir. Silakan muat ulang halaman.';
    } else {
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
}

// Handle pesan dari form kontak
$contact_msg = '';
$contact_ok = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['kontak'])) {
    if (!csrf_validate($_POST['csrf_token'] ?? '')) {
        $contact_msg = 'Sesi telah berakhir. Silakan muat ulang halaman.';
    } else {
        $cname    = trim($_POST['cname'] ?? '');
        $cemail   = trim($_POST['cemail'] ?? '');
        $cphone   = trim($_POST['cphone'] ?? '');
        $csubject = trim($_POST['csubject'] ?? '');
        $cmessage = trim($_POST['cmessage'] ?? '');

        if ($cname === '' || $cmessage === '') {
            $contact_msg = 'Nama dan pesan wajib diisi.';
        } else {
            try {
                db()->prepare('INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?,?,?,?,?)')
                    ->execute([$cname, $cemail ?: null, $cphone ?: null, $csubject ?: null, $cmessage]);
                $contact_msg = 'Terima kasih! Pesan Anda sudah kami terima, kami akan segera merespons.';
                $contact_ok = true;
            } catch (Exception $e) {
                $contact_msg = 'Terjadi kesalahan, silakan coba lagi.';
            }
        }
    }
}

// Ambil data hero
$hero = db()->query('SELECT * FROM hero_content WHERE id=1')->fetch() ?: [];
$legal_badges = json_decode($hero['legal_badges'] ?? '[]', true) ?: [];

// Ambil tema website
$theme = db()->query('SELECT * FROM settings WHERE id=1')->fetch() ?: [];

// Siapkan background hero (prioritas: file upload lokal > URL)
$hero_bg = $hero['background_image'] ?? '';
if (!empty($hero['hero_bg_path'])) {
    $hero_bg = $hero['hero_bg_path']; // path lokal
}

// Siapkan foto section Tentang & handle Instagram (dari settings)
$about_image = $theme['about_image'] ?? '';
if ($about_image && strpos($about_image, 'http') !== 0 && strpos($about_image, '/') === 0) {
    $about_image = BASE_URL . $about_image;
} elseif ($about_image && strpos($about_image, 'http') !== 0) {
    $about_image = BASE_URL . '/' . $about_image;
}
$ig_handle = trim($theme['instagram_handle'] ?? '');
$ig_url = $ig_handle ? 'https://www.instagram.com/' . $ig_handle : '#';

// Kumpulkan gambar hero slideshow: scan folder uploads/hero/slide_*.webp
$hero_slides = [];
$slide_dir = __DIR__ . '/uploads/hero/';
if (is_dir($slide_dir)) {
    foreach (glob($slide_dir . 'slide_*.webp') as $f) {
        $hero_slides[] = BASE_URL . '/uploads/hero/' . basename($f);
    }
}
$hero_slides = array_values(array_unique($hero_slides));

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

// Ambil keunggulan / layanan
$features = db()->query('SELECT * FROM features WHERE is_active=1 ORDER BY sort_order ASC, id ASC')->fetchAll();

// Ambil galeri foto (maks 6 untuk portofolio perjalanan)
$gallery = db()->query('SELECT * FROM gallery_images WHERE is_active=1 ORDER BY sort_order ASC, id ASC LIMIT 6')->fetchAll();
$gallery_items = [];
foreach ($gallery as $g) {
    $gallery_items[] = [
        'img' => BASE_URL . '/' . $g['image_path'],
        'cap' => $g['caption'] ?? '',
    ];
}

// Ambil promo popup
$promo = db()->query('SELECT * FROM promos WHERE id=1')->fetch() ?: [];

// ===== SEO / META =====
$meta_title = htmlspecialchars($hero['title'] ?? 'PT Royal Haramain Internasional | Travel Umrah & Haji Khusus');
$meta_desc  = trim($hero['subtitle'] ?? '') ?: 'PT Royal Haramain Internasional — travel haji, umroh dan halal tours resmi PPIU & PIHK. Pendamping ibadah dari awal hingga pulang ke rumah.';
$meta_desc  = mb_substr($meta_desc, 0, 158);
$meta_logo  = BASE_URL . '/assets/images/logo.png';
$meta_url   = rtrim(BASE_URL, '/') . '/';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $meta_title ?></title>
  <meta name="description" content="<?= htmlspecialchars($meta_desc) ?>">
  <meta name="keywords" content="umroh, haji, umrah murah, travel umroh jogja, umroh bantul, royal haramain, haji khusus, halal tour, paket umroh 2026">
  <meta name="author" content="PT Royal Haramain Internasional">
  <meta name="robots" content="index, follow">
  <link rel="canonical" href="<?= htmlspecialchars($meta_url) ?>">
  <!-- Open Graph (share ke WA/FB/IG/Twitter) -->
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="PT Royal Haramain Internasional">
  <meta property="og:title" content="<?= $meta_title ?>">
  <meta property="og:description" content="<?= htmlspecialchars($meta_desc) ?>">
  <meta property="og:url" content="<?= htmlspecialchars($meta_url) ?>">
  <meta property="og:image" content="<?= htmlspecialchars($meta_logo) ?>">
  <meta property="og:locale" content="id_ID">
  <!-- Twitter Card -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= $meta_title ?>">
  <meta name="twitter:description" content="<?= htmlspecialchars($meta_desc) ?>">
  <meta name="twitter:image" content="<?= htmlspecialchars($meta_logo) ?>">
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="assets/images/logo.png">
  <link rel="apple-touch-icon" href="assets/images/logo.png">
  <?php if (!empty($hero_slides)): ?>
  <link rel="preload" as="image" href="<?= htmlspecialchars($hero_slides[0]) ?>" fetchpriority="high">
  <?php endif; ?>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="css/style_pub.css?v=8">
  <style>
    /* Tema dinamis dari database (Admin -> Hero -> Tema Website) */
    <?php if (count($hero_slides) > 1): ?>
    /* Slideshow aktif: sembunyikan hero-bg statis, pakai slide pertama sebagai fallback */
    .hero { background:rgba(2,36,18,.9)!important; }
    <?php else: ?>
    .hero { --hero-bg: url('<?= htmlspecialchars($hero_bg ? (strpos($hero_bg,'http')===0?$hero_bg:BASE_URL.'/'.$hero_bg) : 'https://images.unsplash.com/photo-1591604466107-ec97de577aff?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80') ?>'); }
    <?php endif; ?>
    :root {
      --emerald: <?= htmlspecialchars($theme['primary_color'] ?? '#046a38') ?>;
      --emerald-dark: <?= htmlspecialchars($theme['secondary_color'] ?? '#023d1f') ?>;
      --gold: <?= htmlspecialchars($theme['accent_color'] ?? '#d4af37') ?>;
    }
    /* Slideshow hero background */
    .hero { position:relative; overflow:hidden; }
    .hero-slide {
      position:absolute; inset:0; z-index:0; background-size:cover; background-position:center;
      opacity:0; will-change:opacity;
      animation:heroFade <?= count($hero_slides) * 5 ?>s linear infinite;
    }
    /* Overlay gelap agar teks di atas gambar selalu terbaca */
    .hero-overlay {
      position:absolute; inset:0; z-index:1;
      background:linear-gradient(180deg, rgba(1,23,13,.82) 0%, rgba(2,36,18,.66) 55%, rgba(1,23,13,.88) 100%);
    }
    .hero-grid { position:relative; z-index:2; }
    @keyframes heroFade {
      0%   { opacity:0; }
      4%   { opacity:1; }
      20%  { opacity:1; }
      24%  { opacity:0; }
      100% { opacity:0; }
    }
    <?php if (count($hero_slides) === 1): ?>
    .hero-slide { opacity:1; animation:none; }
    <?php endif; ?>
    /* gambar kanan dihapus: hero jadi satu kolom terpusat */
    .hero-content { text-align:center; max-width:900px; margin:0 auto; }
    .hero-content .lead { margin:0 auto; }
    .hero-actions { justify-content:center; }
    .stats { justify-content:center; }
    .hero-content .eyebrow { font-size:14px; }
  </style>
</head>
<body>

  <header class="site">
    <div class="header-inner">
      <a href="#" class="brand">
        <img src="assets/images/logo.png" alt="Logo Royal Haramain" width="46" height="46" decoding="async">
        <div><strong>Royal Haramain</strong></div>
      </a>
      <nav class="main-nav">
        <a href="#beranda">Beranda</a>
        <a href="#paket">Paket</a>
        <a href="#keunggulan">Keunggulan</a>
        <a href="#kontak">Kontak</a>
        <a href="https://wa.me/6281215151552" target="_blank" class="gold-button" style="padding:10px 20px;font-size:13px;">Hubungi Kami</a>
      </nav>
      <button class="mobile-menu" onclick="toggleNav()">☰</button>
    </div>
  </header>

  <main>
    <!-- ================= HERO (dari DB) ================= -->
    <section class="hero" id="beranda">
      <?php if (count($hero_slides) > 1): ?>
        <?php foreach ($hero_slides as $i => $slide): ?>
          <div class="hero-slide" style="background-image:url('<?= htmlspecialchars($slide) ?>');animation-delay:<?= $i * 5 ?>s"></div>
        <?php endforeach; ?>
      <?php endif; ?>
      <div class="hero-overlay"></div>
      <div class="container hero-grid">
        <div class="hero-content">
          <?php if (!empty($hero['subtitle'])): ?>
            <span class="eyebrow" style="color:#f3e5ab;background:rgba(212,175,55,.15)">✦ <?= htmlspecialchars($hero['subtitle']) ?></span>
          <?php endif; ?>
          <?php if (!empty($hero['title'])): ?>
          <h1><?= htmlspecialchars($hero['title']) ?></h1>
          <?php endif; ?>
          <?php if (!empty($hero['badge_line'])): ?>
            <p class="lead"><?= htmlspecialchars($hero['badge_line']) ?></p>
          <?php endif; ?>
          <?php if (!empty($hero['quote'])): ?>
            <div class="hero-quote">"<?= htmlspecialchars($hero['quote']) ?>"<cite>— <?= htmlspecialchars($hero['quote_source'] ?? '') ?></cite></div>
          <?php endif; ?>
          <div class="stats">
            <div><strong>17+</strong><span>Tahun Pengalaman</span></div>
            <div><strong>25.000+</strong><span>Jamaah Diberangkatkan</span></div>
            <div><strong>100%</strong><span>Legalitas Kemenag</span></div>
          </div>
          <div class="hero-actions">
            <a href="https://wa.me/6281215151552" target="_blank" class="gold-button" style="display:inline-block;text-align:center;">Hubungi Kami</a>
            <a class="gradient-button" href="#paket" style="display:inline-block;text-align:center;">Lihat Paket →</a>
          </div>
        </div>
      </div>
    </section>

    <?php if ($legal_badges): ?>
    <div class="badges-strip">
      <div class="container">
        <div class="badges-row">
          <?php foreach ($legal_badges as $b): ?><span class="badge-item"><?= htmlspecialchars($b) ?></span><?php endforeach; ?>
        </div>
        <div class="badges-cta">
          <a href="https://wa.me/6281215151552" target="_blank" class="gold-button" style="display:inline-block;text-align:center;">Hubungi Kami</a>
        </div>
      </div>
    </div>
    <?php endif; ?>

    <!-- ============ KEUNGGULAN / LAYANAN (dari DB) ============ -->
    <?php if ($features): ?>
    <section id="keunggulan">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Kenapa Royal Haramain</span>
          <h2>Didampingi dari Awal Hingga Pulang ke Rumah</h2>
          <p>Didampingi dari Awal Hingga Pulang ke Rumah</p>
        </div>
        <div class="features">
          <?php foreach ($features as $f): ?>
            <article class="card">
              <div class="feature-icon"><i class="fa-solid <?= htmlspecialchars($f['icon']) ?>"></i></div>
              <h3><?= htmlspecialchars($f['title']) ?></h3>
              <p><?= htmlspecialchars($f['description'] ?? '') ?></p>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ============ PAKET (dari DB) ============ -->
    <?php if ($packages): ?>
    <section id="paket">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Pilihan Paket Umrah</span>
          <h2>Pilihan Paket Umrah</h2>
          <p>Ringkasan seluruh program Royal Haramain — klik salah satu untuk melihat detail lengkap, fasilitas, dan jadwal.</p>
        </div>
        <div class="packages">
          <?php foreach ($packages as $pk): ?>
            <article class="card package<?= $pk['featured'] ? ' popular' : '' ?>" style="overflow:visible">
              <?php if ($pk['featured']): ?><div class="popular-label">★ <?= htmlspecialchars($pk['badge'] ?: 'Paling Diminati') ?></div><?php endif; ?>
              <div>
                <?php if ($pk['duration']): ?><span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)"><?= htmlspecialchars($pk['duration']) ?></span><?php endif; ?>
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
              <a href="https://wa.me/6281215151552?text=Assalamualaikum%2C%20saya%20ingin%20tanya%20paket%20<?= urlencode($pk['title']) ?>" target="_blank" class="gradient-button" style="display:block;text-align:center;">Lihat Detail</a>
            </article>
          <?php endforeach; ?>
        </div>
      </div>
    </section>
    <?php endif; ?>

    <!-- ============ TENTANG ============ -->
    <section id="tentang" style="background:var(--white)">
      <div class="container about-grid">
        <?php if ($about_image): ?>
          <img src="<?= htmlspecialchars($about_image) ?>" alt="Tentang PT Royal Haramain Internasional" loading="lazy" decoding="async" onerror="this.src='assets/images/logo.png'">
        <?php endif; ?>
        <div>
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Tentang Kami</span>
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
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Kabar Makkah & Madinah</span>
          <h2>Berita Terkini Tanah Suci</h2>
          <p>Informasi seputar kebijakan, fasilitas, dan persiapan ibadah jamaah.</p>
        </div>
        <div class="news">
          <?php foreach ($articles as $a): ?>
            <article>
              <img src="<?= htmlspecialchars($a['image_url'] ?? '') ?>" alt="<?= htmlspecialchars($a['title']) ?>" loading="lazy" decoding="async" onerror="this.style.display='none'">
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

    <!-- ============ GALERI / DOKUMENTASI ============ -->
    <section id="galeri" style="background:var(--white)">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Galeri Kegiatan</span>
          <h2>Dokumentasi Perjalanan</h2>
          <p>Momen kebersamaan jamaah di Tanah Suci dan layanan kami.</p>
        </div>

        <?php if ($gallery): ?>
          <div class="gallery-grid" id="galleryGrid">
            <?php foreach ($gallery as $i => $g): ?>
              <figure>
                <img src="<?= BASE_URL.'/'.htmlspecialchars($g['image_path']) ?>" alt="<?= htmlspecialchars($g['caption']) ?>" loading="lazy" decoding="async" onclick="openLB(<?= (int)$i ?>)" title="<?= htmlspecialchars($g['caption']) ?>">
                <?php if ($g['caption']): ?><figcaption><?= htmlspecialchars($g['caption']) ?></figcaption><?php endif; ?>
              </figure>
            <?php endforeach; ?>
          </div>
          <?php if ($ig_handle): ?>
            <div class="gallery-ig">
              <a href="<?= htmlspecialchars($ig_url) ?>" target="_blank" rel="noopener" class="ig-follow"><i class="fa-brands fa-instagram"></i> Ikuti @<?= htmlspecialchars($ig_handle) ?></a>
            </div>
          <?php endif; ?>
        <?php else: ?>
          <p class="empty">Belum ada foto. Upload dari panel admin → Galeri.</p>
        <?php endif; ?>
      </div>
    </section>

    <!-- ============ LIGHTBOX GALERI ============ -->
    <div class="lightbox" id="lightbox" hidden>
      <button class="lightbox-close" onclick="closeLB()" aria-label="Tutup">✕</button>
      <button class="lightbox-nav prev" onclick="lbNav(-1)" aria-label="Sebelumnya">‹</button>
      <div class="lightbox-inner">
        <img id="lbImg" src="" alt="">
        <div class="lightbox-cap" id="lbCap"></div>
      </div>
      <button class="lightbox-nav next" onclick="lbNav(1)" aria-label="Berikutnya">›</button>
    </div>

    <!-- ============ KONTAK / FORM PESAN ============ -->
    <section id="kontak">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">Hubungi Kami</span>
          <h2>Siap Membantu Perjalanan Ibadah Anda</h2>
          <p>Konsultasikan pilihan paket Umrah bersama tim Royal Haramain. Kami akan membantu memilih program yang paling sesuai.</p>
        </div>
        <div class="contact-wrap">
          <div class="contact-info">
            <h3>Kontak Langsung</h3>
            <div class="ci"><i class="fa-brands fa-whatsapp"></i><div><strong>WhatsApp Marketing</strong><span><a href="https://wa.me/6281215151552" target="_blank" style="color:var(--emerald);font-weight:700;">0812 1515 1552</a></span></div></div>
            <div class="ci"><i class="fa-solid fa-phone"></i><div><strong>Telepon</strong><span><a href="tel:081215151552" style="color:var(--emerald);">0812 1515 1552</a></span></div></div>
            <div class="ci"><i class="fa-solid fa-location-dot"></i><div><strong>Kantor Bantul</strong><span>Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY</span></div></div>
            <p class="hint" style="margin-top:18px;font-size:13px;color:var(--muted)">Klik tombol di bawah untuk konsultasi gratis via WhatsApp.</p>
            <a href="https://wa.me/6281215151552" target="_blank" class="gradient-button" style="display:inline-block;text-align:center;">💬 Chat WhatsApp Sekarang</a>
          </div>
          <div class="contact-form">
            <?php if ($contact_msg): ?>
              <div class="form-msg <?= $contact_ok ? 'ok' : 'err' ?>" style="margin-bottom:16px"><?= htmlspecialchars($contact_msg) ?></div>
            <?php endif; ?>
            <form method="post" action="#kontak">
              <input type="hidden" name="kontak" value="1">
              <?= csrf_field() ?>
              <div class="grid2">
                <div class="fm"><label>Nama Lengkap *</label><input type="text" name="cname" required></div>
                <div class="fm"><label>Email</label><input type="email" name="cemail"></div>
                <div class="fm"><label>Nomor WA / Telepon</label><input type="text" name="cphone" placeholder="08xxxxxxxxxx"></div>
                <div class="fm"><label>Subjek</label><input type="text" name="csubject"></div>
              </div>
              <div class="fm"><label>Pesan *</label><textarea name="cmessage" rows="5" required></textarea></div>
              <button type="submit" class="gold-button">Kirim Pesan</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ LOKASI ============ -->
    <section id="lokasi" style="background:#fff">
      <div class="container">
        <div class="section-heading">
          <span class="eyebrow" style="background:rgba(4,106,56,.08);color:var(--emerald-dark)">4 Kantor Siap Melayani</span>
          <h2>4 Kantor Siap Melayani Anda</h2>
          <p>Royal Haramain hadir di beberapa titik layanan di Yogyakarta dan Jawa Tengah untuk memudahkan konsultasi dan pendaftaran jamaah.</p>
        </div>
        <div class="offices">
          <div class="office"><h3>Yogyakarta</h3><p>Jl. Magelang No. 12, Dukuh, Tridadi, Kec. Sleman, Kabupaten Sleman, DIY</p><a href="https://wa.me/6281273099920" target="_blank">0812 7309 9920</a></div>
          <div class="office"><h3>Bantul</h3><p>Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY</p><a href="https://wa.me/6281215151552" target="_blank">0812 1515 1552</a></div>
          <div class="office"><h3>Temanggung</h3><p>Rumah Tahfidz Zabisa Putri, Jl. Ringroad Utara Krikil, Walitelon Selatan, Temanggung, Jawa Tengah</p><a href="https://wa.me/6287834342424" target="_blank">0878 3434 2424</a></div>
          <div class="office"><h3>Magelang</h3><p>Perum Depkes Blok B2 No. 26, RT.02/RW.06, Kramat Utara, Kec. Magelang Utara, Kota Magelang, Jawa Tengah</p><a href="https://wa.me/628112650165" target="_blank">0811 2650 165</a></div>
        </div>
      </div>
    </section>

    <!-- ============ DAFTAR (melalui popup) ============ -->
    <!-- ============ POPUP BOOKING ============ -->
    <div class="modal-overlay" id="bookModal">
      <div class="modal-card modal-card-lg">
        <button class="modal-close" onclick="closeBooking()">✕</button>
        <div class="modal-body">
          <span class="eyebrow" style="background:rgba(212,175,55,.2);color:#8a6d1f">Daftar Sekarang</span>
          <h3>Daftar & Konsultasi Gratis</h3>
          <?php if ($form_msg): ?>
            <div class="form-msg <?= $form_ok ? 'ok' : 'err' ?>"><?= htmlspecialchars($form_msg) ?></div>
          <?php endif; ?>
          <form method="post" action="#daftar">
            <input type="hidden" name="daftar" value="1">
            <?= csrf_field() ?>
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
    </div>
  </main>

  <footer class="site">
    <div class="container footer-grid">
      <div>
        <div class="brand-text" style="font-family:'Cinzel',serif;font-size:18px;color:#fff">Royal Haramain</div>
        <p style="font-size:13px;margin-bottom:14px">PT. Royal Haramain International — PPIU & PIHK Resmi.</p>
        <p style="font-size:13px">Kantor Bantul: Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY</p>
        <p style="font-size:13px;margin-top:6px">
          <a href="https://instagram.com/royalumrah.jogja" target="_blank" style="color:var(--gold)"><i class="fa-brands fa-instagram"></i> @royalumrah.jogja</a><br>
          <a href="https://www.tiktok.com/@royalumrah.jogja" target="_blank" style="color:var(--gold)"><i class="fa-brands fa-tiktok"></i> @royalumrah.jogja</a><br>
          <a href="https://www.facebook.com/RoyalHaramainInternasional" target="_blank" style="color:var(--gold)"><i class="fa-brands fa-facebook"></i> Royal Haramain Internasional</a>
        </p>
      </div>
      <div>
        <h4>Navigasi</h4>
        <a href="#beranda">Beranda</a><a href="#paket">Paket</a><a href="#galeri">Galeri</a><a href="#kontak">Kontak</a><a href="https://wa.me/6281215151552" target="_blank">Hubungi Kami</a>
      </div>
      <div>
        <h4>Layanan</h4>
        <a href="#paket">Umrah Hemat 2026</a><a href="#paket">Umrah Premium 2026</a><a href="#paket">Program Umrah 10 Hari</a><a href="#paket">Haji Khusus 2027</a>
      </div>
      <div>
        <h4>Kontak</h4>
        <a href="https://wa.me/6281215151552" target="_blank">WhatsApp: 0812 1515 1552</a>
        <a href="tel:081215151552">Telepon: 0812 1515 1552</a>
        <a href="admin/login.php">Login Admin</a>
      </div>
    </div>
    <div class="footer-bottom"><div class="container">© <?= date('Y') ?> PT. Royal Haramain International. Seluruh hak cipta dilindungi. | Jl. Magelang KM. 12, Tridadi, Sleman, Yogyakarta</div></div>
  </footer>

  <?php if (!empty($promo['enabled'])): ?>
  <div class="modal-overlay" id="promoModal">
    <div class="modal-card">
      <button class="modal-close" onclick="closePromo()">✕</button>
      <?php if ($promo['image_url']): ?><img src="<?= htmlspecialchars($promo['image_url']) ?>" alt="Promo" loading="lazy" decoding="async"><?php endif; ?>
      <div class="modal-body">
        <?php if ($promo['badge']): ?><span class="eyebrow" style="background:rgba(212,175,55,.2);color:#8a6d1f"><?= htmlspecialchars($promo['badge']) ?></span><?php endif; ?>
        <h3 style="margin-top:8px"><?= htmlspecialchars($promo['title']) ?></h3>
        <p><?= htmlspecialchars($promo['message']) ?></p>
        <button class="gold-button" onclick="goPromo('<?= htmlspecialchars($promo['link'] ?? '') ?>')">Lihat Penawaran</button>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <script>
    function openBooking(pkg) {
      var m = document.getElementById('bookModal');
      if (m) {
        m.classList.add('show');
        var sel = document.querySelector('#bookModal select[name=package]');
        if (sel && pkg) { for (var i=0;i<sel.options.length;i++){ if(sel.options[i].text===pkg){ sel.selectedIndex=i; break; } } }
      }
    }
    function closeBooking(){ var m=document.getElementById('bookModal'); if (m) m.classList.remove('show'); }
    function toggleNav() { var n=document.querySelector('nav.main-nav'); if(n) n.classList.toggle('open'); }
    function closePromo(){ document.getElementById('promoModal').classList.remove('show'); }
    function goPromo(url){ closePromo(); if(url && url!=='#' && url!=='#daftar' && url!=='') window.location.href=url; else openBooking(); }
    /* Lightbox galeri */
    var LB_ITEMS = <?= json_encode($gallery_items, JSON_UNESCAPED_UNICODE) ?>;
    var lbIdx = 0;
    function openLB(i){ if(!LB_ITEMS.length) return; lbIdx=(i+LB_ITEMS.length)%LB_ITEMS.length; updLB(); document.getElementById('lightbox').hidden=false; document.body.style.overflow='hidden'; }
    function closeLB(){ document.getElementById('lightbox').hidden=true; document.body.style.overflow=''; }
    function updLB(){ document.getElementById('lbImg').src=LB_ITEMS[lbIdx].img; document.getElementById('lbCap').textContent=LB_ITEMS[lbIdx].cap||''; }
    function lbNav(d){ openLB(lbIdx+d); }
    document.addEventListener('keydown', function(ev){
      var lb=document.getElementById('lightbox'); if (lb && !lb.hidden){ if(ev.key==='Escape')closeLB(); if(ev.key==='ArrowLeft')lbNav(-1); if(ev.key==='ArrowRight')lbNav(1); }
    });
    document.addEventListener('click', function(ev){
      if (ev.target && ev.target.id==='lightbox' && ev.target.classList.contains('lightbox')) closeLB();
    });
    <?php if (!empty($promo['enabled'])): ?>
    setTimeout(function(){ var m=document.getElementById('promoModal'); if(m) m.classList.add('show'); }, (parseInt('<?= (int)($promo['delay'] ?? 3) ?>')||3)*1000);
    <?php endif; ?>
    document.addEventListener('click', function(ev){
      if (ev.target.classList && ev.target.classList.contains('modal-overlay') && ev.target.id==='bookModal') closeBooking();
      if (ev.target.classList && ev.target.classList.contains('modal-overlay') && ev.target.id==='promoModal') closePromo();
    });
    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['daftar'])): ?>
    openBooking();
    <?php endif; ?>
  </script>
</body>
</html>
