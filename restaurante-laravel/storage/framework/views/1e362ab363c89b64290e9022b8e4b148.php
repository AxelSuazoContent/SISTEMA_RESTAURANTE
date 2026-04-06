<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservaciones — <?php echo e($config->nombre_negocio); ?></title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:ital,wght@0,300;0,600;1,300;1,600&display=swap" rel="stylesheet">
<style>
:root {
  --navy:        #0f172a;
  --navy-mid:    #1e293b;
  --navy-light:  #334155;
  --blue:        #3b82f6;
  --blue-dark:   #1d4ed8;
  --blue-glow:   rgba(59,130,246,0.15);
  --accent:      #38bdf8;
  --white:       #f8fafc;
  --muted:       #94a3b8;
  --border:      rgba(148,163,184,0.12);
  --success:     #22c55e;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

html { scroll-behavior: smooth; }

body {
  font-family: 'Plus Jakarta Sans', sans-serif;
  background: var(--navy);
  color: var(--white);
  overflow-x: hidden;
}

/* ── NOISE TEXTURE ── */
body::after {
  content: '';
  position: fixed;
  inset: 0;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.03'/%3E%3C/svg%3E");
  pointer-events: none;
  z-index: 9998;
}

/* ── NAVBAR ── */
.navbar {
  position: fixed;
  top: 0; left: 0; right: 0;
  z-index: 100;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0 40px;
  height: 64px;
  background: rgba(15,23,42,0.85);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid var(--border);
}

.navbar-brand {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--white);
  text-decoration: none;
}

.navbar-brand .dot {
  width: 8px; height: 8px;
  background: var(--blue);
  border-radius: 50%;
  box-shadow: 0 0 8px var(--blue);
}

.navbar-links { display: flex; align-items: center; gap: 8px; }

.navbar-links a {
  font-size: 0.82rem;
  font-weight: 500;
  color: var(--muted);
  text-decoration: none;
  padding: 6px 14px;
  border-radius: 6px;
  transition: all 0.2s;
}
.navbar-links a:hover { color: var(--white); background: rgba(255,255,255,0.06); }

.navbar-links .btn-reservar {
  background: var(--blue);
  color: white;
  padding: 8px 18px;
  border-radius: 8px;
  font-weight: 600;
}
.navbar-links .btn-reservar:hover { background: var(--blue-dark); }

/* ── HERO ── */
.hero {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 100px 24px 60px;
  position: relative;
  overflow: hidden;
}

.hero-glow-1 {
  position: absolute;
  width: 600px; height: 600px;
  background: radial-gradient(circle, rgba(59,130,246,0.12) 0%, transparent 70%);
  top: -100px; right: -100px;
  pointer-events: none;
}
.hero-glow-2 {
  position: absolute;
  width: 400px; height: 400px;
  background: radial-gradient(circle, rgba(56,189,248,0.08) 0%, transparent 70%);
  bottom: 0; left: -50px;
  pointer-events: none;
}

.hero-grid {
  position: absolute;
  inset: 0;
  background-image:
    linear-gradient(rgba(148,163,184,0.03) 1px, transparent 1px),
    linear-gradient(90deg, rgba(148,163,184,0.03) 1px, transparent 1px);
  background-size: 48px 48px;
  pointer-events: none;
}

.hero-content {
  position: relative;
  z-index: 2;
  text-align: center;
  max-width: 760px;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: rgba(59,130,246,0.1);
  border: 1px solid rgba(59,130,246,0.25);
  border-radius: 999px;
  padding: 6px 16px;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--accent);
  letter-spacing: 0.08em;
  text-transform: uppercase;
  margin-bottom: 28px;
  animation: fadeUp 0.6s ease both;
}
.hero-badge::before {
  content: '';
  width: 6px; height: 6px;
  background: var(--accent);
  border-radius: 50%;
  box-shadow: 0 0 6px var(--accent);
}

.hero-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(3rem, 8vw, 5.5rem);
  font-weight: 600;
  line-height: 1.05;
  color: var(--white);
  margin-bottom: 24px;
  animation: fadeUp 0.6s 0.1s ease both;
}

