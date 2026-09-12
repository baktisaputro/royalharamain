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
        'name'     => 'Umrah Hemat 2026',
        'price'    => 'Rp 29,9 Jt',
        'price_old'=> '',
        'duration' => '9 Hari',
        'badge'    => '',
        'facilities' => ['Tiket Pesawat PP Internasional', 'Hotel Madinah Nusk El Eiman (3 malam)', 'Hotel Makkah Snood Ajyad (4 malam)', 'Kereta Cepat Haramain', 'Taifsama Cable Car & Al Baik', 'Bimbingan Manasik & Tour Leader', 'Transportasi bus full trip & city tour', 'Makan full board hotel & snack', 'Visa Umrah & asuransi perjalanan', 'Dokumentasi foto & video', 'Air Zam-zam 5L & suvenir'],
    ],
    [
        'name'     => 'Umrah Premium 2026',
        'price'    => 'Rp 39,9 Jt',
        'price_old'=> '',
        'duration' => '12 Hari',
        'badge'    => 'PALING DIMINATI',
        'facilities' => ['Direct Flight Garuda Indonesia', 'Hotel Madinah Nusk El Eiman (3 malam)', 'Hotel Makkah Snood Ajyad (4 malam)', 'Kereta Cepat Haramain', 'Taifsama Cable Car & Al Baik', 'Bimbingan Manasik & Tour Leader', 'Transportasi bus full trip & city tour', 'Makan full board hotel & snack', 'Visa Umrah & asuransi perjalanan', 'Dokumentasi foto & video', 'Air Zam-zam 5L & suvenir'],
    ],
    [
        'name'     => 'Program Umrah 10 Hari',
        'price'    => 'Rp 33,9 Jt',
        'price_old'=> '',
        'duration' => '10 Hari',
        'badge'    => '',
        'facilities' => ['Tiket pesawat YIA – CGK PP', 'Tiket Saudi Airlines PP', 'Visa Umrah', 'Hotel Madinah Grand Plaza ±150m dari Masjid Nabawi', 'Hotel Makkah Maysan Al Maqam ±350m dari Masjidil Haram', 'Mutowif (pembimbing ibadah)', 'City tour Madinah & Makkah', 'Manasik Umrah', 'Perlengkapan Umrah'],
    ],
    [
        'name'     => 'Haji Khusus 2027',
        'price'    => 'USD 13.400',
        'price_old'=> '',
        'duration' => '23 Hari',
        'badge'    => '',
        'facilities' => ['Kuota Murni 2027', 'Oman Air PP Jakarta – Arab Saudi', 'Konsorsium Saudi Journey', 'Maktab VIP Majr Kabs Zona 1–2', 'Meals 3x sehari', 'Tour leader & pembimbing ibadah', 'Bus kontrak', 'Pelepasan haji H-1', 'Asuransi perjalanan', 'Mutawif (guide)'],
    ],
];

