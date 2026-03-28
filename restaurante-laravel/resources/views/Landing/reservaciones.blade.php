<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reservaciones — {{ $config->nombre_negocio }}</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
  :root {
    --cream: #F5F0E8;
    --warm-white: #FDFAF4;
    --terracotta: #C85B3A;
    --terracotta-dark: #A04428;
    --terracotta-light: #E8876A;
    --gold: #C9A84C;
    --gold-light: #E8C97A;
    --charcoal: #1A1612;
    --brown: #3D2B1F;
    --brown-mid: #6B4C38;
    --sage: #7A8C6E;
  }

  * { box-sizing: border-box; margin: 0; padding: 0; }

  body {
    font-family: 'DM Sans', sans-serif;
    background-color: var(--charcoal);
    color: var(--cream);
    overflow-x: hidden;
  }

  /* GRAIN OVERLAY */
  body::before {
    content: '';
    position: fixed;
    inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
    pointer-events: none;
    z-index: 9999;
    opacity: 0.4;
  }

  /* HERO */
  .hero {
    min-height: 100vh;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    overflow: hidden;
  }

  .hero-bg {
    position: absolute;
    inset: 0;
    background:
      radial-gradient(ellipse 80% 60% at 50% 0%, rgba(200,91,58,0.15) 0%, transparent 60%),
      radial-gradient(ellipse 60% 40% at 80% 80%, rgba(201,168,76,0.08) 0%, transparent 50%),
      linear-gradient(160deg, #1A1612 0%, #2A1E16 50%, #1A1612 100%);
  }

  .hero-circle {
    position: absolute;
    border-radius: 50%;
    border: 1px solid rgba(201,168,76,0.12);
    animation: slowRotate 30s linear infinite;
  }

  @keyframes slowRotate {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }

  .hero-circle-1 { width: 600px; height: 600px; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-duration: 40s; }
  .hero-circle-2 { width: 900px; height: 900px; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-duration: 60s; animation-direction: reverse; }
  .hero-circle-3 { width: 1200px; height: 1200px; top: 50%; left: 50%; transform: translate(-50%,-50%); animation-duration: 80s; }

  .divider-ornament {
    display: flex;
    align-items: center;
    gap: 16px;
    color: var(--gold);
    opacity: 0.7;
  }
  .divider-ornament::before,
  .divider-ornament::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--gold), transparent);
    max-width: 120px;
  }

  /* ANIMATIONS */
  @keyframes fadeUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  @keyframes shimmer {
    0%, 100% { opacity: 0.6; }
    50% { opacity: 1; }
  }

  .animate-fade-up { animation: fadeUp 0.9s ease forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.15s; }
  .delay-2 { animation-delay: 0.3s; }
  .delay-3 { animation-delay: 0.45s; }
  .delay-4 { animation-delay: 0.6s; }
  .delay-5 { animation-delay: 0.75s; }
  .delay-6 { animation-delay: 0.9s; }

  /* NAV */
  nav {
    position: fixed;
    top: 0; left: 0; right: 0;
    z-index: 100;
    padding: 24px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to bottom, rgba(26,22,18,0.95), transparent);
    backdrop-filter: blur(8px);
  }

  .nav-logo {
    font-family: 'Playfair Display', serif;
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--cream);
    letter-spacing: 0.05em;
  }

  .nav-logo span { color: var(--terracotta); }

  .nav-links a {
    font-size: 0.8rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(245,240,232,0.6);
    text-decoration: none;
    margin-left: 32px;
    transition: color 0.3s;
  }
  .nav-links a:hover { color: var(--gold); }

  /* FORM CARD */
  .form-card {
    background: rgba(26, 22, 18, 0.7);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(201,168,76,0.2);
    border-radius: 2px;
    padding: 48px;
    position: relative;
  }

  .form-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 2px;
    background: linear-gradient(135deg, rgba(201,168,76,0.05) 0%, transparent 50%);
    pointer-events: none;
  }

  /* Corner decorations */
  .corner-tl, .corner-br {
    position: absolute;
    width: 24px;
    height: 24px;
    border-color: var(--gold);
    border-style: solid;
    opacity: 0.5;
  }
  .corner-tl { top: -1px; left: -1px; border-width: 2px 0 0 2px; }
  .corner-br { bottom: -1px; right: -1px; border-width: 0 2px 2px 0; }

  /* FORM INPUTS */
  .form-group { position: relative; }

  .form-input {
    width: 100%;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(201,168,76,0.2);
    border-radius: 2px;
    padding: 16px 20px;
    color: var(--cream);
    font-family: 'DM Sans', sans-serif;
    font-size: 0.95rem;
    transition: all 0.3s;
    outline: none;
    -webkit-appearance: none;
  }

  .form-input:focus {
    border-color: var(--gold);
    background: rgba(201,168,76,0.05);
    box-shadow: 0 0 0 3px rgba(201,168,76,0.08);
  }

  .form-input::placeholder { color: rgba(245,240,232,0.3); }

  .form-label {
    display: block;
    font-size: 0.7rem;
    letter-spacing: 0.15em;
    text-transform: uppercase;
    color: rgba(245,240,232,0.5);
    margin-bottom: 8px;
    font-weight: 500;
  }

  select.form-input option {
    background: var(--charcoal);
    color: var(--cream);
  }

  /* BUTTON */
  .btn-primary {
    background: linear-gradient(135deg, var(--terracotta) 0%, var(--terracotta-dark) 100%);
    color: white;
    border: none;
    padding: 18px 40px;
    font-family: 'DM Sans', sans-serif;
    font-size: 0.8rem;
    font-weight: 500;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
    border-radius: 2px;
  }

  .btn-primary::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 100%);
    opacity: 0;
    transition: opacity 0.3s;
  }

  .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 40px rgba(200,91,58,0.4); }
  .btn-primary:hover::before { opacity: 1; }
  .btn-primary:active { transform: translateY(0); }

  /* FEATURES */
  .feature-card {
    border: 1px solid rgba(201,168,76,0.1);
    border-radius: 2px;
    padding: 32px;
    background: rgba(255,255,255,0.02);
    transition: all 0.4s;
    position: relative;
    overflow: hidden;
  }

  .feature-card::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 2px;
    background: linear-gradient(90deg, var(--terracotta), var(--gold));
    transform: scaleX(0);
    transition: transform 0.4s;
  }

  .feature-card:hover { background: rgba(201,168,76,0.04); transform: translateY(-4px); }
  .feature-card:hover::after { transform: scaleX(1); }

  /* SCROLL REVEAL */
  .reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }
  .reveal.visible {
    opacity: 1;
    transform: translateY(0);
  }

  /* SUCCESS STATE */
  .success-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(26,22,18,0.95);
    z-index: 200;
    justify-content: center;
    align-items: center;
    flex-direction: column;
  }
  .success-overlay.show { display: flex; animation: fadeIn 0.5s ease; }

  /* MOBILE */
  @media (max-width: 768px) {
    nav { padding: 20px 24px; }
    .nav-links { display: none; }
    .form-card { padding: 32px 24px; }
  }

  /* LOADING SPINNER */
  .spinner {
    width: 20px; height: 20px;
    border: 2px solid rgba(255,255,255,0.3);
    border-top-color: white;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    display: none;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  /* FLOATING ELEMENTS */
  @keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    33% { transform: translateY(-12px) rotate(2deg); }
    66% { transform: translateY(-6px) rotate(-1deg); }
  }

  .float-el { animation: float 8s ease-in-out infinite; }
  .float-el-2 { animation: float 10s ease-in-out infinite; animation-delay: -3s; }
  .float-el-3 { animation: float 12s ease-in-out infinite; animation-delay: -6s; }
