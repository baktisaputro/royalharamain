-- Update paket sesuai royalharamain.id
-- Jalankan di phpMyAdmin → tab SQL → paste semua → Go

USE pesm4254_royalharamain;

DELETE FROM package_facilities;
DELETE FROM packages;
ALTER TABLE packages AUTO_INCREMENT = 1;

INSERT INTO packages (title, price, price_old, duration, badge, featured, image_url, url, sort_order, is_active) VALUES
 ('Umrah Hemat 2026', 'Rp 29,9 Jt', NULL, '9 Hari', NULL, 0,
  'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=800&auto=format&fit=crop', '#', 1, 1),
 ('Umrah Premium 2026', 'Rp 39,9 Jt', NULL, '12 Hari', 'PALING DIMINATI', 1,
  'https://images.unsplash.com/photo-1584551246679-0daf3d275d0f?w=800&auto=format&fit=crop', '#', 2, 1),
 ('Program Umrah 10 Hari', 'Rp 33,9 Jt', NULL, '10 Hari', NULL, 0,
  'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=800&auto=format&fit=crop', '#', 3, 1),
 ('Haji Khusus 2027', 'USD 13.400', NULL, '23 Hari', NULL, 0,
  'https://images.unsplash.com/photo-1591604466107-ec97de577aff?w=800&auto=format&fit=crop', '#', 4, 1);

INSERT INTO package_facilities (package_id, facility, sort_order) VALUES
 (1, 'Tiket Pesawat PP Internasional', 1),
 (1, 'Hotel Madinah Nusk El Eiman (3 malam)', 2),
 (1, 'Hotel Makkah Snood Ajyad (4 malam)', 3),
 (1, 'Kereta Cepat Haramain', 4),
 (1, 'Taifsama Cable Car & Al Baik', 5),
 (1, 'Bimbingan Manasik & Tour Leader', 6),
 (1, 'Transportasi bus full trip & city tour', 7),
 (1, 'Makan full board hotel & snack', 8),
 (1, 'Visa Umrah & asuransi perjalanan', 9),
 (1, 'Dokumentasi foto & video', 10),
 (1, 'Air Zam-zam 5L & suvenir', 11),
 (2, 'Direct Flight Garuda Indonesia', 1),
 (2, 'Hotel Madinah Nusk El Eiman (3 malam)', 2),
 (2, 'Hotel Makkah Snood Ajyad (4 malam)', 3),
 (2, 'Kereta Cepat Haramain', 4),
 (2, 'Taifsama Cable Car & Al Baik', 5),
 (2, 'Bimbingan Manasik & Tour Leader', 6),
 (2, 'Transportasi bus full trip & city tour', 7),
 (2, 'Makan full board hotel & snack', 8),
 (2, 'Visa Umrah & asuransi perjalanan', 9),
 (2, 'Dokumentasi foto & video', 10),
 (2, 'Air Zam-zam 5L & suvenir', 11),
 (3, 'Tiket pesawat YIA – CGK PP', 1),
 (3, 'Tiket Saudi Airlines PP', 2),
 (3, 'Visa Umrah', 3),
 (3, 'Hotel Madinah Grand Plaza ±150m dari Masjid Nabawi', 4),
 (3, 'Hotel Makkah Maysan Al Maqam ±350m dari Masjidil Haram', 5),
 (3, 'Mutowif (pembimbing ibadah)', 6),
 (3, 'City tour Madinah & Makkah', 7),
 (3, 'Manasik Umrah', 8),
 (3, 'Perlengkapan Umrah', 9),
 (4, 'Kuota Murni 2027', 1),
 (4, 'Oman Air PP Jakarta – Arab Saudi', 2),
 (4, 'Konsorsium Saudi Journey', 3),
 (4, 'Maktab VIP Majr Kabs Zona 1–2', 4),
 (4, 'Meals 3x sehari', 5),
 (4, 'Tour leader & pembimbing ibadah', 6),
 (4, 'Bus kontrak', 7),
 (4, 'Pelepasan haji H-1', 8),
 (4, 'Asuransi perjalanan', 9),
 (4, 'Mutawif (guide)', 10);

UPDATE hero_content SET secondary_btn_url = 'https://wa.me/6281215151552' WHERE id = 1;

DELETE FROM features;
INSERT INTO features (icon, title, description, sort_order, is_active) VALUES
 ('fa-calendar-check', 'Berpengalaman', 'Melayani Sejak 2009 — Pengalaman lebih dari 17 tahun memberangkatkan jamaah Umrah, dikelola oleh tim yang memahami seluk-beluk perjalanan ke Tanah Suci.', 1, 1),
 ('fa-certificate', 'Berizin', 'Resmi PPIU & PIHK — Beroperasi dengan izin umrah (PPIU) dan izin haji khusus (PIHK) yang terdaftar resmi, sehingga keberangkatan jamaah legal dan terlindungi.', 2, 1),
 ('fa-user-graduate', 'Bimbingan', 'Muthawif & Tour Leader — Setiap keberangkatan didampingi tour leader, pembimbing ibadah, dan muthawif kompeten bersertifikat resmi BNSP, lulusan pondok pesantren serta universitas Islam ternama.', 3, 1),
 ('fa-list-check', 'Fleksibel', 'Pilihan Program Beragam — Dari Umrah Reguler, Hemat, hingga Premium — disesuaikan kemampuan dan kebutuhan jamaah.', 4, 1);