.hero-title em {
  font-style: italic;
  color: var(--blue);
}

.hero-subtitle {
  font-size: 1.05rem;
  font-weight: 400;
  color: var(--muted);
  line-height: 1.8;
  margin-bottom: 44px;
  animation: fadeUp 0.6s 0.2s ease both;
  max-width: 520px;
  margin-left: auto;
  margin-right: auto;
}

.hero-actions {
  display: flex;
  gap: 12px;
  justify-content: center;
  flex-wrap: wrap;
  animation: fadeUp 0.6s 0.3s ease both;
}

.btn-primary {
  background: var(--blue);
  color: white;
  padding: 14px 32px;
  border-radius: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.9rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  text-decoration: none;
  display: inline-flex;
  align-items: center;
  gap: 8px;
  transition: all 0.2s;
  box-shadow: 0 4px 24px rgba(59,130,246,0.35);
}
.btn-primary:hover { background: var(--blue-dark); transform: translateY(-1px); box-shadow: 0 8px 32px rgba(59,130,246,0.45); }

.btn-ghost {
  background: rgba(255,255,255,0.05);
  color: var(--white);
  padding: 14px 32px;
  border-radius: 10px;
  font-size: 0.9rem;
  font-weight: 600;
  border: 1px solid var(--border);
  cursor: pointer;
  text-decoration: none;
  transition: all 0.2s;
}
.btn-ghost:hover { background: rgba(255,255,255,0.1); }

/* ── STATS ── */
.stats {
  padding: 48px 24px;
  border-top: 1px solid var(--border);
  border-bottom: 1px solid var(--border);
  background: rgba(30,41,59,0.5);
}

.stats-inner {
  max-width: 900px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
  gap: 40px;
  text-align: center;
}

.stat-number {
  font-family: 'Fraunces', serif;
  font-size: 2.8rem;
  font-weight: 600;
  color: var(--blue);
  line-height: 1;
}

.stat-label {
  font-size: 0.72rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--muted);
  margin-top: 8px;
}

/* ── SECTION ── */
.section { padding: 96px 24px; }

.section-inner { max-width: 1100px; margin: 0 auto; }

.section-tag {
  display: inline-block;
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.12em;
  text-transform: uppercase;
  color: var(--blue);
  margin-bottom: 16px;
}

.section-title {
  font-family: 'Fraunces', serif;
  font-size: clamp(2rem, 4vw, 3rem);
  font-weight: 600;
  line-height: 1.15;
  color: var(--white);
  margin-bottom: 16px;
}

.section-title em { font-style: italic; color: var(--accent); }

/* ── ABOUT ── */
.about-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 72px;
  align-items: center;
}

.about-text p {
  font-size: 0.95rem;
  line-height: 1.85;
  color: var(--muted);
  margin-bottom: 20px;
}

.about-hours {
  display: flex;
  gap: 32px;
  margin-top: 40px;
  padding-top: 32px;
  border-top: 1px solid var(--border);
}

.hour-item .day {
  font-weight: 700;
  font-size: 0.95rem;
  color: var(--white);
  margin-bottom: 4px;
}

.hour-item .time {
  font-size: 0.8rem;
  color: var(--muted);
}

.about-visual {
  background: var(--navy-mid);
  border: 1px solid var(--border);
  border-radius: 16px;
  padding: 48px;
  text-align: center;
  position: relative;
  overflow: hidden;
}

.about-visual::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, transparent, var(--blue), transparent);
}

.about-visual-icon { font-size: 4rem; margin-bottom: 24px; }

.about-visual-quote {
  font-family: 'Fraunces', serif;
  font-size: 1.15rem;
  font-style: italic;
  font-weight: 300;
  color: var(--muted);
  line-height: 1.7;
}

.about-visual-author {
  font-size: 0.75rem;
  font-weight: 600;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--blue);
  margin-top: 20px;
}

/* ── FEATURES ── */
.features-bg { background: var(--navy-mid); }

.features-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-top: 56px;
}

