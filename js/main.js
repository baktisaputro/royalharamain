/* ============================================================
   MAIN — membaca DATA dari data.js dan merender halaman
   Gaya terinspirasi jejakimani.com. Tidak perlu diedit
   untuk update konten (cukup edit js/data.js).
   ============================================================ */

document.addEventListener("DOMContentLoaded", () => {
  renderNavbar();
  renderHero();
  renderBadges();
  renderServices();
  renderPackages();
  renderAbout();
  renderTestimonials();
  renderUstadz();
  renderPartners();
  renderArticles();
  renderOffices();
  renderFooter();
  renderFab();
  renderPopup();
  initRegisterForm();

  const toggle = document.getElementById("navToggle");
  const mobileNav = document.getElementById("navMobile");
  if (toggle && mobileNav) {
    toggle.addEventListener("click", () => {
      const isOpen = mobileNav.classList.toggle("open");
      toggle.setAttribute("aria-expanded", String(isOpen));
    });
    mobileNav.querySelectorAll(".nav-link").forEach((link) => {
      link.addEventListener("click", () => {
        if (!link.parentElement.classList.contains("has-children")) {
          mobileNav.classList.remove("open");
          toggle.setAttribute("aria-expanded", "false");
        }
      });
    });
    mobileNav.querySelectorAll(".has-children > .nav-link").forEach((link) => {
      link.addEventListener("click", (e) => {
        e.preventDefault();
        link.parentElement.classList.toggle("open");
      });
    });
  }

  highlightNav();
});

/* ============ NAVBAR ============ */
function renderNavbar() {
  const brand = document.getElementById("brand");
  const title = document.getElementById("siteTitle");
  if (brand) {
    brand.innerHTML = `<img src="${DATA.logo}" alt="${DATA.brand}" class="brand-logo"><span>${DATA.brand}</span>`;
  }
  if (title) title.textContent = DATA.siteTitle;

  const desktop = document.getElementById("navDesktop");
  if (desktop) {
    desktop.innerHTML = `<ul>${DATA.nav
      .map((item) => {
        const hasChildren = item.children && item.children.length;
        const dropdown = hasChildren
          ? `<div class="dropdown">${item.children
              .map((c) => `<a href="${c.href}">${c.label}</a>`)
              .join("")}</div>`
          : "";
        return `<li><a class="nav-link" href="${item.href}">${item.label}${
          hasChildren ? ' <i class="fa-solid fa-chevron-down" style="font-size:10px"></i>' : ""
        }</a>${dropdown}</li>`;
      })
      .join("")}</ul>`;
  }

  const mobile = document.getElementById("navMobile");
  if (mobile) {
    const navCta = document.getElementById("navCta");
    mobile.innerHTML = `<ul>${DATA.nav
      .map((item) => {
        const hasChildren = item.children && item.children.length;
        const dropdown = hasChildren
          ? `<div class="dropdown">${item.children
              .map((c) => `<a href="${c.href}">${c.label}</a>`)
              .join("")}</div>`
          : "";
        return `<li class="${hasChildren ? "has-children" : ""}"><a class="nav-link" href="${item.href}">${item.label}${
          hasChildren ? ' <i class="fa-solid fa-chevron-down"></i>' : ""
        }</a>${dropdown}</li>`;
      })
      .join("")}</ul><a class="btn btn-gold nav-cta" href="${DATA.ctaButton.url}">${DATA.ctaButton.text}</a>`;
  }

  const cta = document.getElementById("navCta");
  if (cta) cta.textContent = DATA.ctaButton.text;
}

/* ============ HERO ============ */
function renderHero() {
  const el = document.getElementById("hero");
  if (!el) return;
  const h = DATA.hero;
  el.style.background = `linear-gradient(180deg, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.30) 70%, rgba(0,0,0,0.45) 100%), url('${h.background}') center/cover no-repeat`;
  el.innerHTML = `
    <div class="hero-content">
      <h1>${h.title}</h1>
      <p class="hero-quote">${h.quote}</p>
      <p class="hero-quote-source">— ${h.quoteSource}</p>
      <div class="hero-actions">
        <a href="${h.primaryBtn.url}" class="btn btn-gold">${h.primaryBtn.text}</a>
        <a href="${h.secondaryBtn.url}" class="btn btn-outline-light">${h.secondaryBtn.text}</a>
      </div>
    </div>`;
}