</style>
</head>
<body>

<!-- NAV -->
<nav>
  <div class="nav-logo">{{ $config->nombre_negocio }}</div>
  <div class="nav-links">
    <a href="#reservar">Reservar</a>
    <a href="#nosotros">Nosotros</a>
    <a href="#experiencia">Experiencia</a>
    <a href="tel:+50412345678">{{ $config->telefono }}</a>
  </div>
  <!-- Mobile menu button -->
  <button onclick="toggleMenu()" class="md:hidden text-cream" style="background:none;border:none;cursor:pointer;color:var(--cream);">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
  </button>
</nav>

<!-- MOBILE MENU -->
<div id="mobileMenu" style="display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(26,22,18,0.98);z-index:99;justify-content:center;align-items:center;flex-direction:column;gap:32px;">
  <button onclick="toggleMenu()" style="position:absolute;top:24px;right:24px;background:none;border:none;cursor:pointer;color:var(--cream);">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
      <path d="M6 18L18 6M6 6l12 12"/>
    </svg>
  </button>
  <a href="#reservar" onclick="toggleMenu()" style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--cream);text-decoration:none;">Reservar</a>
  <a href="#nosotros" onclick="toggleMenu()" style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--cream);text-decoration:none;">Nosotros</a>
  <a href="#experiencia" onclick="toggleMenu()" style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--cream);text-decoration:none;">Experiencia</a>
  <a href="tel:+50412345678" style="font-size:0.85rem;letter-spacing:0.15em;color:var(--gold);text-decoration:none;">+504 1234-5678</a>