.feature-card {
  background: var(--navy);
  border: 1px solid var(--border);
  border-radius: 14px;
  padding: 28px;
  transition: all 0.3s;
  position: relative;
  overflow: hidden;
}

.feature-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 1px;
  background: linear-gradient(90deg, transparent, var(--blue), transparent);
  opacity: 0;
  transition: opacity 0.3s;
}

.feature-card:hover { border-color: rgba(59,130,246,0.3); transform: translateY(-3px); box-shadow: 0 12px 40px rgba(0,0,0,0.3); }
.feature-card:hover::before { opacity: 1; }

.feature-icon {
  width: 44px; height: 44px;
  background: var(--blue-glow);
  border: 1px solid rgba(59,130,246,0.2);
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.3rem;
  margin-bottom: 20px;
}

.feature-title {
  font-weight: 700;
  font-size: 0.95rem;
  color: var(--white);
  margin-bottom: 10px;
}

.feature-desc {
  font-size: 0.85rem;
  line-height: 1.7;
  color: var(--muted);
}

/* ── FORM SECTION ── */
.form-section { background: var(--navy); }

.form-wrapper {
  max-width: 680px;
  margin: 0 auto;
}

.form-card {
  background: var(--navy-mid);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 48px;
  position: relative;
  overflow: hidden;
}

.form-card::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0;
  height: 2px;
  background: linear-gradient(90deg, var(--blue-dark), var(--blue), var(--accent));
}

.form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
  margin-bottom: 16px;
}

.form-group { margin-bottom: 16px; }

.form-label {
  display: block;
  font-size: 0.78rem;
  font-weight: 600;
  letter-spacing: 0.05em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 8px;
}

.form-input {
  width: 100%;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 13px 16px;
  color: var(--white);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.92rem;
  outline: none;
  transition: all 0.2s;
  -webkit-appearance: none;
}

.form-input:focus {
  border-color: var(--blue);
  background: rgba(59,130,246,0.05);
  box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

.form-input::placeholder { color: rgba(148,163,184,0.4); }

select.form-input option { background: var(--navy-mid); color: var(--white); }

/* Personas */
.personas-grid {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}

.persona-btn {
  flex: 1;
  min-width: 52px;
  padding: 12px 8px;
  background: rgba(255,255,255,0.04);
  border: 1px solid var(--border);
  border-radius: 10px;
  color: var(--muted);
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.9rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
  text-align: center;
}

.persona-btn:hover { border-color: var(--blue); color: var(--white); }
.persona-btn.active { background: var(--blue-glow); border-color: var(--blue); color: var(--blue); }

/* Submit */
.btn-submit {
  width: 100%;
  background: var(--blue);
  color: white;
  padding: 16px;
  border-radius: 10px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-size: 0.95rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  box-shadow: 0 4px 24px rgba(59,130,246,0.3);
  margin-top: 8px;
}
.btn-submit:hover { background: var(--blue-dark); transform: translateY(-1px); }
.btn-submit:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.form-note {
  text-align: center;
  font-size: 0.75rem;
  color: var(--muted);
  margin-top: 16px;
  line-height: 1.6;
}

/* Spinner */
.spinner {
  width: 18px; height: 18px;
  border: 2px solid rgba(255,255,255,0.3);
  border-top-color: white;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
  display: none;
}

/* ── FOOTER ── */
footer {
  border-top: 1px solid var(--border);
  padding: 64px 24px 32px;
  background: var(--navy-mid);
}

.footer-inner {
  max-width: 1100px;
  margin: 0 auto;
}

.footer-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 48px;
  margin-bottom: 48px;
}

.footer-brand {
  font-family: 'Plus Jakarta Sans', sans-serif;
  font-weight: 800;
  font-size: 1.1rem;
  color: var(--white);
  margin-bottom: 12px;
  display: flex;
  align-items: center;
  gap: 8px;
}

.footer-brand .dot {
  width: 7px; height: 7px;
  background: var(--blue);
  border-radius: 50%;
}