/* ============ BADGE LEGALITAS ============ */
function renderBadges() {
  const el = document.getElementById("badges");
  if (!el) return;
  el.innerHTML = `<div class="container">${DATA.hero.legalBadges
    .map((b) => `<span class="badge-pill">${b}</span>`)
    .join("")}</div>`;
}

/* ============ LAYANAN (BENTO GRID) ============ */
function renderServices() {
  const grid = document.getElementById("servicesGrid");
  if (!grid) return;
  grid.innerHTML = DATA.services
    .map(
      (s) => `
    <div class="bento-item" data-area="${s.area}">
      <img src="${s.image}" alt="${s.title}">
      <div class="bento-overlay">
        <h3>${s.title}</h3>
        <p>${s.desc}</p>
        <a href="${s.url}" class="link-arrow">Lihat Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </div>`
    )
    .join("");
}

/* ============ PAKET UMROH & HAJI ============ */
function getPackages() {
  try {
    const raw = localStorage.getItem("rhi_packages");
    if (raw) {
      const stored = JSON.parse(raw);
      if (Array.isArray(stored) && stored.length) return stored;
    }
  } catch (e) {
    /* fallback ke data.js */
  }
  return DATA.packages;
}

function renderPackages() {
  const grid = document.getElementById("packagesGrid");
  if (!grid) return;
  grid.innerHTML = getPackages()
    .map(
      (p) => `
    <div class="paket-card${p.featured ? " featured" : ""}">
      <img src="${p.image || "assets/images/logo.png"}" alt="${p.title}" class="paket-img">
      <div class="paket-body">
        ${p.badge ? `<span class="paket-badge">${p.badge}</span>` : ""}
        <h3>${p.title}</h3>
        <div class="paket-meta"><i class="fa-solid fa-clock"></i> ${p.duration || ""}</div>
        <div class="paket-price">${p.price}</div>
        <ul class="paket-facilities">
          ${(p.facilities || []).map((f) => `<li>${f}</li>`).join("")}
        </ul>
        <a href="${p.url}" class="btn ${p.featured ? "btn-gold" : "btn-outline-dark"} btn-full">Detail &amp; Daftar</a>
      </div>
    </div>`
    )
    .join("");
}

/* ============ TENTANG ============ */
function renderAbout() {
  const wrap = document.getElementById("aboutWrap");
  if (!wrap) return;
  const a = DATA.about;
  wrap.innerHTML = `
    <div class="about-media"><img src="${a.image}" alt="Tentang kami"></div>
    <div class="about-caption">
      <span class="section-label">${a.label}</span>
      <h2>${a.heading}</h2>
      ${a.paragraphs.map((p) => `<p>${p}</p>`).join("")}
      <div class="about-visi"><p><strong>Visi:</strong> ${a.vision}</p></div>
      <h3 style="font-family:var(--serif);font-size:18px;color:var(--gray-900);margin-bottom:10px;">Misi</h3>
      <ul class="mission-list">
        ${a.missions.map((m) => `<li>${m}</li>`).join("")}
      </ul>
      <div class="about-sign">
        <strong>${a.signatureName}</strong>
        <span>${a.signatureRole}</span>
      </div>
    </div>`;
}

/* ============ TESTIMONI ============ */
function renderTestimonials() {
  const grid = document.getElementById("testimonialsGrid");
  if (!grid) return;
  grid.innerHTML = DATA.testimonials
    .map(
      (t) => `
    <div class="testimonial-card">
      <div class="quote"><strong>\u201C</strong> ${t.quote} <strong>\u201D</strong></div>
      <div class="testimonial-persona">
        <img src="${t.avatar}" alt="${t.name}">
        <div>
          <strong>${t.name}</strong>
          <span>${t.role}</span>
        </div>
      </div>
    </div>`
    )
    .join("");
}

