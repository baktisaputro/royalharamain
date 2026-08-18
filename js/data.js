/* ============================================================
   DATA TRAVEL & UMROH — PT Royal Haramain Internasional
   Gaya: terinspirasi jejakimani.com (data-driven, mudah update).
   Edit file ini saja untuk mengubah konten. Lalu refresh browser.
   ============================================================ */

const DATA = {
  /* ---------- Identitas / Navbar ---------- */
  brand: "PT Royal Haramain Internasional",
  siteTitle: "PT Royal Haramain Internasional — Travel Haji, Umroh & Halal Tours",
  logo: "assets/images/logo.png",
  ctaButton: { text: "Konsultasi Gratis", url: "#lokasi" },

  nav: [
    { label: "Beranda", href: "#hero" },
    {
      label: "Layanan",
      href: "#layanan",
      children: [
        { label: "Haji", href: "#layanan" },
        { label: "Umroh", href: "#layanan" },
        { label: "Jelajah Dunya", href: "#layanan" },
        { label: "Badal Haji", href: "#layanan" },
        { label: "Badal Umroh", href: "#layanan" },
        { label: "Tabungan Umroh", href: "#layanan" },
      ],
    },
    { label: "Tentang", href: "#tentang" },
    { label: "Paket", href: "#paket" },
    { label: "Artikel", href: "#artikel" },
    { label: "Lokasi", href: "#lokasi" },
    { label: "Daftar", href: "#daftar" },
  ],

  /* ---------- Hero ---------- */
  hero: {
    title: "Travel Haji, Umroh dan Halal Tours",
    quote:
      "\u201CIkutkanlah umroh kepada haji, karena keduanya menghilangkan kemiskinan dan dosa-dosa sebagaimana pembakaran menghilangkan karat pada besi, emas, dan perak. Sementara tidak ada pahala bagi haji yang mabrur kecuali surga.\u201D",
    quoteSource: "HR. An Nasai, Tirmidzi dan Ahmad",
    background:
      "https://images.unsplash.com/photo-1565557623262-b51c2513a641?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80",
    primaryBtn: { text: "Konsultasi Gratis", url: "#lokasi" },
    secondaryBtn: { text: "Lihat Paket", url: "#layanan" },
    legalBadges: ["AMPHURI", "PIHK No. 394", "PPIU No. U.533", "Kemenag", "Siskopatuh"],
  },

  /* ---------- Layanan (bento grid) ---------- */
  services: [
    {
      area: "haji",
      title: "Haji",
      desc: "Sempurnakan rukun Islam dengan waktu tunggu lebih cepat hingga tanpa antri.",
      image:
        "https://images.unsplash.com/photo-1582200832860-2646fc0c6f55?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
    {
      area: "umroh",
      title: "Umroh",
      desc: "Dapatkan ketenangan hati dari ibadah umroh di Tanah Suci dengan fasilitas lebih nyaman.",
      image:
        "https://images.unsplash.com/photo-1591146200236-4c4b901fdb70?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
    {
      area: "jelajah",
      title: "Jelajah Dunya",
      desc: "Nikmati keindahan bumi Allah dengan menjelajahi berbagai negara di dunia sebagai ungkapan syukur.",
      image:
        "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
    {
      area: "badal-haji",
      title: "Badal Haji",
      desc: "Bantu sempurnakan rukun islam orang terkasih yang memiliki keterbatasan fisik.",
      image:
        "https://images.unsplash.com/photo-1542042220-47b2c93d25ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
    {
      area: "badal-umroh",
      title: "Badal Umroh",
      desc: "Dapatkan pahala umroh untuk orang tersayang dengan menggantikannya.",
      image:
        "https://images.unsplash.com/photo-1590396590212-04e38deec1c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
    {
      area: "tabungan",
      title: "Tabungan Umroh",
      desc: "Wujudkan impian beribadah di Tanah Suci dengan menyisihkan sebagian rezeki.",
      image:
        "https://images.unsplash.com/photo-1520639888713-7851133b1ed0?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
      url: "#",
    },
  ],

  /* ---------- Paket Umroh & Haji ---------- */
  packages: [
    {
      title: "Paket Umroh Reguler",
      price: "Rp 28.500.000",
      duration: "9 Hari",
      featured: false,
      facilities: [
        "Hotel Bintang 4 (Makkah & Madinah)",
        "Pesawat Garuda / Saudia",
        "Muthawwif Berpengalaman",
        "Bus Full AC (Saptco)",
        "Visa Umroh & Perlengkapan",
      ],
      image:
        "https://images.unsplash.com/photo-1590396590212-04e38deec1c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
      url: "#",
    },
    {
      title: "Paket Umroh Plus",
      price: "Rp 39.000.000",
      duration: "12 Hari",
      featured: true,
      badge: "PALING DIMINATI",
      facilities: [
        "Hotel Bintang 5 (Pelataran Masjid)",
        "Penerbangan Direct (Saudia)",
        "Kereta Cepat Haramain",
        "Eksklusif Lounge & Fast Track",
        "City Tour Madinah & Ziarah",
      ],
      image:
        "https://images.unsplash.com/photo-1591146200236-4c4b901fdb70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
      url: "#",
    },
    {
      title: "Paket Haji Khusus",
      price: "Rp 65.000.000",
      duration: "25 Hari",
      featured: false,
      facilities: [
        "Hotel Bintang 5 (Makkah & Madinah)",
        "Muthawwif & Pembimbing Haji",
        "Manasik Lengkap",
        "Visa Haji & Perlengkapan",
        "Pembimbingan di Tanah Suci",
      ],
      image:
        "https://images.unsplash.com/photo-1520639888713-7851133b1ed0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80",
      url: "#",
    },
  ],

  /* ---------- Tentang Kami ---------- */
  about: {
    label: "Tentang Kami",
    heading: "Menjadi Sebaik-baiknya Pelayan Tamu Allah",
    paragraphs: [
      "PT Royal Haramain Internasional adalah travel haji, umroh dan halal tours yang menyediakan berbagai layanan untuk memudahkan umat Muslim dalam mewujudkan tujuan mulia. Sejak awal didirikan, kami berkomitmen menjadi sebaik-baiknya pelayan para tamu Allah menuju Tanah Suci.",
      "Tahun demi tahun kami lewati dengan semangat untuk senantiasa memperbaiki kualitas dan sistem pelayanan agar jamaah dapat beribadah dengan maksimal dan lebih nyaman.",
    ],
    vision:
      "\u201CMenjadi biro haji, umroh, islamic dan halal tours terbesar di Indonesia dengan pelayanan prima.\u201D",
    missions: [
      "Membangun hubungan interpersonal yang bersifat kekeluargaan antara perusahaan dengan jamaah maupun calon jamaah.",
      "Terus menerus meningkatkan pelayanan haji, umrah, islamic dan halal tours dengan melayani jamaah sepenuh hati.",
      "Memudahkan jamaah dalam program pembayaran biaya haji dan umrah.",
      "Menjaga kualitas syariat dalam setiap ibadah haji khusus dan umrah.",
      "Memberi pelayanan terbaik dengan menyediakan makanan halal dan memfasilitasi tempat shalat selama program.",
    ],
    signatureName: "Direktur Utama",
    signatureRole: "PT Royal Haramain Internasional",
    image:
      "https://images.unsplash.com/photo-1596484552993-9c88be238712?ixlib=rb-4.0.3&auto=format&fit=crop&w=900&q=80",
  },

  /* ---------- Testimoni ---------- */
  testimonials: [
    {
      quote:
        "Rasa syukur dan haru melebur menjadi satu menjalani ibadah umrah. Nikmat tiada terukur Allah izinkan untuk bisa bertamu ke Tanah Suci.",
      name: "Ibu Siti Rahma",
      role: "Jamaah Umroh Reguler 2025",
      avatar: "https://i.pravatar.cc/120?img=47",
    },
    {
      quote:
        "Pelayanannya luar biasa, semua kebutuhan diurus dari A sampai Z. Saya hanya fokus beribadah, semua yang lain sudah ditangani tim.",
      name: "Bapak Ahmad Fauzi",
      role: "Jamaah Haji Khusus 2025",
      avatar: "https://i.pravatar.cc/120?img=12",
    },
    {
      quote:
        "Alhamdulillah, pembimbing ustadznya sangat sabar dan ilmunya dalam. Perjalanan umroh jadi sangat bermakna dan nyaman.",
      name: "Ibu Dewi Lestari",
      role: "Jamaah Umroh Plus 2026",
      avatar: "https://i.pravatar.cc/120?img=32",
    },
  ],

  /* ---------- Ustadz Pembimbing ---------- */
  ustads: [
    { name: "Ustadz H. Ahmad Zaki", role: "Pembimbing Umroh", avatar: "https://i.pravatar.cc/120?img=11" },
    { name: "Ustadz H. Muhammad Rizal", role: "Pembimbing Haji", avatar: "https://i.pravatar.cc/120?img=8" },
    { name: "Ustadzah Ummi Fatimah", role: "Pembimbing Wanita", avatar: "https://i.pravatar.cc/120?img=45" },
  ],

  /* ---------- Partner ---------- */
  partners: [
    "Partner 1",
    "Partner 2",
    "Partner 3",
    "Partner 4",
    "Partner 5",
    "Partner 6",
    "Partner 7",
    "Partner 8",
  ],

  /* ---------- Artikel ---------- */
  articles: [
    {
      image:
        "https://images.unsplash.com/photo-1606132479707-1e66c7a7b8e1?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
      title: "Niat & Doa Ihram",
      excerpt:
        "Kumpulan doa shahih saat memulai ihram dari Miqat hingga memasuki Masjidil Haram.",
      date: "12 Agustus 2026",
      url: "#",
    },
    {
      image:
        "https://images.unsplash.com/photo-1542042220-47b2c93d25ce?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
      title: "Tata Cara Tawaf",
      excerpt:
        "Panduan lengkap melakukan tawaf 7 putaran beserta doa di setiap putarannya.",
      date: "5 Agustus 2026",
      url: "#",
    },
    {
      image:
        "https://images.unsplash.com/photo-1507525428034-b723cf961d3e?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80",
      title: "Barang Bawaan Umroh",
      excerpt:
        "Daftar perlengkapan penting yang wajib dibawa oleh jamaah laki-laki dan perempuan.",
      date: "28 Juli 2026",
      url: "#",
    },
  ],

  /* ---------- Lokasi Kantor ---------- */
  offices: [
    {
      city: "Kantor Pusat — Jakarta",
      phone: "+62 811 2000 0180",
      address: "Jl. Sudirman No. 88, Jakarta Pusat, DKI Jakarta 10210",
    },
    {
      city: "Cabang Surabaya",
      phone: "+62 811 329 0037",
      address: "Jl. Pemuda No. 45, Surabaya, Jawa Timur 60271",
    },
    {
      city: "Cabang Bandung",
      phone: "+62 811 800 8846",
      address: "Jl. Asia Afrika No. 12, Bandung, Jawa Barat 40111",
    },
    {
      city: "Cabang Makassar",
      phone: "+62 811 450 2211",
      address: "Jl. Jend. Sudirman No. 5, Makassar, Sulawesi Selatan 90111",
    },
  ],

  /* ---------- Kontak & WhatsApp FAB ---------- */
  contact: {
    email: "info@royalharamain.com",
    whatsapp: "https://wa.me/6281120000180",
    socials: [
      { name: "Instagram", icon: "fa-brands fa-instagram", url: "#" },
      { name: "TikTok", icon: "fa-brands fa-tiktok", url: "#" },
      { name: "YouTube", icon: "fa-brands fa-youtube", url: "#" },
      { name: "Facebook", icon: "fa-brands fa-facebook-f", url: "#" },
    ],
  },

  /* ---------- Footer ---------- */
  footer: {
    description:
      "Travel haji, umroh dan halal tours terpercaya yang melayani para tamu Allah dengan sepenuh hati.",
    address: "Jl. Sudirman No. 88, Jakarta Pusat, DKI Jakarta 10210",
    email: "info@royalharamain.com",
    serviceLinks: [
      { label: "Haji Khusus", url: "#" },
      { label: "Umroh Reguler", url: "#" },
      { label: "Umroh Plus", url: "#" },
      { label: "Jelajah Dunya", url: "#" },
      { label: "Tabungan Umroh", url: "#" },
      { label: "Badal Haji & Umroh", url: "#" },
    ],
    companyLinks: [
      { label: "Tentang Kami", url: "#tentang" },
      { label: "Profil Ustadz", url: "#ustadz" },
      { label: "Artikel", url: "#artikel" },
      { label: "Lokasi Kantor", url: "#lokasi" },
      { label: "Hubungi Kami", url: "#lokasi" },
    ],
    legalLinks: [
      { label: "Kebijakan & Privasi", url: "#" },
      { label: "Legalitas & Izin", url: "#" },
    ],
    copyrightYear: 2026,
    legalName: "PT ROYAL HARAMAIN INTERNASIONAL",
  },
};