.footer-desc {
  font-size: 0.85rem;
  color: var(--muted);
  line-height: 1.7;
}

.footer-heading {
  font-size: 0.72rem;
  font-weight: 700;
  letter-spacing: 0.1em;
  text-transform: uppercase;
  color: var(--muted);
  margin-bottom: 16px;
}

.footer-links { list-style: none; }

.footer-links li {
  font-size: 0.85rem;
  color: var(--muted);
  line-height: 2;
}

.footer-bottom {
  border-top: 1px solid var(--border);
  padding-top: 28px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 12px;
}

.footer-bottom p {
  font-size: 0.78rem;
  color: var(--muted);
}

/* ── SUCCESS OVERLAY ── */
.success-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,0.97);
  z-index: 500;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  text-align: center;
  padding: 40px;
}
.success-overlay.show { display: flex; animation: fadeIn 0.4s ease; }

.success-icon {
  width: 80px; height: 80px;
  background: rgba(34,197,94,0.1);
  border: 1px solid rgba(34,197,94,0.3);
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2.5rem;
  margin: 0 auto 28px;
  animation: scaleIn 0.4s 0.2s ease both;
}

.success-title {
  font-family: 'Fraunces', serif;
  font-size: 2.2rem;
  font-weight: 600;
  color: var(--white);
  margin-bottom: 12px;
}

.success-desc {
  font-size: 0.95rem;
  color: var(--muted);
  line-height: 1.8;
  max-width: 420px;
  margin: 0 auto 36px;
}

/* ── REVEAL ── */
.reveal {
  opacity: 0;
  transform: translateY(24px);
  transition: opacity 0.7s ease, transform 0.7s ease;
}
.reveal.visible { opacity: 1; transform: none; }

/* ── MOBILE MENU ── */
.mobile-menu-btn {
  display: none;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--white);
  padding: 4px;
}

.mobile-menu {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(15,23,42,0.98);
  z-index: 99;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  gap: 24px;
}
.mobile-menu.open { display: flex; }
.mobile-menu a {
  font-family: 'Fraunces', serif;
  font-size: 2rem;
  font-weight: 600;
  color: var(--white);
  text-decoration: none;
}
.mobile-menu-close {
  position: absolute;
  top: 20px; right: 20px;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--muted);
  font-size: 1.5rem;
}

/* ── ANIMATIONS ── */
@keyframes fadeUp {
  from { opacity: 0; transform: translateY(24px); }
  to   { opacity: 1; transform: none; }
}
@keyframes fadeIn {
  from { opacity: 0; }
  to   { opacity: 1; }
}
@keyframes spin {
  to { transform: rotate(360deg); }
}
@keyframes scaleIn {
  from { opacity: 0; transform: scale(0.8); }
  to   { opacity: 1; transform: scale(1); }
}

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .navbar { padding: 0 20px; }
  .navbar-links { display: none; }
  .mobile-menu-btn { display: block; }
  .about-grid { grid-template-columns: 1fr; gap: 40px; }
  .form-card { padding: 28px 20px; }
  .form-row { grid-template-columns: 1fr; }
  .footer-grid { grid-template-columns: 1fr 1fr; }
}
</style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <a href="#" class="navbar-brand">
    <span class="dot"></span>
    <?php echo e($config->nombre_negocio); ?>

  </a>
  <div class="navbar-links">
    <a href="#nosotros">Nosotros</a>
    <a href="#experiencia">Experiencia</a>
    <a href="tel:<?php echo e($config->telefono); ?>"><?php echo e($config->telefono); ?></a>
    <a href="#reservar" class="btn-reservar">Reservar Mesa</a>
  </div>
  <button class="mobile-menu-btn" onclick="toggleMenu()">
    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
  </button>
</nav>

<!-- MOBILE MENU -->
<div class="mobile-menu" id="mobileMenu">
  <button class="mobile-menu-close" onclick="toggleMenu()">✕</button>
  <a href="#nosotros" onclick="toggleMenu()">Nosotros</a>
  <a href="#experiencia" onclick="toggleMenu()">Experiencia</a>
  <a href="#reservar" onclick="toggleMenu()">Reservar</a>