</div>

<!-- HERO -->
<section class="hero" id="inicio">
  <div class="hero-bg"></div>
  <div class="hero-circle hero-circle-1"></div>
  <div class="hero-circle hero-circle-2"></div>
  <div class="hero-circle hero-circle-3"></div>

  <!-- Floating decorative elements -->
  <div class="float-el" style="position:absolute;top:20%;left:8%;opacity:0.12;font-size:5rem;">✦</div>
  <div class="float-el-2" style="position:absolute;top:60%;right:6%;opacity:0.08;font-size:8rem;">◈</div>
  <div class="float-el-3" style="position:absolute;bottom:20%;left:12%;opacity:0.07;font-size:4rem;">❧</div>

  <div style="position:relative;z-index:10;text-align:center;padding:0 24px;max-width:800px;">
    <p class="animate-fade-up delay-1" style="font-size:0.7rem;letter-spacing:0.35em;text-transform:uppercase;color:var(--gold);margin-bottom:24px;font-weight:500;">
      ✦ &nbsp; Bienvenido a nuestra mesa &nbsp; ✦
    </p>

    <h1 class="animate-fade-up delay-2" style="font-family:'Playfair Display',serif;font-size:clamp(3.5rem,10vw,7rem);font-weight:900;line-height:0.95;margin-bottom:32px;color:var(--cream);">
      Una experiencia<br>
      <em style="color:var(--terracotta);font-style:italic;">inolvidable</em>
    </h1>

    <div class="animate-fade-up delay-3 divider-ornament" style="margin:0 auto 32px;max-width:300px;">
      <span style="font-family:'Playfair Display',serif;font-size:1.1rem;font-style:italic;white-space:nowrap;">Reserva tu lugar</span>
    </div>

    <p class="animate-fade-up delay-4" style="font-family:'Cormorant Garamond',serif;font-size:1.25rem;font-weight:300;color:rgba(245,240,232,0.65);line-height:1.8;margin-bottom:48px;font-style:italic;">
      Donde cada platillo cuenta una historia y cada momento<br class="hidden md:block"> se convierte en un recuerdo para siempre.
    </p>

    <div class="animate-fade-up delay-5" style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;">
      <a href="#reservar" class="btn-primary" style="text-decoration:none;display:inline-block;">
        Reservar Mesa
      </a>
      <a href="#nosotros" style="display:inline-block;padding:18px 40px;border:1px solid rgba(201,168,76,0.3);color:var(--gold);text-decoration:none;font-size:0.8rem;letter-spacing:0.2em;text-transform:uppercase;transition:all 0.3s;border-radius:2px;">
        Conocernos
      </a>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="animate-fade-up delay-6" style="position:absolute;bottom:40px;left:50%;transform:translateX(-50%);display:flex;flex-direction:column;align-items:center;gap:8px;opacity:0.4;">
    <span style="font-size:0.65rem;letter-spacing:0.2em;text-transform:uppercase;">Scroll</span>
    <div style="width:1px;height:48px;background:linear-gradient(to bottom,var(--gold),transparent);animation:shimmer 2s ease-in-out infinite;"></div>
  </div>
</section>

