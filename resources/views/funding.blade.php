<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="#0a1f52" />
<title>Business &amp; Personal Funding — Sparkman Solutions</title>
<meta name="description" content="Get funded fast with Sparkman Solutions. Business term loans, lines of credit, working capital, and startup funding from $5K to $500K — apply in minutes." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<link rel="stylesheet" href="/funding.css" />
</head>
<body data-no-autopopup>

<!-- ===== Progress bar ===== -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- ===== Navigation ===== -->
<header class="nav" id="nav">
  <div class="container nav__inner">
    <a href="/" class="brand" aria-label="Sparkman Solutions home">
      <img class="brand__logo" src="/images/logo.jpg" alt="Sparkman Solutions" />
    </a>
    <nav class="nav__links" id="navLinks">
      <a href="/services">Services</a>
      <a href="/process">Process</a>
      <a href="/funding" class="is-active">Funding</a>
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

<!-- ===== Hero (light, split) ===== -->
<section class="hero" id="home">
  <div class="hero__bg" aria-hidden="true">
    <span class="hero__blob hero__blob--a"></span>
    <span class="hero__blob hero__blob--b"></span>
    <span class="hero__dots"></span>
  </div>

  <div class="container hero__inner">
    <div class="hero__copy">
      <span class="hero__badge reveal"><span class="hero__stars">★</span> $18M+ funded for clients &amp; businesses</span>
      <h1 class="hero__title reveal">Capital that moves<br /><span class="txt-gold">as fast as you do.</span></h1>
      <p class="hero__lead reveal">
        Business &amp; personal funding from <b>$5,000 to $500,000</b> — a simple
        application, real human guidance, and approvals in as little as 24–48 hours.
        Strong credit or still rebuilding, we find your best option.
      </p>
      <div class="hero__actions reveal">
        <a href="/get-funded" class="btn btn--gold btn--lg">Apply in Minutes
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="#options" class="btn btn--outline btn--lg">See Options</a>
      </div>
    </div>

    <div class="hero__media reveal">
      <div class="hero__frame">
        <img src="/images/3.jpeg" alt="Sparkman Solutions funding specialist" />
      </div>
    </div>
  </div>
</section>

<!-- ===== Qualifier CTA (replaces the old apply form) ===== -->
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container" style="max-width:760px;text-align:center">
    <div class="section__head reveal">
      <span class="kicker">Apply Now</span>
      <h2 class="section__title">See How Much You <span class="txt-gold">Qualify For</span></h2>
      <p class="section__sub">Answer a few quick questions — free, 60 seconds, no impact to your credit. We'll tell you if you're funding-ready now or get your credit funding-ready first.</p>
    </div>
    <div class="reveal" style="margin-top:24px">
      <a href="/get-funded" class="btn btn--gold btn--lg">Check if I qualify →</a>
    </div>
    <p class="reveal" style="margin-top:16px;color:var(--slate);font-size:.9rem">🔒 Secure &amp; confidential · $5K–$500K · approvals in 24–48 hours</p>
  </div>
</section>

<!-- ===== Funding Options ===== -->
<section class="section services" id="options">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Funding Options</span>
      <h2 class="section__title">Capital Built Around <span class="txt-gold">Your Goals</span></h2>
      <p class="section__sub">One application, multiple options. We match you to the funding that fits your situation — not a one-size-fits-all product.</p>
    </div>

    <div class="cards">
      <article class="card reveal">
        <div class="card__icon">🏦</div>
        <h3>Business Term Loans</h3>
        <p>Lump-sum capital with clear, fixed terms — perfect for expansion, hiring, or big moves.</p>
        <span class="card__line"></span>
      </article>
      <article class="card card--feature reveal">
        <div class="card__icon">💳</div>
        <h3>Business Line of Credit</h3>
        <p>Flexible, revolving funds you draw as needed and only pay for what you use.</p>
        <span class="card__line"></span>
      </article>
      <article class="card reveal">
        <div class="card__icon">⚡</div>
        <h3>Working Capital</h3>
        <p>Fast cash to smooth out cash flow, cover payroll, or seize a time-sensitive opportunity.</p>
        <span class="card__line"></span>
      </article>
      <article class="card reveal">
        <div class="card__icon">🚜</div>
        <h3>Equipment Financing</h3>
        <p>Fund the tools, vehicles, and machines that grow your business — the equipment secures itself.</p>
        <span class="card__line"></span>
      </article>
      <article class="card reveal">
        <div class="card__icon">🚀</div>
        <h3>Startup Funding</h3>
        <p>Early-stage capital and 0% intro options to launch strong — even with a young business.</p>
        <span class="card__line"></span>
      </article>
      <article class="card reveal">
        <div class="card__icon">🏠</div>
        <h3>Personal Funding</h3>
        <p>Personal loans and credit lines for debt consolidation, big purchases, or a fresh start.</p>
        <span class="card__line"></span>
      </article>
    </div>
  </div>
</section>