</div>

<!-- HERO -->
<section class="hero" id="inicio">
  <div class="hero-grid"></div>
  <div class="hero-glow-1"></div>
  <div class="hero-glow-2"></div>
  <div class="hero-content">
    <div class="hero-badge">Reservaciones en línea</div>
    <h1 class="hero-title">
      Una experiencia<br>
      <em>que no olvidarás</em>
    </h1>
    <p class="hero-subtitle">
      Reserva tu mesa en <?php echo e($config->nombre_negocio); ?> y disfruta de una experiencia gastronómica única. Confirmamos tu reservación en menos de 2 horas.
    </p>
    <div class="hero-actions">
      <a href="#reservar" class="btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Hacer Reservación
      </a>
      <a href="#nosotros" class="btn-ghost">Conocernos</a>
    </div>
  </div>
</section>

<!-- STATS -->
<div class="stats">
  <div class="stats-inner">
    <div class="reveal">
      <div class="stat-number">12+</div>
      <div class="stat-label">Años de experiencia</div>
    </div>
    <div class="reveal" style="transition-delay:0.1s">
      <div class="stat-number">4,800+</div>
      <div class="stat-label">Comensales felices</div>
    </div>
    <div class="reveal" style="transition-delay:0.2s">
      <div class="stat-number">85+</div>
      <div class="stat-label">Platillos únicos</div>
    </div>
    <div class="reveal" style="transition-delay:0.3s">
      <div class="stat-number">⭐ 4.9</div>
      <div class="stat-label">Calificación promedio</div>
    </div>
  </div>
</div>

<!-- NOSOTROS -->
<section class="section" id="nosotros">
  <div class="section-inner">
    <div class="about-grid reveal">
      <div class="about-text">
        <span class="section-tag">Nuestra historia</span>
        <h2 class="section-title">Cocina con <em>alma y tradición</em></h2>
        <p>Cada receta nace de generaciones de sabor, de manos que conocen el arte de transformar ingredientes frescos en momentos que perduran en la memoria.</p>
        <p>Nuestro equipo de chefs selecciona los mejores ingredientes de temporada para ofrecerte una experiencia gastronómica auténtica, donde cada visita es única e irrepetible.</p>
        <div class="about-hours">
          <div class="hour-item">
            <div class="day">Lunes – Viernes</div>
            <div class="time">12:00 pm – 10:00 pm</div>
          </div>
          <div style="width:1px;background:var(--border);"></div>
          <div class="hour-item">
            <div class="day">Sábado – Domingo</div>
            <div class="time">11:00 am – 11:00 pm</div>
          </div>
        </div>
      </div>
      <div class="about-visual">
        <div class="about-visual-icon">🍽️</div>
        <p class="about-visual-quote">"La buena comida es la base de la felicidad genuina"</p>
        <p class="about-visual-author">— Auguste Escoffier</p>
      </div>
    </div>
  </div>
</section>

<!-- EXPERIENCIA -->
<section class="section features-bg" id="experiencia">
  <div class="section-inner">
    <div style="text-align:center;" class="reveal">
      <span class="section-tag">Por qué elegirnos</span>
      <h2 class="section-title">La experiencia <em>completa</em></h2>
    </div>
    <div class="features-grid">
      <div class="feature-card reveal" style="transition-delay:0s">
        <div class="feature-icon">🌿</div>
        <div class="feature-title">Ingredientes frescos</div>
        <p class="feature-desc">Seleccionamos cada ingrediente con cuidado, priorizando productores locales y de temporada.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.1s">
        <div class="feature-icon">👨‍🍳</div>
        <div class="feature-title">Chefs expertos</div>
        <p class="feature-desc">Técnicas tradicionales con innovación moderna para platos que sorprenden en cada visita.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.2s">
        <div class="feature-icon">🕯️</div>
        <div class="feature-title">Ambiente íntimo</div>
        <p class="feature-desc">Cada rincón está diseñado para crear momentos especiales con una atmósfera cálida y acogedora.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.3s">
        <div class="feature-icon">🍷</div>
        <div class="feature-title">Carta de bebidas</div>
        <p class="feature-desc">Vinos, cócteles artesanales y bebidas de autor que complementan cada momento de tu visita.</p>
      </div>
    </div>
  </div>