<!-- STATS BAR -->
<section style="background:rgba(201,168,76,0.08);border-top:1px solid rgba(201,168,76,0.15);border-bottom:1px solid rgba(201,168,76,0.15);padding:32px 24px;">
  <div style="max-width:900px;margin:0 auto;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:32px;text-align:center;">
    <div class="reveal">
      <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold);">12+</div>
      <div style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(245,240,232,0.5);margin-top:4px;">Años de experiencia</div>
    </div>
    <div class="reveal" style="transition-delay:0.1s">
      <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold);">4,800+</div>
      <div style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(245,240,232,0.5);margin-top:4px;">Comensales felices</div>
    </div>
    <div class="reveal" style="transition-delay:0.2s">
      <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold);">85+</div>
      <div style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(245,240,232,0.5);margin-top:4px;">Platillos únicos</div>
    </div>
    <div class="reveal" style="transition-delay:0.3s">
      <div style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--gold);">⭐ 4.9</div>
      <div style="font-size:0.7rem;letter-spacing:0.15em;text-transform:uppercase;color:rgba(245,240,232,0.5);margin-top:4px;">Calificación promedio</div>
    </div>
  </div>
</section>

<!-- NOSOTROS -->
<section id="nosotros" style="padding:120px 24px;max-width:1100px;margin:0 auto;">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:80px;align-items:center;" class="reveal">
    <!-- Text -->
    <div>
      <p style="font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:var(--terracotta);margin-bottom:20px;font-weight:500;">Nuestra historia</p>
      <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:700;line-height:1.1;margin-bottom:32px;color:var(--cream);">
        Cocina con<br><em style="color:var(--gold);font-style:italic;">alma y tradición</em>
      </h2>
      <p style="font-family:'Cormorant Garamond',serif;font-size:1.15rem;line-height:1.9;color:rgba(245,240,232,0.7);margin-bottom:24px;font-weight:300;">
        Cada receta nace de generaciones de sabor, de manos que conocen el arte de transformar ingredientes frescos en momentos que perduran en la memoria.
      </p>
      <p style="font-size:0.9rem;line-height:1.8;color:rgba(245,240,232,0.55);margin-bottom:40px;">
        Nuestro equipo de chefs selecciona los mejores ingredientes de temporada para ofrecerte una experiencia gastronómica auténtica, donde cada visita es única e irrepetible.
      </p>
      <div style="display:flex;gap:32px;">
        <div>
          <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--terracotta);">Lun–Vie</div>
          <div style="font-size:0.75rem;letter-spacing:0.1em;color:rgba(245,240,232,0.4);text-transform:uppercase;margin-top:2px;">12pm – 10pm</div>
        </div>
        <div style="width:1px;background:rgba(201,168,76,0.2);"></div>
        <div>
          <div style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--terracotta);">Sáb–Dom</div>
          <div style="font-size:0.75rem;letter-spacing:0.1em;color:rgba(245,240,232,0.4);text-transform:uppercase;margin-top:2px;">11am – 11pm</div>
        </div>
      </div>
    </div>

    <!-- Visual -->
    <div style="position:relative;">
      <div style="aspect-ratio:3/4;background:linear-gradient(135deg,rgba(200,91,58,0.15),rgba(201,168,76,0.1));border:1px solid rgba(201,168,76,0.2);border-radius:2px;display:flex;align-items:center;justify-content:center;position:relative;overflow:hidden;">
        <!-- Decorative pattern -->
        <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(201,168,76,0.03) 0px,rgba(201,168,76,0.03) 1px,transparent 1px,transparent 20px);"></div>
        <div style="text-align:center;padding:40px;position:relative;z-index:1;">
          <div style="font-size:5rem;margin-bottom:24px;">🍽️</div>
          <p style="font-family:'Playfair Display',serif;font-size:1.3rem;font-style:italic;color:var(--gold);opacity:0.8;">"La buena comida es la base de la felicidad genuina"</p>
          <p style="font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;color:rgba(245,240,232,0.3);margin-top:16px;">— Auguste Escoffier</p>
        </div>
      </div>
      <!-- Decorative offset box -->
      <div style="position:absolute;top:-16px;right:-16px;width:100%;height:100%;border:1px solid rgba(201,168,76,0.1);border-radius:2px;z-index:-1;"></div>
    </div>
  </div>
</section>