/* ============ USTADZ ============ */
function renderUstadz() {
  const grid = document.getElementById("ustadzGrid");
  if (!grid) return;
  grid.innerHTML = DATA.ustads
    .map(
      (u) => `
    <div class="ustadz-card">
      <img src="${u.avatar}" alt="${u.name}">
      <strong>${u.name}</strong>
      <span>${u.role}</span>
    </div>`
    )
    .join("");
}

/* ============ PARTNER MARQUEE ============ */
function renderPartners() {
  const slider = document.getElementById("partnerSlider");
  if (!slider) return;
  const chips = DATA.partners.map((p) => `<span class="partner-chip">${p}</span>`).join("");
  slider.innerHTML = `<div class="logo-slider-inner">${chips}${chips}</div>`;
}

/* ============ ARTIKEL ============ */
function getArticles() {
  try {
    const raw = localStorage.getItem("rhi_articles");
    if (raw) {
      const stored = JSON.parse(raw);
      if (Array.isArray(stored) && stored.length) return stored;
    }
  } catch (e) {
    /* fallback ke data.js */
  }
  return DATA.articles;
}

function renderArticles() {
  const grid = document.getElementById("articlesGrid");
  if (!grid) return;
  grid.innerHTML = getArticles()
    .map(
      (a) => `
    <article class="article-card">
      <img src="${a.image || "assets/images/logo.png"}" alt="${a.title}">
      <div class="article-body">
        <p class="article-date">${a.date || ""}</p>
        <h3>${a.title}</h3>
        <p>${a.excerpt}</p>
        <a href="${a.url}" class="link-arrow">Baca Selengkapnya <i class="fa-solid fa-arrow-right"></i></a>
      </div>
    </article>`
    )
    .join("");
}

/* ============ LOKASI ============ */
function renderOffices() {
  const grid = document.getElementById("officesGrid");
  if (!grid) return;
  grid.innerHTML = DATA.offices
    .map(
      (o) => `
    <div class="office-card">
      <div class="office-icon"><i class="fa-solid fa-location-dot"></i></div>
      <h3>${o.city}</h3>
      <a href="https://wa.me/${o.phone.replace(/[^0-9]/g, "")}">${o.phone}</a>
      <p>${o.address}</p>
    </div>`
    )
    .join("");
}

/* ============ FOOTER ============ */
function renderFooter() {
  const el = document.getElementById("footer");
  if (!el) return;
  const f = DATA.footer;
  el.innerHTML = `
    <div class="container">
      <div class="footer-grid">
        <div>
          <h4>${DATA.brand}</h4>
          <p>${f.description}</p>
          <p><i class="fa-solid fa-location-dot" style="color:var(--gold-strong)"></i> ${f.address}</p>
          <p><i class="fa-solid fa-envelope" style="color:var(--gold-strong)"></i> ${f.email}</p>
          <div class="footer-social">
            ${DATA.contact.socials
              .map((s) => `<a href="${s.url}" aria-label="${s.name}"><i class="${s.icon}"></i></a>`)
              .join("")}
          </div>
        </div>
        <div>
          <h4>Layanan</h4>
          <ul>${f.serviceLinks.map((l) => `<li><a href="${l.url}">${l.label}</a></li>`).join("")}</ul>
        </div>
        <div>
          <h4>Perusahaan</h4>
          <ul>${f.companyLinks.map((l) => `<li><a href="${l.url}">${l.label}</a></li>`).join("")}</ul>
          <ul style="margin-top:8px;"><li><a href="admin.html"><i class="fa-solid fa-lock" style="font-size:12px;margin-right:4px;color:var(--gold-strong)"></i> Kelola Artikel (Admin)</a></li></ul>
        </div>
        <div>
          <h4>Legal</h4>
          <ul>${f.legalLinks.map((l) => `<li><a href="${l.url}">${l.label}</a></li>`).join("")}</ul>
        </div>
      </div>
      <div class="footer-bottom">
        &copy; ${f.copyrightYear} ${f.legalName}. All rights reserved.
      </div>
    </div>`;
}

