<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="#0a1f52" />
<title>@yield('title')</title>
<meta name="description" content="@yield('description')" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
</head>
<body>

<div class="scroll-progress" id="scrollProgress"></div>

<!-- ===== Navigation ===== -->
<header class="nav nav--solid" id="nav">
  <div class="container nav__inner">
    <a href="/" class="brand" aria-label="Sparkman Solutions home">
      <img class="brand__logo" src="/images/logo.jpg" alt="Sparkman Solutions" />
    </a>
    <nav class="nav__links" id="navLinks">
      <a href="/services" class="is-active">Services</a>
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

<!-- ===== Page hero (dark banner) ===== -->
<section class="phero" id="home">
  <img class="phero__img" src="@yield('heroImg')" alt="" aria-hidden="true" />
  <div class="phero__inner">
    <nav class="phero__crumb" aria-label="Breadcrumb">
      <a href="/">Home</a> <i>/</i> <a href="/services">Services</a> <i>/</i> @yield('crumb')
    </nav>
    <h1 class="phero__title">@yield('heroTitle')</h1>
    <p class="phero__sub">@yield('heroSub')</p>
  </div>
</section>

<!-- ===== Qualifier CTA (replaces the old per-page lead form) ===== -->
<section class="section">
  <div class="container" style="max-width:720px;text-align:center">
    <span class="kicker">Get Started</span>
    <h2 class="section__title">See if you're <span class="txt-gold">funding-ready</span></h2>
    <p class="section__sub">Answer a few quick questions — free, 60 seconds. We'll tell you if you qualify for funding now, or get your credit funding-ready first.</p>
    <a href="/get-funded" class="btn btn--gold btn--lg" style="margin-top:22px">Check if I qualify →</a>
  </div>
</section>

@yield('content')

<!-- ===== Contact / CTA ===== -->
<section class="section contact" id="contact">
  <div class="contact__bg" aria-hidden="true"><span class="contact__glow"></span></div>
  <div class="container contact__inner reveal">
    <span class="kicker light">Let's Begin</span>
    <h2 class="section__title on-dark">Your Free Credit Analysis <span class="txt-gold">Starts Here</span></h2>
    <p class="on-dark contact__lead">Answer a few quick questions and a Sparkman strategist will reach out within one business day. No obligation, no pressure — just a clear plan for your credit.</p>
    <button class="btn btn--gold btn--lg contact__cta" data-open-form>Get My Free Analysis
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
    </button>
    <p class="contact__note">Free consultation · No obligation · Reply within 1 business day</p>
    <div class="contact__owner">
      <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
      <p>"I'll personally make sure you get a real plan."<br /><strong>— Javon Sparkman, Founder</strong></p>
    </div>
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
      <h4>Get Started</h4>
      <a href="#contact">Free Analysis</a>
      <a href="/faq">FAQ</a>
      <a href="/process">Our Process</a>
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

<button class="fab" id="fab" data-open-form>Free Analysis</button>

<!-- ===== Lead Popup Modal ===== -->
<div class="modal" id="leadModal" aria-hidden="true">
  <div class="modal__overlay" data-close></div>
  <div class="modal__dialog" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <button class="modal__close" data-close aria-label="Close form">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg>
    </button>
    <div class="modal__aside" aria-hidden="true">
      <span class="modal__stars">★★★★★</span>
      <h4>Your free plan is<br />one step away.</h4>
      <ul class="modal__perks">
        <li><span>✓</span> Free full credit analysis</li>
        <li><span>✓</span> A custom, no-obligation plan</li>
        <li><span>✓</span> A reply within 1 business day</li>
      </ul>
      <div class="modal__founder">
        <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
        <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
      </div>
    </div>
    <div class="modal__main">
      <div class="modal__head">
        <span class="modal__badge">Free Consultation</span>
        <h3 id="modalTitle">Start your free analysis</h3>
        <p>Tell us a bit about you — no cost, no obligation.</p>
      </div>
      <form class="modal__form lead-form" id="leadForm" novalidate>
        <div class="field">
          <input type="text" id="name" name="name" required placeholder=" " autocomplete="name" />
          <label for="name">Full Name</label>
        </div>
        <div class="field">
          <input type="email" id="email" name="email" required placeholder=" " autocomplete="email" />
          <label for="email">Email Address</label>
        </div>
        <div class="field">
          <input type="tel" id="phone" name="phone" required placeholder=" " autocomplete="tel" />
          <label for="phone">Phone Number</label>
        </div>
        <div class="field">
          <select id="goal" name="goal" required>
            <option value="" disabled selected></option>
            <option>Buy a home</option>
            <option>Buy / finance a car</option>
            <option>Qualify for funding</option>
            <option>Remove negative items</option>
            <option>Build business credit</option>
            <option>Just improve my score</option>
          </select>
          <label for="goal" class="label--select">My main goal is to…</label>
        </div>
        <div class="field">
          <textarea id="message" name="message" rows="2" placeholder=" "></textarea>
          <label for="message">Tell us about your situation (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Get My Free Analysis</button>
        <p class="form__disclaimer">🔒 Your info is secure. We never sell your data.</p>
        <div class="form__success" id="formSuccess" hidden>
          <span>✓</span> Thank you! Your request is in — we'll reach out within one business day.
        </div>
      </form>
    </div>
  </div>
</div>

<script src="/script.js"></script>
</body>
</html>