</section>

<!-- RESERVAR -->
<section class="section form-section" id="reservar">
  <div class="form-wrapper">
    <div style="text-align:center;margin-bottom:48px;" class="reveal">
      <span class="section-tag">Asegura tu lugar</span>
      <h2 class="section-title">Haz tu <em>reservación</em></h2>
      <p style="color:var(--muted);font-size:0.92rem;margin-top:8px;">Completa el formulario y te confirmamos en menos de 2 horas.</p>
    </div>

    <div class="form-card reveal">
      <form id="reservaForm" onsubmit="enviarReserva(event)">

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Nombre completo *</label>
            <input type="text" class="form-input" placeholder="Tu nombre" required id="nombre">
          </div>
          <div class="form-group">
            <label class="form-label">Teléfono *</label>
            <input type="tel" class="form-input" placeholder="+504 0000-0000" required id="telefono">
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Correo electrónico</label>
          <input type="email" class="form-input" placeholder="tu@email.com" id="email">
        </div>

        <div class="form-row">
          <div class="form-group">
            <label class="form-label">Fecha *</label>
            <input type="date" class="form-input" required id="fecha" style="color-scheme:dark;">
          </div>
          <div class="form-group">
            <label class="form-label">Hora *</label>
            <select class="form-input" required id="hora">
    <option value="" disabled selected>Selecciona una hora</option>
    <?php $__currentLoopData = $horas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hora): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            [$h, $m] = explode(':', $hora);
            $h = (int)$h;
            $sufijo = $h >= 12 ? 'pm' : 'am';
            $h12 = $h > 12 ? $h - 12 : ($h == 0 ? 12 : $h);
            $label = sprintf('%d:%s %s', $h12, $m, $sufijo);
        ?>
        <option value="<?php echo e($hora); ?>"><?php echo e($label); ?></option>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</select>
          </div>
        </div>

        <div class="form-group">
          <label class="form-label">Número de personas *</label>
          <div class="personas-grid">
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 1)">1</button>
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 2)">2</button>
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 3)">3</button>
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 4)">4</button>
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 5)">5</button>
            <button type="button" class="persona-btn" onclick="selectPersonas(this, 6)">6+</button>
          </div>
          <input type="hidden" id="personas" required>
        </div>

        <div class="form-group">
          <label class="form-label">Ocasión especial</label>
          <select class="form-input" id="ocasion">
            <option value="" disabled selected>Selecciona (opcional)</option>
            <option>Cumpleaños 🎂</option>
            <option>Aniversario 💑</option>
            <option>Cena romántica 🌹</option>
            <option>Reunión de negocios 💼</option>
            <option>Celebración familiar 👨‍👩‍👧</option>
            <option>Otra ocasión</option>
          </select>
        </div>

        <div class="form-group">
          <label class="form-label">Peticiones especiales</label>
          <textarea class="form-input" placeholder="Alergias, decoración especial, preferencias de mesa..." rows="3" id="notas" style="resize:vertical;min-height:88px;"></textarea>
        </div>

        <button type="submit" class="btn-submit" id="submitBtn">
          <span id="btnText">Solicitar Reservación</span>
          <div class="spinner" id="spinner"></div>
        </button>

        <p class="form-note">
          Al enviar confirmas nuestras políticas de reservación.<br>
          Te contactaremos al teléfono indicado para confirmar.
        </p>
      </form>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div>
        <div class="footer-brand">
          <span class="dot"></span>
          <?php echo e($config->nombre_negocio); ?>

        </div>
        <p class="footer-desc">Una experiencia gastronómica única donde cada visita se convierte en un recuerdo especial.</p>
      </div>
      <div>
        <div class="footer-heading">Horarios</div>
        <ul class="footer-links">
          <li>Lun – Vie &nbsp; 12pm – 10pm</li>
          <li>Sáb – Dom &nbsp; 11am – 11pm</li>
        </ul>
      </div>
      <div>
        <div class="footer-heading">Contacto</div>
        <ul class="footer-links">
          <li>📞 <?php echo e($config->telefono); ?></li>
          <li>📍 <?php echo e($config->direccion); ?></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <p>© 2026 <?php echo e($config->nombre_negocio); ?>. Todos los derechos reservados.</p>
      <p>Hecho con ❤️ en Honduras</p>
    </div>
  </div>
