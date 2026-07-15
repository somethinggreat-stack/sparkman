<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="#0a1f52" />
<title>@yield('title') — Sparkman Solutions</title>
<meta name="description" content="@yield('title') for Sparkman Solutions credit restoration &amp; consulting services." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
</head>
<body>

<div class="scroll-progress" id="scrollProgress"></div>

<header class="nav nav--solid" id="nav">
  <div class="container nav__inner">
    <a href="/" class="brand" aria-label="Sparkman Solutions home">
      <img class="brand__logo" src="/images/logo.jpg" alt="Sparkman Solutions" />
    </a>
    <nav class="nav__links" id="navLinks">
      <a href="/services">Services</a>
      <a href="/process">Process</a>
      <a href="/funding">Funding</a>
      <a href="/about">About</a>
      <a href="/pricing">Pricing</a>
      <a href="/faq">FAQ</a>
    </nav>
    <div class="nav__cta">
      <a href="{{ config('services.ghl.calendar_url') ?: '/get-funded' }}" target="_blank" rel="noopener" class="btn btn--gold">Free Consultation</a>
    </div>
    <button class="nav__toggle" id="navToggle" aria-label="Open menu">
      <span></span><span></span><span></span>
    </button>
  </div>
</header>

<section class="legal-hero">
  <div class="container">
    <nav class="phero__crumb" aria-label="Breadcrumb" style="color:var(--slate)">
      <a href="/" style="color:var(--slate)">Home</a> <i style="color:var(--gold-3)">/</i> @yield('title')
    </nav>
    <h1>@yield('title')</h1>
    <p class="legal-hero__meta">Last updated: {{ config('app.legal_updated', 'July 2026') }}</p>
  </div>
</section>

<section class="section legal">
  <div class="container legal__wrap">
    @yield('content')
  </div>
</section>

<!-- ===== Footer ===== -->
<footer class="footer">
  <div class="container footer__grid">
    <div class="footer__brand">
      <a href="/" class="brand brand--footer">
        <img class="footer__logo" src="/images/logo-white.png" alt="Sparkman Solutions" />
      </a>
      <p>Premium credit restoration &amp; financial freedom. Your future, unlocked.</p>
    </div>
    <div class="footer__col">
      <h4>Company</h4>
      <a href="/about">About</a>
      <a href="/services">Services</a>
      <a href="/#results">Why Us</a>
      <a href="/pricing">Pricing</a>
    </div>
    <div class="footer__col">
      <h4>Legal</h4>
      <a href="/terms">Terms of Service</a>
      <a href="/privacy">Privacy Policy</a>
      <a href="/disclaimer">Disclaimer</a>
    </div>
    <div class="footer__col">
      <h4>Contact</h4>
      <a href="mailto:hello@sparkmansolutions.com">hello@sparkmansolutions.com</a>
      <a href="tel:+10000000000">Call / Text us</a>
      <p class="footer__small">Serving clients nationwide</p>
    </div>
  </div>
  <div class="container footer__bottom">
    <p>© <span id="year"></span> Sparkman Solutions. All rights reserved.</p>
    <p class="footer__legal"><a href="/terms">Terms of Service</a> · <a href="/privacy">Privacy Policy</a> · <a href="/disclaimer">Disclaimer</a></p>
    <p class="footer__fine">Sparkman Solutions provides credit consulting services and does not guarantee specific results. Results vary by individual.</p>
  </div>
</footer>

<script src="/script.js"></script>
</body>
</html>
