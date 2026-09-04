<?php
/**
 * Royal Haramain - Halaman Coming Soon
 * ============================================================
 * EDIT PAKET: Ubah array $packages di bawah ini sesuai kebutuhan.
 * Edit Kontak: Ganti nilai variabel $whatsapp, $phone, $email, $address.
 * ============================================================
 */

// ===== PAKET UMROH (edit di sini) =====
$packages = [
    [
        'name'     => 'Umrah Hemat',
        'price'    => 'Rp 29.9 Juta',
        'duration' => '9 Hari',
        'badge'    => '',
        'facilities' => ['Hotel Bintang 4', 'Tiket Pesawat Internasional/Domestik', 'Muthawwif Berpengalaman', 'Visa & Perlengkapan'],
    ],
    [
        'name'     => 'Umrah Premium 2026',
        'price'    => 'Rp 39.9 Juta',
        'duration' => '12 Hari',
        'badge'    => 'PALING DIMINATI',
        'facilities' => ['Hotel Bintang 5', 'Penerbangan Langsung dengan Garuda', 'Kereta Cepat Haramain', 'Eksklusif Lounge'],
    ],
    [
        'name'     => 'Haji Khusus',
        'price'    => 'Rp 65 Juta',
        'duration' => '25 Hari',
        'badge'    => '',
        'facilities' => ['Hotel Bintang 5', 'Penerbangan Direct', 'Bimbingan Haji Lengkap', 'Visa Haji Khusus'],
    ],
];