<!-- EXPERIENCIA -->
<section id="experiencia" style="padding:80px 24px;background:rgba(255,255,255,0.02);border-top:1px solid rgba(201,168,76,0.08);">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:64px;" class="reveal">
      <p style="font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:var(--terracotta);margin-bottom:16px;font-weight:500;">Por qué elegirnos</p>
      <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3rem);font-weight:700;color:var(--cream);">La experiencia <em style="color:var(--gold);font-style:italic;">completa</em></h2>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;">
      <div class="feature-card reveal" style="transition-delay:0s">
        <div style="font-size:2rem;margin-bottom:20px;">🌿</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:600;color:var(--cream);margin-bottom:12px;">Ingredientes frescos</h3>
        <p style="font-size:0.9rem;line-height:1.7;color:rgba(245,240,232,0.55);">Seleccionamos cada ingrediente con cuidado, priorizando productores locales y de temporada para garantizar frescura y sabor en cada platillo.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.1s">
        <div style="font-size:2rem;margin-bottom:20px;">👨‍🍳</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:600;color:var(--cream);margin-bottom:12px;">Chefs expertos</h3>
        <p style="font-size:0.9rem;line-height:1.7;color:rgba(245,240,232,0.55);">Nuestro equipo culinario combina técnicas tradicionales con innovación moderna, creando platos que sorprenden y deleitan en cada visita.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.2s">
        <div style="font-size:2rem;margin-bottom:20px;">🕯️</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:600;color:var(--cream);margin-bottom:12px;">Ambiente íntimo</h3>
        <p style="font-size:0.9rem;line-height:1.7;color:rgba(245,240,232,0.55);">Cada rincón del restaurante está diseñado para crear momentos especiales, con una atmósfera cálida y acogedora perfecta para cualquier ocasión.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:0.3s">
        <div style="font-size:2rem;margin-bottom:20px;">🍷</div>
        <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:600;color:var(--cream);margin-bottom:12px;">Carta de bebidas</h3>
        <p style="font-size:0.9rem;line-height:1.7;color:rgba(245,240,232,0.55);">Una cuidada selección de vinos, cócteles artesanales y bebidas de autor que complementan perfectamente cada momento de tu experiencia.</p>
      </div>
    </div>
  </div>
</section>

<!-- RESERVATION FORM -->
<section id="reservar" style="padding:120px 24px;">
  <div style="max-width:720px;margin:0 auto;">
    <div style="text-align:center;margin-bottom:56px;" class="reveal">
      <p style="font-size:0.7rem;letter-spacing:0.25em;text-transform:uppercase;color:var(--terracotta);margin-bottom:16px;font-weight:500;">Asegura tu lugar</p>
      <h2 style="font-family:'Playfair Display',serif;font-size:clamp(2.5rem,6vw,4rem);font-weight:700;color:var(--cream);margin-bottom:16px;">
        Haz tu <em style="color:var(--gold);font-style:italic;">reservación</em>
      </h2>
      <p style="font-family:'Cormorant Garamond',serif;font-size:1.1rem;color:rgba(245,240,232,0.5);font-style:italic;">Completa el formulario y nos pondremos en contacto contigo para confirmar.</p>
    </div>

    <div class="form-card reveal">
      <div class="corner-tl"></div>
      <div class="corner-br"></div>

      <form id="reservaForm" onsubmit="enviarReserva(event)">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
          <div class="form-group">
            <label class="form-label">Nombre completo *</label>
            <input type="text" class="form-input" placeholder="Tu nombre" required id="nombre">
          </div>
          <div class="form-group">
            <label class="form-label">Teléfono *</label>
            <input type="tel" class="form-input" placeholder="+504 0000-0000" required id="telefono">
          </div>
        </div>

        <div style="margin-bottom:24px;">
          <label class="form-label">Correo electrónico</label>
          <input type="email" class="form-input" placeholder="tu@email.com" id="email">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;margin-bottom:24px;">
          <div class="form-group">
            <label class="form-label">Fecha *</label>
            <input type="date" class="form-input" required id="fecha" style="color-scheme:dark;">
          </div>
          <div class="form-group">
            <label class="form-label">Hora *</label>
            <select class="form-input" required id="hora">
              <option value="" disabled selected>Selecciona una hora</option>
              <option>12:00 pm</option>
              <option>12:30 pm</option>
              <option>1:00 pm</option>
              <option>1:30 pm</option>
              <option>2:00 pm</option>
              <option>7:00 pm</option>
              <option>7:30 pm</option>
              <option>8:00 pm</option>
              <option>8:30 pm</option>
              <option>9:00 pm</option>
              <option>9:30 pm</option>
            </select>
          </div>
        </div>

        <div style="margin-bottom:24px;">
          <label class="form-label">Número de personas *</label>
          <div style="display:flex;gap:12px;flex-wrap:wrap;">
            <label style="cursor:pointer;" onclick="selectPersonas(this,1)">
              <input type="radio" name="personas" value="1" style="display:none" required>
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">1</span>
            </label>
            <label style="cursor:pointer;" onclick="selectPersonas(this,2)">
              <input type="radio" name="personas" value="2" style="display:none">
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">2</span>
            </label>
            <label style="cursor:pointer;" onclick="selectPersonas(this,3)">
              <input type="radio" name="personas" value="3" style="display:none">
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">3</span>
            </label>
            <label style="cursor:pointer;" onclick="selectPersonas(this,4)">
              <input type="radio" name="personas" value="4" style="display:none">
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">4</span>
            </label>
            <label style="cursor:pointer;" onclick="selectPersonas(this,5)">
              <input type="radio" name="personas" value="5" style="display:none">
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">5</span>
            </label>
            <label style="cursor:pointer;" onclick="selectPersonas(this,6)">
              <input type="radio" name="personas" value="6" style="display:none">
              <span class="personas-btn" style="display:inline-block;padding:12px 20px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;font-size:0.85rem;transition:all 0.2s;color:rgba(245,240,232,0.6);">6+</span>
            </label>
          </div>
        </div>

        <div style="margin-bottom:32px;">
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

        <div style="margin-bottom:32px;">
          <label class="form-label">Peticiones especiales</label>
          <textarea class="form-input" placeholder="Alergias, decoración especial, preferencias de mesa..." rows="3" id="notas" style="resize:vertical;min-height:90px;"></textarea>
        </div>

        <button type="submit" class="btn-primary" style="width:100%;display:flex;align-items:center;justify-content:center;gap:12px;" id="submitBtn">
          <span id="btnText">Solicitar Reservación</span>
          <div class="spinner" id="spinner"></div>
        </button>

        <p style="text-align:center;font-size:0.75rem;color:rgba(245,240,232,0.3);margin-top:20px;line-height:1.6;">
          Al enviar confirmas que has leído nuestras políticas de reservación.<br>
          Te confirmaremos por teléfono dentro de las próximas 2 horas.
        </p>
      </form>
    </div>
  </div>