// ===== KONTAK (edit di sini) =====
$whatsapp = '6281215151552';   // format internasional, tanpa +
$phone    = '0812 1515 1552';
$email    = 'info@royalharamain.id';
$address  = 'Kantor Bantul: Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY';
$wa_link  = 'https://wa.me/' . $whatsapp;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Haramain Tour & Travel Yogyakarta — Umrah Resmi Berizin PPIU</title>
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
            --cream: #ffffff;
            --text: #1a1a1a;
            --muted: #6b7280;
            --white: #ffffff;
            --border: #e5e7eb;
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

        /* ===== HEADER — putih ===== */
        header {
            background: #fff;
            padding: 18px 0;
            text-align: center;
            border-bottom: 1px solid var(--border);
        }
        .brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 14px;
            color: var(--emerald-dark);
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
            color: var(--emerald-dark);
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

        /* ===== CONTACT — putih ===== */
        .contact {
            background: #fff;
            color: var(--text);
            padding: 60px 22px;
            border-top: 1px solid var(--border);
        }
        .contact .section-title { color: var(--emerald-dark); }
        .contact .section-sub { color: var(--muted); }
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
            background: #f9fafb;
            border-radius: 14px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }
        .contact-item:hover {
            background: #f3f4f6;
            transform: translateY(-2px);
        }
        .contact-item i {
            font-size: 28px;
            color: var(--emerald);
            margin-bottom: 12px;
            display: block;
        }
        .contact-item h4 {
            font-size: 13px;
            color: var(--emerald-dark);
            margin-bottom: 6px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .contact-item p {
            font-size: 14px;
            color: var(--muted);
        }
        .contact-item a {
            color: var(--emerald);
            font-weight: 600;
        }
        .contact-item a:hover { text-decoration: underline; }

        /* ===== FOOTER — gelap ===== */
        footer {
            background: var(--emerald-dark);
            color: rgba(255,255,255,0.6);
            text-align: center;
            padding: 24px 20px;
            font-size: 12px;
        }
        footer a { color: var(--gold-light); }

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
        </div>
    </div>
</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="eyebrow">Travel Umrah Ramah Lansia</div>
        <h2>Perjalanan Suci yang <span class="gold">Tenang, Terarah,</span> dan Penuh Kehormatan</h2>
        <p>Royal Haramain Tour & Travel melayani jamaah Umrah dengan bimbingan ibadah yang intensif, hotel berkelas dekat Tanah Suci, dan tim yang mendampingi dari pendaftaran hingga pulang ke rumah.</p>
        <a href="<?= $wa_link ?>" target="_blank" class="wa-btn">
            <i class="fa-brands fa-whatsapp"></i> Hubungi Kami
        </a>
    </div>
</section>

<!-- LEGAL BADGES -->
<section style="background:#fff;padding:20px 22px;border-bottom:1px solid var(--border);">
    <div class="container" style="display:flex;justify-content:center;gap:40px;flex-wrap:wrap;text-align:center;">
        <div>
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Izin Umrah (PPIU)</div>
            <div style="font-weight:700;color:var(--emerald-dark);font-size:14px;">21092200513570003</div>
        </div>
        <div>
            <div style="font-size:12px;color:var(--muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:4px;">Izin Haji (PIHK)</div>
            <div style="font-weight:700;color:var(--emerald-dark);font-size:14px;">694/2020 by. Hajar Aswad</div>
        </div>
    </div>
</section>

<!-- PACKAGES -->
<section class="packages">
    <div class="container">
        <h2 class="section-title">Pilihan Paket Umrah</h2>
        <p class="section-sub">Ringkasan seluruh program Royal Haramain — klik salah satu untuk melihat detail lengkap, fasilitas, dan jadwal.</p>
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
                        <i class="fa-brands fa-whatsapp"></i> Lihat Detail
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
                <h4>WhatsApp Marketing</h4>
                <p><a href="https://wa.me/6281215151552" target="_blank">0812 1515 1552</a></p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-phone"></i>
                <h4>Telepon</h4>
                <p><a href="tel:081215151552">0812 1515 1552</a></p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-location-dot"></i>
                <h4>Kantor Bantul</h4>
                <p>Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY</p>
            </div>
            <div class="contact-item">
                <i class="fa-solid fa-building"></i>
                <h4>Kantor Pusat</h4>
                <p>Jl. Magelang KM. 12, Tridadi, Sleman, Yogyakarta</p>
            </div>
        </div>
    </div>
</section>

<!-- 4 KANTOR -->
<section style="background:#f9fafb;padding:60px 22px;border-top:1px solid var(--border);">
    <div class="container">
        <h2 class="section-title">4 Kantor Siap Melayani Anda</h2>
        <p class="section-sub">Royal Haramain hadir di beberapa titik layanan di Yogyakarta dan Jawa Tengah</p>
        <div class="contact-grid" style="max-width:1100px;">
            <div class="contact-item" style="background:#fff;border-color:var(--border);">
                <i class="fa-solid fa-building" style="color:var(--emerald);"></i>
                <h4 style="color:var(--emerald-dark);">Yogyakarta</h4>
                <p style="color:var(--text);">Jl. Magelang No. 12, Dukuh, Tridadi, Kec. Sleman, Kabupaten Sleman, DIY</p>
                <p><a href="https://wa.me/6281273099920" target="_blank" style="color:var(--emerald);">0812 7309 9920</a></p>
            </div>
            <div class="contact-item" style="background:#fff;border-color:var(--border);">
                <i class="fa-solid fa-building" style="color:var(--emerald);"></i>
                <h4 style="color:var(--emerald-dark);">Bantul</h4>
                <p style="color:var(--text);">Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY</p>
                <p><a href="https://wa.me/6281215151552" target="_blank" style="color:var(--emerald);">0812 1515 1552</a></p>
            </div>
            <div class="contact-item" style="background:#fff;border-color:var(--border);">
                <i class="fa-solid fa-building" style="color:var(--emerald);"></i>
                <h4 style="color:var(--emerald-dark);">Temanggung</h4>
                <p style="color:var(--text);">Rumah Tahfidz Zabisa Putri, Jl. Ringroad Utara Krikil, Walitelon Selatan, Temanggung, Jawa Tengah</p>
                <p><a href="https://wa.me/6287834342424" target="_blank" style="color:var(--emerald);">0878 3434 2424</a></p>
            </div>
            <div class="contact-item" style="background:#fff;border-color:var(--border);">
                <i class="fa-solid fa-building" style="color:var(--emerald);"></i>
                <h4 style="color:var(--emerald-dark);">Magelang</h4>
                <p style="color:var(--text);">Perum Depkes Blok B2 No. 26, RT.02/RW.06, Kramat Utara, Kec. Magelang Utara, Kota Magelang, Jawa Tengah</p>
                <p><a href="https://wa.me/628112650165" target="_blank" style="color:var(--emerald);">0811 2650 165</a></p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div style="margin-bottom:8px;">
        <a href="https://instagram.com/royalumrah.jogja" target="_blank" style="margin:0 8px;"><i class="fa-brands fa-instagram"></i> @royalumrah.jogja</a>
        <a href="https://www.tiktok.com/@royalumrah.jogja" target="_blank" style="margin:0 8px;"><i class="fa-brands fa-tiktok"></i> @royalumrah.jogja</a>
        <a href="https://www.facebook.com/RoyalHaramainInternasional" target="_blank" style="margin:0 8px;"><i class="fa-brands fa-facebook"></i> Royal Haramain Internasional</a>
    </div>
    &copy; <?= date('Y') ?> PT. Royal Haramain International. Seluruh hak cipta dilindungi.
    <br>Kantor Bantul: Jl. Srandakan Km. 4, RW. 6, Ngabean, Triharjo, Kec. Pandak, Kab. Bantul, DIY
</footer>

</body>
</html>