<!-- ===== How it works ===== -->
<section class="section process">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">The Process</span>
      <h2 class="section__title">Funded in <span class="txt-gold">Four Simple Steps</span></h2>
      <p class="section__sub">No mountains of paperwork. No runaround. Just a clear path to capital.</p>
    </div>

    <div class="steps">
      <article class="pstep reveal">
        <span class="pstep__ghost">01</span>
        <div class="pstep__icon">📝</div>
        <h3>Apply in Minutes</h3>
        <p>Complete a short, secure application — no impact to your credit to check options.</p>
        <span class="pstep__tag">3 minutes</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">02</span>
        <div class="pstep__icon">🤝</div>
        <h3>Get Matched</h3>
        <p>We review your profile and match you to the strongest funding options available.</p>
        <span class="pstep__tag">Same day</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">03</span>
        <div class="pstep__icon">✅</div>
        <h3>Get Approved</h3>
        <p>Review clear terms with a real strategist — no hidden fees, no surprises.</p>
        <span class="pstep__tag">24–48 hrs</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">04</span>
        <div class="pstep__icon">💰</div>
        <h3>Get Funded</h3>
        <p>Sign and receive your capital — often wired straight to your account within days.</p>
        <span class="pstep__tag">Fast payout</span>
      </article>
    </div>

    <div class="process__cta reveal">
      <p>Ready to see how much you qualify for?</p>
      <a href="/get-funded" class="btn btn--gold btn--lg">Check My Options
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- ===== Why us (reused band) ===== -->
<section class="section why-us">
  <div class="why-us__bg" aria-hidden="true">
    <span class="why-orb why-orb--gold"></span>
    <span class="why-orb why-orb--red"></span>
    <span class="why-lines"></span>
  </div>
  <div class="container why-us__grid">
    <div class="why-us__intro reveal">
      <span class="kicker light">Why Fund With Sparkman</span>
      <h2 class="section__title on-dark">Capital,<br /><span class="txt-gold">without the games.</span></h2>
      <p class="why-us__lead">We're not a faceless lender. You get a real strategist who shops your profile, explains every number, and fights for the best terms — the same way we fight for your credit.</p>
      <div class="why-us__rating">
        <span class="why-us__stars">★★★★★</span>
        <div><strong>$18M+ funded</strong><small>Across businesses &amp; individuals</small></div>
      </div>
      <a href="/get-funded" class="btn btn--gold btn--lg">Start My Application
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
    <div class="why-us__cards">
      <article class="wcard reveal"><span class="wcard__icon">⚡</span><h3>Fast Approvals</h3><p>Decisions in as little as 24–48 hours.</p></article>
      <article class="wcard reveal"><span class="wcard__icon">🎯</span><h3>Multiple Options</h3><p>One application, matched to many lenders.</p></article>
      <article class="wcard reveal"><span class="wcard__icon">🔍</span><h3>No Hidden Fees</h3><p>Transparent terms explained in plain English.</p></article>
      <article class="wcard reveal"><span class="wcard__icon">🤝</span><h3>Real Guidance</h3><p>A human strategist in your corner, start to finish.</p></article>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section class="section faq" id="faq">
  <div class="container faq__grid">
    <div class="faq__intro reveal">
      <span class="kicker">Questions</span>
      <h2 class="section__title">Funding, <span class="txt-gold">Answered</span></h2>
      <p>Not sure which option fits? Apply and a strategist will walk you through it — free.</p>
      <a href="/get-funded" class="btn btn--red">Apply Free</a>
    </div>
    <div class="faq__list">
      <details class="faq__item reveal" open>
        <summary>How fast can I get funded?</summary>
        <p>Many clients are approved within 24–48 hours and funded within a few business days, depending on the product and documents provided.</p>
      </details>
      <details class="faq__item reveal">
        <summary>Will applying hurt my credit?</summary>
        <p>No. Checking your options is a soft inquiry and won't impact your score. A hard pull only happens if you choose to move forward with an offer.</p>
      </details>
      <details class="faq__item reveal">
        <summary>What credit score do I need?</summary>
        <p>Options often start around a 600 score, but we work with a range of profiles. If you're still rebuilding, we'll point you to the right first step.</p>
      </details>
      <details class="faq__item reveal">
        <summary>How much can I qualify for?</summary>
        <p>Funding typically ranges from $5,000 to $500,000 based on revenue, credit, and time in business. Apply and we'll show you real numbers.</p>
      </details>
      <details class="faq__item reveal">
        <summary>Is this for businesses or individuals?</summary>
        <p>Both. We offer business funding (term loans, lines of credit, working capital) and personal funding options depending on your goals.</p>
      </details>
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
      <a href="/#about">About</a>
      <a href="/services">Services</a>
      <a href="/#results">Why Us</a>
      <a href="/#pricing">Pricing</a>
    </div>
    <div class="footer__col">
      <h4>Get Started</h4>
      <a href="/#contact">Free Analysis</a>
      <a href="/#faq">FAQ</a>
      <a href="/#process">Our Process</a>
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

<a href="/get-funded" class="fab" id="fab">Apply Now</a>

<script src="/script.js"></script>
</body>
</html>