</section>

<!-- CONTACT / FOOTER -->
<footer style="border-top:1px solid rgba(201,168,76,0.12);padding:80px 24px 40px;">
  <div style="max-width:1100px;margin:0 auto;">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:48px;margin-bottom:64px;">
      <div>
        <div style="font-family:'Playfair Display',serif;...">{{ $config->nombre_negocio }}</div>
        <p style="font-size:0.85rem;line-height:1.8;color:rgba(245,240,232,0.45);">Una experiencia gastronómica única donde cada visita se convierte en un recuerdo especial.</p>
      </div>
      <div>
        <h4 style="font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;opacity:0.8;">Horarios</h4>
        <div style="font-size:0.85rem;color:rgba(245,240,232,0.5);line-height:2;">
          <p>Lunes – Viernes &nbsp; 12pm – 10pm</p>
          <p>Sábado – Domingo &nbsp; 11am – 11pm</p>
        </div>
      </div>
      <div>
        <h4 style="font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;opacity:0.8;">Contacto</h4>
        <div style="font-size:0.85rem;color:rgba(245,240,232,0.5);line-height:2;">
          <p>{{ $config->telefono }}</p>
          <p>✉️ reservas@lamesa.hn</p>
          <p>📍 Col. Palmira, Tegucigalpa</p>
        </div>
      </div>
      <div>
        <h4 style="font-size:0.7rem;letter-spacing:0.2em;text-transform:uppercase;color:var(--gold);margin-bottom:20px;opacity:0.8;">Síguenos</h4>
        <div style="display:flex;gap:16px;">
          <a href="#" style="width:40px;height:40px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1rem;transition:all 0.3s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(201,168,76,0.2)'">📘</a>
          <a href="#" style="width:40px;height:40px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1rem;transition:all 0.3s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(201,168,76,0.2)'">📸</a>
          <a href="#" style="width:40px;height:40px;border:1px solid rgba(201,168,76,0.2);border-radius:2px;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1rem;transition:all 0.3s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='rgba(201,168,76,0.2)'">🐦</a>
        </div>
      </div>
    </div>

    <div style="border-top:1px solid rgba(201,168,76,0.08);padding-top:32px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:16px;">
      <p style="font-size:0.75rem;color:rgba(245,240,232,0.25);">© 2026 LaMesa. Todos los derechos reservados.</p>
      <p style="font-size:0.75rem;color:rgba(245,240,232,0.25);">Hecho con ❤️ en Honduras</p>
    </div>
  </div>