/* ============ FAB WHATSAPP ============ */
function renderFab() {
  const fab = document.getElementById("fabWa");
  if (fab && DATA.contact.whatsapp) fab.href = DATA.contact.whatsapp;
}

/* ============ POPUP PROMO ============ */
function renderPopup() {
  const forceTest = new URLSearchParams(window.location.search).get("promo") === "1";

  let promo;
  try {
    const raw = localStorage.getItem("rhi_promo");
    promo = raw ? JSON.parse(raw) : null;
  } catch (e) {
    promo = null;
  }

  const overlay = document.getElementById("popupOverlay");
  if (!overlay) return;

  if (forceTest) {
    promo = promo && promo.enabled ? promo : {
      enabled: true,
      badge: "PROMO SPESIAL",
      title: "Diskon 10% Umroh Reguler",
      message: "Daftar sebelum tanggal 30 dan dapatkan diskon spesial. Kuota terbatas!",
      image: "assets/images/logo.png",
      link: "#daftar",
      delay: 0,
      showOnce: false,
    };
  } else if (!promo || !promo.enabled) {
    return;
  }

  const img = document.getElementById("popupImg");
  const badge = document.getElementById("popupBadge");
  const title = document.getElementById("popupTitle");
  const message = document.getElementById("popupMessage");
  const link = document.getElementById("popupLink");
  if (img) img.src = promo.image || "assets/images/logo.png";
  if (badge) badge.textContent = promo.badge || "PROMO";
  if (title) title.textContent = promo.title || "";
  if (message) message.textContent = promo.message || "";
  if (link) link.href = promo.link || "#daftar";

  if (!forceTest) {
    const showOnce = promo.showOnce !== false;
    if (showOnce) {
      const today = new Date().toDateString();
      if (localStorage.getItem("rhi_promo_seen") === today) return;
      localStorage.setItem("rhi_promo_seen", today);
    }
  }

  const delay = (Number(promo.delay) || 3) * 1000;
  setTimeout(() => {
    overlay.hidden = false;
    document.body.style.overflow = "hidden";
  }, delay);

  const close = document.getElementById("popupClose");
  if (close) {
    close.addEventListener("click", () => {
      overlay.hidden = true;
      document.body.style.overflow = "";
    });
  }
  overlay.addEventListener("click", (e) => {
    if (e.target === overlay) {
      overlay.hidden = true;
      document.body.style.overflow = "";
    }
  });
}

/* ============ FORM PENDAFTARAN (GRAB DATA) ============ */
function initRegisterForm() {
  const form = document.getElementById("registerForm");
  if (!form) return;

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    const name = document.getElementById("regName").value.trim();
    const phone = document.getElementById("regPhone").value.trim();
    const interest = document.getElementById("regInterest").value;
    const message = document.getElementById("regMessage").value.trim();
    if (!name || !phone) return;

    let leads = [];
    try {
      leads = JSON.parse(localStorage.getItem("rhi_leads")) || [];
    } catch (err) {
      leads = [];
    }
    leads.unshift({
      name: name,
      phone: phone,
      interest: interest,
      message: message,
      date: new Date().toLocaleString("id-ID"),
    });
    localStorage.setItem("rhi_leads", JSON.stringify(leads));

    const success = document.getElementById("registerSuccess");
    if (success) success.hidden = false;
    form.querySelector("button[type='submit']").disabled = true;
    form.reset();
  });
}

/* ============ HIGHLIGHT NAV SAAT SCROLL ============ */
function highlightNav() {
  const sections = document.querySelectorAll("main section[id]");
  const desktopLinks = document.querySelectorAll(".nav-desktop a.nav-link");
  if (!sections.length || !desktopLinks.length) return;

  window.addEventListener("scroll", () => {
    const pos = window.scrollY + 120;
    let currentId = "hero";
    sections.forEach((sec) => {
      if (pos >= sec.offsetTop) currentId = sec.id;
    });
    desktopLinks.forEach((link) => {
      link.classList.toggle("active", link.getAttribute("href") === "#" + currentId);
    });
  });
}