</footer>

<!-- SUCCESS OVERLAY -->
<div class="success-overlay" id="successOverlay">
  <div class="success-icon">✓</div>
  <h2 class="success-title">¡Reservación enviada!</h2>
  <p class="success-desc">
    Hemos recibido tu solicitud. Te contactaremos en las próximas
    <strong style="color:var(--blue)">2 horas</strong> para confirmar tu reservación.
  </p>
  <button onclick="closeSuccess()" class="btn-primary">Volver al inicio</button>
</div>

<script>
let personasSeleccionadas = null;

// Min date
const fechaInput = document.getElementById('fecha');
fechaInput.min = new Date().toISOString().split('T')[0];
fechaInput.value = new Date().toISOString().split('T')[0];

// Mobile menu
function toggleMenu() {
  document.getElementById('mobileMenu').classList.toggle('open');
}

// Personas
function selectPersonas(btn, num) {
  document.querySelectorAll('.persona-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
  personasSeleccionadas = num;
  document.getElementById('personas').value = num;
}

// Form
function enviarReserva(e) {
  e.preventDefault();

  if (!personasSeleccionadas) {
    alert('Por favor selecciona el número de personas.');
    return;
  }

  const btn  = document.getElementById('submitBtn');
  const text = document.getElementById('btnText');
  const spin = document.getElementById('spinner');

  btn.disabled = true;
  text.textContent = 'Enviando...';
  spin.style.display = 'block';

  fetch('<?php echo e(route("landing.store")); ?>', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
    },
    body: JSON.stringify({
      nombre:   document.getElementById('nombre').value,
      telefono: document.getElementById('telefono').value,
      email:    document.getElementById('email').value,
      fecha:    document.getElementById('fecha').value,
      hora:     document.getElementById('hora').value,
      personas: personasSeleccionadas,
      ocasion:  document.getElementById('ocasion').value,
      notas:    document.getElementById('notas').value,
    })
  })
  .then(r => r.json())
  .then(data => {
    btn.disabled = false;
    text.textContent = 'Solicitar Reservación';
    spin.style.display = 'none';
    if (data.success) {
      document.getElementById('successOverlay').classList.add('show');
    } else {
      alert(data.message || 'Error al enviar. Intenta de nuevo.');
    }
  })
  .catch(() => {
    btn.disabled = false;
    text.textContent = 'Solicitar Reservación';
    spin.style.display = 'none';
    alert('Error de conexión. Intenta de nuevo.');
  });
}

function closeSuccess() {
  document.getElementById('successOverlay').classList.remove('show');
  document.getElementById('reservaForm').reset();
  document.querySelectorAll('.persona-btn').forEach(b => b.classList.remove('active'));
  personasSeleccionadas = null;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Scroll reveal
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) e.target.classList.add('visible'); });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    e.preventDefault();
    const t = document.querySelector(a.getAttribute('href'));
    if (t) t.scrollIntoView({ behavior: 'smooth' });
  });
});

// Responsive about grid
function checkGrid() {
  const g = document.querySelector('.about-grid');
  if (g) g.style.gridTemplateColumns = window.innerWidth < 768 ? '1fr' : '1fr 1fr';
}
checkGrid();
window.addEventListener('resize', checkGrid);
</script>
</body>
</html><?php /**PATH C:\Users\Ryzen Gaming\Documents\aaa\SISTEMA_RESTAURANTE\restaurante-laravel\resources\views/landing/reservaciones.blade.php ENDPATH**/ ?>