</footer>

<!-- SUCCESS OVERLAY -->
<div class="success-overlay" id="successOverlay">
  <div style="text-align:center;padding:40px;max-width:480px;">
    <div style="font-size:4rem;margin-bottom:24px;animation:float 3s ease-in-out infinite;">✨</div>
    <h2 style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:var(--cream);margin-bottom:16px;">¡Reservación enviada!</h2>
    <div class="divider-ornament" style="margin:0 auto 24px;max-width:200px;">
      <span style="font-family:'Playfair Display',serif;font-style:italic;font-size:0.9rem;white-space:nowrap;">Te esperamos</span>
    </div>
    <p style="font-size:0.95rem;line-height:1.8;color:rgba(245,240,232,0.6);margin-bottom:40px;">
      Hemos recibido tu solicitud. Nuestro equipo se pondrá en contacto contigo en las próximas <strong style="color:var(--gold)">2 horas</strong> para confirmar tu reservación.
    </p>
    <button onclick="closeSuccess()" class="btn-primary">
      Volver al inicio
    </button>
  </div>
</div>

<script>
  // Set min date for reservation
  const fechaInput = document.getElementById('fecha');
  const today = new Date().toISOString().split('T')[0];
  fechaInput.min = today;
  fechaInput.value = today;

  // Mobile menu
  function toggleMenu() {
    const menu = document.getElementById('mobileMenu');
    menu.style.display = menu.style.display === 'flex' ? 'none' : 'flex';
  }

  // Personas selector
  function selectPersonas(label, num) {
    document.querySelectorAll('.personas-btn').forEach(btn => {
      btn.style.background = 'transparent';
      btn.style.borderColor = 'rgba(201,168,76,0.2)';
      btn.style.color = 'rgba(245,240,232,0.6)';
    });
    const span = label.querySelector('.personas-btn');
    span.style.background = 'rgba(200,91,58,0.2)';
    span.style.borderColor = 'var(--terracotta)';
    span.style.color = 'var(--cream)';
  }

  // Form submission
  function enviarReserva(e) {
    e.preventDefault();
    const btn = document.getElementById('submitBtn');
    const text = document.getElementById('btnText');
    const spinner = document.getElementById('spinner');

    btn.disabled = true;
    text.textContent = 'Enviando...';
    spinner.style.display = 'block';

    const personas = document.querySelector('input[name="personas"]:checked');

    fetch('{{ route("landing.store") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nombre:   document.getElementById('nombre').value,
            telefono: document.getElementById('telefono').value,
            email:    document.getElementById('email').value,
            fecha:    document.getElementById('fecha').value,
            hora:     document.getElementById('hora').value,
            personas: personas ? personas.value : 2,
            ocasion:  document.getElementById('ocasion').value,
            notas:    document.getElementById('notas').value,
        })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        text.textContent = 'Solicitar Reservación';
        spinner.style.display = 'none';
        if (data.success) {
            document.getElementById('successOverlay').classList.add('show');
        } else {
            alert('Error al enviar. Intenta de nuevo.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        text.textContent = 'Solicitar Reservación';
        spinner.style.display = 'none';
        alert('Error de conexión. Intenta de nuevo.');
    });
}

  function closeSuccess() {
    document.getElementById('successOverlay').classList.remove('show');
    document.getElementById('reservaForm').reset();
    document.querySelectorAll('.personas-btn').forEach(btn => {
      btn.style.background = 'transparent';
      btn.style.borderColor = 'rgba(201,168,76,0.2)';
      btn.style.color = 'rgba(245,240,232,0.6)';
    });
    window.scrollTo({ top: 0, behavior: 'smooth' });
  }

  // Scroll reveal
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

  // Smooth scrolling
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute('href'));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  // Responsive grid fix
  function checkGrid() {
    const w = window.innerWidth;
    const nosotrosGrid = document.querySelector('#nosotros > div');
    if (nosotrosGrid) {
      nosotrosGrid.style.gridTemplateColumns = w < 768 ? '1fr' : '1fr 1fr';
    }
  }
  checkGrid();
  window.addEventListener('resize', checkGrid);
</script>
</body>
</html>