// ===== KONTAK (edit di sini) =====
$whatsapp = '6281234567890';   // format internasional, tanpa +
$phone    = '0812-3456-7890';
$email    = 'info@royalharamain.com';
$address  = 'Yogyakarta, Indonesia';
$wa_link  = 'https://wa.me/' . $whatsapp;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Haramain - Segera Hadir</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --emerald: #046a38;
            --emerald-dark: #023d1f;
            --emerald-light: #089752;
            --gold: #d4af37;
            --gold-light: #f3e5ab;
            --cream: #f8fbf9;
            --text: #112217;
            --muted: #64756b;
            --white: #ffffff;
            --border: #d5e3d8;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--cream);
            color: var(--text);
            font-family: 'Plus Jakarta Sans', sans-serif;
            line-height: 1.6;
        }
        h1, h2, h3, h4 { font-family: 'Cinzel', serif; line-height: 1.25; }
        a { color: inherit; text-decoration: none; }
        .container { max-width: 1100px; margin: 0 auto; padding: 0 22px; }

        /* ===== HEADER ===== */
        header {
            background: var(--emerald-dark);
            padding: 18px 0;
            text-align: center;
            border-bottom: 3px solid var(--gold);
        }
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            color: #fff;
        }
        .brand-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 8px;
        }
        .brand h1 {
            font-size: 22px;
            letter-spacing: 1px;
        }
        .brand span {
            display: block;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px;
            color: var(--gold-light);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 2px;
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald) 50%, var(--emerald-light) 100%);
            color: #fff;
            text-align: center;
            padding: 80px 22px;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: radial-gradient(circle at 30% 70%, rgba(212,175,55,0.1) 0%, transparent 50%);
            animation: pulse 8s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 0.5; }
            50% { transform: scale(1.1); opacity: 1; }
        }
        .hero-content { position: relative; z-index: 1; }
        .hero .eyebrow {
            display: inline-block;
            background: rgba(212,175,55,0.2);
            color: var(--gold-light);
            padding: 6px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
            border: 1px solid rgba(212,175,55,0.3);
        }
        .hero h2 {
            font-size: clamp(32px, 6vw, 56px);
            margin-bottom: 16px;
            text-shadow: 0 2px 20px rgba(0,0,0,0.2);
        }
        .hero h2 .gold { color: var(--gold); }
        .hero p {
            font-size: 18px;
            color: rgba(255,255,255,0.85);
            max-width: 600px;
            margin: 0 auto 30px;
        }
        .hero .wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: var(--gold);
            color: var(--emerald-dark);
            padding: 14px 32px;
            border-radius: 50px;
            font-weight: 700;
            font-size: 16px;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(212,175,55,0.4);
        }
        .hero .wa-btn:hover {
            background: var(--gold-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 30px rgba(212,175,55,0.5);
        }

        /* ===== PACKAGES ===== */
        .packages {
            padding: 70px 22px;
        }
        .section-title {
            text-align: center;
            font-size: clamp(24px, 4vw, 36px);
            color: var(--emerald-dark);
            margin-bottom: 12px;
        }
        .section-sub {
            text-align: center;
            color: var(--muted);
            font-size: 15px;
            margin-bottom: 40px;
        }
        .pkg-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        .pkg-card {
            background: var(--white);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
            transition: all 0.3s;
            position: relative;
        }
        .pkg-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 40px rgba(4,106,56,0.12);
        }
        .pkg-card.featured {
            border: 2px solid var(--gold);
            box-shadow: 0 4px 20px rgba(212,175,55,0.15);
        }
        .pkg-badge {
            position: absolute;
            top: 8px; right: 16px;
            background: var(--gold);
            color: var(--emerald-dark);
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .pkg-header {
            background: linear-gradient(135deg, var(--emerald-dark), var(--emerald));
            padding: 40px 24px 28px;
            color: #fff;
        }
        .pkg-header h3 {
            font-size: 20px;
            margin-bottom: 6px;
        }
        .pkg-header .duration {
            font-size: 13px;
            color: rgba(255,255,255,0.7);
        }
        .pkg-body {
            padding: 24px;
        }
        .pkg-price {
            font-size: 28px;
            font-weight: 700;
            color: var(--emerald);
            margin-bottom: 16px;
        }
        .pkg-price small {
            font-size: 14px;
            color: var(--muted);
            font-weight: 400;
        }
        .pkg-facilities {
            list-style: none;
            margin-bottom: 20px;
        }
        .pkg-facilities li {
            padding: 8px 0;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .pkg-facilities li:last-child { border-bottom: none; }
        .pkg-facilities li i {
            color: var(--emerald);
            font-size: 12px;
        }
        .pkg-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--emerald);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s;
        }
        .pkg-btn:hover {
            background: var(--emerald-dark);
        }
        .pkg-card.featured .pkg-btn {
            background: var(--gold);
            color: var(--emerald-dark);
        }
        .pkg-card.featured .pkg-btn:hover {
            background: var(--gold-light);
        }

        /* ===== CONTACT ===== */
        .contact {
            background: var(--emerald-dark);
            color: #fff;
            padding: 60px 22px;
        }
        .contact .section-title { color: #fff; }
        .contact .section-sub { color: rgba(255,255,255,0.7); }
        .contact-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 24px;
            max-width: 900px;
            margin: 0 auto;
        }
        .contact-item {
            text-align: center;
            padding: 24px 16px;
            background: rgba(255,255,255,0.06);
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.08);
            transition: all 0.3s;
        }
        .contact-item:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-2px);
        }
        .contact-item i {
            font-size: 28px;
            color: var(--gold);
            margin-bottom: 12px;
            display: block;
        }
        .contact-item h4 {
            font-size: 13px;
            color: var(--gold-light);
            margin-bottom: 6px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .contact-item p {
            font-size: 14px;
            color: rgba(255,255,255,0.85);
        }
        .contact-item a {
            color: var(--gold);
            font-weight: 600;
        }
        .contact-item a:hover { text-decoration: underline; }

        /* ===== FOOTER ===== */
        footer {
            background: #01170d;
            color: rgba(255,255,255,0.4);
            text-align: center;
            padding: 20px;
            font-size: 12px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 640px) {
            .hero { padding: 50px 16px; }
            .hero h2 { font-size: 28px; }
            .hero p { font-size: 15px; }
            .hero .wa-btn { padding: 12px 24px; font-size: 14px; }
            .pkg-grid { grid-template-columns: 1fr; }
            .contact-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<!-- HEADER -->
<header>
    <div class="brand">
        <img src="assets/images/logo.png" alt="Logo Royal Haramain" class="brand-logo">
        <div>
            <h1>Royal Haramain</h1>
            <span>Travel Haji & Umroh</span>
        </div>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="eyebrow">Coming Soon</div>
        <h2>Segera <span class="gold">Hadir</span> untuk Anda</h2>
        <p>Perjalanan ibadah haji dan umroh yang nyaman, aman, dan sesuai tuntunan. Daftarkan diri Anda sekarang!</p>
        <a href="<?= $wa_link ?>" target="_blank" class="wa-btn">
            <i class="fa-brands fa-whatsapp"></i> Hubungi Kami
        </a>
    </div>
</section>

<!-- PACKAGES -->
<section class="packages">
    <div class="container">
        <h2 class="section-title">Pilihan Paket</h2>
        <p class="section-sub">Pilih paket ibadah sesuai kebutuhan Anda</p>
        <div class="pkg-grid">
            <?php foreach ($packages as $i => $pkg): ?>
            <div class="pkg-card<?= !empty($pkg['badge']) ? ' featured' : '' ?>">
                <?php if (!empty($pkg['badge'])): ?>
                <div class="pkg-badge"><?= htmlspecialchars($pkg['badge']) ?></div>
                <?php endif; ?>
                <div class="pkg-header">
                    <h3><?= htmlspecialchars($pkg['name']) ?></h3>
                    <div class="duration"><i class="fa-regular fa-clock"></i> <?= htmlspecialchars($pkg['duration']) ?></div>
                </div>
                <div class="pkg-body">
                    <div class="pkg-price">
                        <?= htmlspecialchars($pkg['price']) ?>
                        <small>/ orang</small>
                    </div>
                    <?php if (!empty($pkg['facilities'])): ?>
                    <ul class="pkg-facilities">
                        <?php foreach ($pkg['facilities'] as $fac): ?>
                        <li><i class="fa-solid fa-check"></i> <?= htmlspecialchars($fac) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <a href="<?= $wa_link ?>" target="_blank" class="pkg-btn">
                        <i class="fa-brands fa-whatsapp"></i> Daftar Sekarang
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CONTACT -->
<section class="contact">
    <div class="container">
        <h2 class="section-title">Hubungi Kami</h2>
        <p class="section-sub">Konsultasi gratis sebelum daftar</p>
        <div class="contact-grid">
            <div class="contact-item">
                <i class="fa-brands fa-whatsapp"></i>
                <h4>WhatsApp</h4>
                <p><a href="<?= $wa_link ?>" target="_blank"><?= htmlspecialchars($phone) ?></a></p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <h4>Telepon</h4>
                <p><a href="tel:<?= preg_replace('/\D/','',$phone) ?>"><?= htmlspecialchars($phone) ?></a></p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-envelope"></i>
                <h4>Email</h4>
                <p><a href="mailto:<?= $email ?>"><?= htmlspecialchars($email) ?></a></p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <h4>Alamat</h4>
                <p><?= htmlspecialchars($address) ?></p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    &copy; <?= date('Y') ?> Royal Haramain. All rights reserved.
</footer>

</body>
</html>
