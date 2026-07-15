<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="theme-color" content="#0a1f52" />
<title>Sparkman Solutions — Premium Credit Repair &amp; Financial Freedom</title>
<meta name="description" content="Sparkman Solutions helps you delete negative items, rebuild your credit, and unlock the score you deserve. Expert credit repair led by Javon Sparkman." />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
</head>
<body>

<!-- ===== Progress bar ===== -->
<div class="scroll-progress" id="scrollProgress"></div>

<!-- ===== Navigation ===== -->
<header class="nav" id="nav">
  <div class="container nav__inner">
    <a href="#home" class="brand" aria-label="Sparkman Solutions home">
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

<!-- ===== Hero ===== -->
<section class="hero" id="home">
  <div class="hero__bg" aria-hidden="true">
    <span class="hero__blob hero__blob--a"></span>
    <span class="hero__blob hero__blob--b"></span>
    <span class="hero__dots"></span>
  </div>

  <div class="container hero__inner">
    <div class="hero__copy">
      <span class="hero__badge reveal">
        <span class="hero__stars">★★★★★</span> Trusted by 14,000+ clients nationwide
      </span>

      <h1 class="hero__title reveal">
        Your partner for credit repair &amp;<br />
        <span class="txt-gold">real financial freedom.</span>
      </h1>

      <p class="hero__lead reveal">
        We remove the negatives holding you back, rebuild your score, and get you
        approved for the home, the car, and the funding you deserve — done for you,
        guided personally by Javon Sparkman.
      </p>

      <div class="hero__actions reveal">
        <a href="#contact" class="btn btn--gold btn--lg">Get My Free Analysis
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="/pricing" class="btn btn--outline-sapphire btn--lg">See Pricing Plans</a>
      </div>

    </div>

    <div class="hero__media reveal">
      <div class="hero__frame">
        <img src="/images/1.jpeg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
    </div>
  </div>

</section>

<!-- ===== One-stop-shop / ownership pitch ===== -->
<section class="section pitch">
  <div class="container pitch__inner reveal">
    <span class="pitch__eyebrow">Your one-stop shop — credit repair → getting you funded</span>
    <h2 class="pitch__title">Credit isn't the goal — <span class="txt-gold">ownership is.</span></h2>
    <p class="pitch__lead">At Sparkman Solutions, we show you how to turn your <b>credit into capital</b>, your <b>capital into assets</b>, and your <b>assets into generational wealth</b>.</p>
    <div class="pitch__flow">
      <span class="pitch__step">Credit</span>
      <span class="pitch__arrow">→</span>
      <span class="pitch__step">Capital</span>
      <span class="pitch__arrow">→</span>
      <span class="pitch__step">Assets</span>
      <span class="pitch__arrow">→</span>
      <span class="pitch__step pitch__step--gold">Generational Wealth</span>
    </div>
  </div>
</section>

<!-- ===== Services (sticky scroll) ===== -->
<section class="section svc" id="services">
  <div class="container svc__grid">
    <aside class="svc__aside reveal">
      <span class="kicker">What We Do</span>
      <h2 class="svc__title">A complete path to<br /><span class="txt-gold">elite credit.</span></h2>
      <p class="svc__lead">From your first dispute to lasting financial freedom, Sparkman Solutions makes fixing and building your credit simple, honest, and personal.</p>
      <p class="svc__lead">Every plan is tailored to your report — no cookie-cutter letters, just a strategy engineered to move your score and keep it there.</p>
      <ul class="svc__ticks">
        <li><span class="svc__tick">✓</span> Tailored to your exact report</li>
        <li><span class="svc__tick">✓</span> Aggressive, FCRA-compliant process</li>
        <li><span class="svc__tick">✓</span> One dedicated strategist, start to finish</li>
      </ul>
      <a href="#contact" class="btn btn--gold btn--lg" data-open-form>Get My Free Analysis
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </aside>

    <div class="svc__cards">
      <article class="svccard svccard--navy reveal">
        <img class="svccard__img" src="/images/svc-credit-repair.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">01</span>
        <h3>Credit Repair &amp; Disputes</h3>
        <p>We challenge the inaccurate, outdated, and unverifiable negatives dragging your score down — collections, charge-offs, late payments, repossessions and more.</p>
        <p>Every dispute is aggressive, compliant, and tailored to your report, so the items that don't belong get removed for good.</p>
        <div class="svccard__foot">
          <a href="/credit-repair" class="svccard__link">Learn More</a>
          <a href="/credit-repair" class="svccard__arrow" aria-label="Learn more about credit repair">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

      <article class="svccard svccard--gold reveal">
        <img class="svccard__img" src="/images/svc-funding.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">02</span>
        <h3>Funding</h3>
        <p>Once your credit is strong, we help you put it to work — business and personal funding from $5,000 to $500,000.</p>
        <p>A simple application, real human guidance, and approvals in as little as 24–48 hours. One stop, start to finish.</p>
        <div class="svccard__foot">
          <a href="/funding" class="svccard__link">Learn More</a>
          <a href="/funding" class="svccard__arrow" aria-label="Learn more about funding">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

      <article class="svccard svccard--navy reveal">
        <img class="svccard__img" src="/images/svc-business.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">03</span>
        <h3>Business Credit</h3>
        <p>Build fundable business credit that's separate from your personal profile — from EIN and entity setup to net-30 vendor accounts.</p>
        <p>Position your business to qualify for real capital without putting your personal credit on the line.</p>
        <div class="svccard__foot">
          <a href="/business-credit" class="svccard__link">Learn More</a>
          <a href="/business-credit" class="svccard__arrow" aria-label="Learn more about business credit">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

      <article class="svccard svccard--gold reveal">
        <img class="svccard__img" src="/images/svc-debt.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">04</span>
        <h3>Debt Validation</h3>
        <p>We force collectors to prove a debt is truly yours — and remove whatever they can't legally validate.</p>
        <p>It's your right under the law, and we exercise it relentlessly on your behalf.</p>
        <div class="svccard__foot">
          <a href="/debt-validation" class="svccard__link">Learn More</a>
          <a href="/debt-validation" class="svccard__arrow" aria-label="Learn more about debt validation">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

      <article class="svccard svccard--navy reveal">
        <img class="svccard__img" src="/images/svc-building.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">05</span>
        <h3>Credit Building</h3>
        <p>Tradelines, secured strategies, and reporting tools that add positive history quickly and responsibly.</p>
        <p>We don't just remove the bad — we build the good, so your profile keeps getting stronger.</p>
        <div class="svccard__foot">
          <a href="/credit-building" class="svccard__link">Learn More</a>
          <a href="/credit-building" class="svccard__arrow" aria-label="Learn more about credit building">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

      <article class="svccard svccard--gold reveal">
        <img class="svccard__img" src="/images/svc-coaching.jpg" alt="" aria-hidden="true" loading="lazy" />
        <span class="svccard__num">06</span>
        <h3>Financial Coaching</h3>
        <p>One-on-one guidance so your new score stays high — budgeting, funding readiness, and real wealth habits.</p>
        <p>You get a strategist in your corner long after the deletions, focused on your bigger financial goals.</p>
        <div class="svccard__foot">
          <a href="/financial-coaching" class="svccard__link">Learn More</a>
          <a href="/financial-coaching" class="svccard__arrow" aria-label="Learn more about financial coaching">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
          </a>
        </div>
      </article>

    </div>
  </div>
</section>

<!-- ===== What We Remove ===== -->
<section class="section remove" id="remove">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">The Damage Control</span>
      <h2 class="section__title">What We Wipe From <span class="txt-gold">Your Report</span></h2>
      <p class="section__sub">If it's inaccurate, outdated, or unverifiable — it doesn't belong on your report. Hover to see what we go after.</p>
    </div>

    <div class="remove__grid">
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🧾</span><span class="ritem__check">✓</span></span><span class="ritem__name">Collections</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">💳</span><span class="ritem__check">✓</span></span><span class="ritem__name">Charge-Offs</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">⏰</span><span class="ritem__check">✓</span></span><span class="ritem__name">Late Payments</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🔍</span><span class="ritem__check">✓</span></span><span class="ritem__name">Hard Inquiries</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🚗</span><span class="ritem__check">✓</span></span><span class="ritem__name">Repossessions</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">⚖️</span><span class="ritem__check">✓</span></span><span class="ritem__name">Bankruptcies</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🏥</span><span class="ritem__check">✓</span></span><span class="ritem__name">Medical Bills</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🏠</span><span class="ritem__check">✓</span></span><span class="ritem__name">Foreclosures</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">📄</span><span class="ritem__check">✓</span></span><span class="ritem__name">Judgments</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">🏛️</span><span class="ritem__check">✓</span></span><span class="ritem__name">Tax Liens</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">❌</span><span class="ritem__check">✓</span></span><span class="ritem__name">Fraud &amp; Errors</span><span class="ritem__stamp">Removed</span></div>
      <div class="ritem reveal"><span class="ritem__icon"><span class="ritem__emoji">📉</span><span class="ritem__check">✓</span></span><span class="ritem__name">Public Records</span><span class="ritem__stamp">Removed</span></div>
    </div>
  </div>
</section>

<!-- ===== Pricing ===== -->
<section class="section pricing" id="pricing">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Payment Plans</span>
      <h2 class="section__title">Invest in a <span class="txt-gold">Better Future</span></h2>
      <p class="section__sub">Simple down payment, then a low monthly. Cancel anytime — your first consultation is always free.</p>
    </div>
    <div class="plans">
      <article class="plan reveal">
        <h3>Individual</h3>
        <p class="plan__for">One person, full cleanup</p>
        <div class="plan__price"><span>$</span>497<small> down</small></div>
        <p class="plan__monthly">then <b>$99</b>/month</p>
        <ul>
          <li>Full credit analysis</li>
          <li>All 3 bureaus covered</li>
          <li>Unlimited disputes</li>
          <li>Monthly progress updates</li>
          <li>Cancel anytime</li>
        </ul>
        <a href="/checkout/individual" class="btn btn--outline">Get Started</a>
      </article>
      <article class="plan plan--pop reveal">
        <span class="plan__ribbon">Most Popular</span>
        <h3>Aggressive Attack</h3>
        <p class="plan__for">Maximum, fastest results</p>
        <div class="plan__price"><span>$</span>997<small> one-time</small></div>
        <p class="plan__monthly"><b>One-time payment</b> — no monthly</p>
        <ul>
          <li>Everything in Individual</li>
          <li>Aggressive multi-round attacks</li>
          <li>Priority dispute cycles</li>
          <li>Score optimization plan</li>
          <li>Debt validation &amp; priority support</li>
        </ul>
        <a href="/checkout/aggressive" class="btn btn--gold">Start Winning</a>
      </article>
      <article class="plan reveal">
        <h3>Husband &amp; Wife</h3>
        <p class="plan__for">Two people, one plan</p>
        <div class="plan__price"><span>$</span>897<small> one-time</small></div>
        <p class="plan__monthly"><b>One-time payment</b> — no monthly</p>
        <ul>
          <li>Both credit profiles worked together</li>
          <li>All 3 bureaus × 2</li>
          <li>Unlimited disputes for both</li>
          <li>Shared dedicated strategist</li>
          <li>Cancel anytime</li>
        </ul>
        <a href="/checkout/couple" class="btn btn--outline">Get Started</a>
      </article>
    </div>
    <p class="pricing__note reveal">Your first consultation is always free — we'll confirm the right plan for your report.</p>
  </div>
</section>

<!-- ===== Results Gallery ===== -->
<section class="section results-gallery" id="wins">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Client Wins</span>
      <h2 class="section__title">Scores Up. <span class="txt-gold">Lives Changed.</span></h2>
      <p class="section__sub">Real results from real clients. Swipe through the proof.</p>
    </div>

    <div class="gallery gallery--wide">
      <figure class="rcard reveal">
        <img src="/assets/img/results/result-1.png" alt="Client 3-bureau credit increase: +136, +116, +118 points" loading="lazy" />
        <figcaption><span class="rcard__badge">✓ Verified</span> 41 items deleted · up to +136 pts</figcaption>
      </figure>
      <figure class="rcard reveal">
        <img src="/assets/img/results/result-2.png" alt="Client 3-bureau credit increase: +112, +110, +138 points" loading="lazy" />
        <figcaption><span class="rcard__badge">✓ Verified</span> All 3 bureaus up · up to +138 pts</figcaption>
      </figure>
      <figure class="rcard reveal">
        <img src="/assets/img/results/result-3.png" alt="Client 3-bureau credit increase: +225, +228, +171 points" loading="lazy" />
        <figcaption><span class="rcard__badge">✓ Verified</span> 17 items deleted · up to +228 pts</figcaption>
      </figure>
      <figure class="rcard reveal">
        <img src="/assets/img/results/result-4.png" alt="Client 3-bureau credit increase: +154, +103, +135 points" loading="lazy" />
        <figcaption><span class="rcard__badge">✓ Verified</span> 19 items deleted · up to +154 pts</figcaption>
      </figure>
    </div>

    <p class="gallery__note">Real client reports. Individual results vary.</p>
  </div>
</section>

<!-- ===== Process ===== -->
<section class="section process" id="process">
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">The Process</span>
      <h2 class="section__title">Four Steps to a <span class="txt-gold">Higher Score</span></h2>
      <p class="section__sub">Simple, transparent, and done-for-you. You watch the deletions roll in.</p>
    </div>

    <div class="steps">
      <article class="pstep reveal">
        <span class="pstep__ghost">01</span>
        <div class="pstep__icon">🔍</div>
        <h3>Free Credit Analysis</h3>
        <p>We pull a full picture of your report and pinpoint every item hurting your score.</p>
        <span class="pstep__tag">Day 1</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">02</span>
        <div class="pstep__icon">🗺️</div>
        <h3>Custom Game Plan</h3>
        <p>You get a personalized roadmap with timelines, targets, and exactly what we'll dispute.</p>
        <span class="pstep__tag">Day 2–3</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">03</span>
        <div class="pstep__icon">⚡</div>
        <h3>We Go To Work</h3>
        <p>Aggressive, compliant disputes and challenges sent to bureaus and creditors on your behalf.</p>
        <span class="pstep__tag">Ongoing</span>
      </article>
      <article class="pstep reveal">
        <span class="pstep__ghost">04</span>
        <div class="pstep__icon">📈</div>
        <h3>Watch It Climb</h3>
        <p>Track deletions and score gains in real time — and get funding-ready for what's next.</p>
        <span class="pstep__tag">45+ days</span>
      </article>
    </div>

    <div class="process__cta reveal">
      <p>Ready to see what's dragging your score down?</p>
      <a href="#contact" class="btn btn--gold btn--lg">Start My Free Analysis
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
  </div>
</section>

<!-- ===== About ===== -->
<section class="section about" id="about">
  <div class="container about__grid">
    <div class="about__media reveal">
      <div class="about__frame">
        <img src="/assets/img/owner-laugh.jpg" alt="Javon Sparkman smiling" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">Meet Your Advocate</span>
      <h2 class="section__title reveal">Your Credit, In <span class="txt-gold">Expert Hands</span></h2>
      <p class="reveal">Javon Sparkman built Sparkman Solutions on a simple belief: your past shouldn't lock the door to your future. What started as helping friends and family fix their reports grew into a full-service credit restoration firm trusted by thousands.</p>
      <p class="reveal">When you work with us, you're not a ticket number. You get a strategist in your corner who treats your goals — the house, the car, the business, the freedom — like his own.</p>
      <ul class="ticks reveal">
        <li>Transparent pricing, no hidden fees</li>
        <li>Personal, one-on-one strategy</li>
        <li>100% legal &amp; FCRA-compliant process</li>
        <li>Guidance long after the deletions</li>
      </ul>
      <a href="#contact" class="btn btn--gold btn--lg reveal">Work With Javon</a>
    </div>
  </div>
</section>

<!-- ===== Why us ===== -->
<section class="section why-us" id="results">
  <div class="why-us__bg" aria-hidden="true">
    <span class="why-orb why-orb--gold"></span>
    <span class="why-orb why-orb--red"></span>
    <span class="why-lines"></span>
  </div>
  <div class="container why-us__grid">
    <div class="why-us__intro reveal">
      <span class="kicker light">Why Sparkman</span>
      <h2 class="section__title on-dark">Built to<br /><span class="txt-gold">Change Lives.</span></h2>
      <p class="why-us__lead">The difference is in the details — a relentless, compliant process and a team that treats your goals like their own. The results speak for themselves.</p>
      <div class="why-us__rating">
        <span class="why-us__stars">★★★★★</span>
        <div><strong>5.0 / 5.0</strong><small>Rated by 14,000+ happy clients</small></div>
      </div>
      <a href="#contact" class="btn btn--gold btn--lg">Start My Free Analysis
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>

    <div class="why-us__cards">
      <article class="wcard reveal">
        <span class="wcard__icon">⚡</span>
        <h3>Fast Movement</h3>
        <p>Most clients see their first deletions within 45 days.</p>
      </article>
      <article class="wcard reveal">
        <span class="wcard__icon">🛡️</span>
        <h3>Fully Compliant</h3>
        <p>Every action follows the FCRA and consumer protection law.</p>
      </article>
      <article class="wcard reveal">
        <span class="wcard__icon">📊</span>
        <h3>Total Transparency</h3>
        <p>Track progress, deletions, and score changes anytime.</p>
      </article>
      <article class="wcard reveal">
        <span class="wcard__icon">🤝</span>
        <h3>People First</h3>
        <p>Real humans, real strategy, real accountability.</p>
      </article>
    </div>
  </div>
</section>

<!-- ===== Guarantee ===== -->
<section class="guarantee-sec">
  <div class="container">
    <div class="cert reveal">
      <div class="cert__seal">
        <span class="cert__seal-inner"><b>100%</b><small>Effort</small></span>
      </div>
      <span class="cert__badge">Our Promise To You</span>
      <h2 class="cert__title">We don't stop until your credit <span class="txt-gold">works for you.</span></h2>
      <p class="cert__lead">No long contracts. Just an honest, aggressive, results-driven process — and a team that actually answers when you call.</p>

      <div class="cert__points">
        <div class="cpoint"><span class="cpoint__ic">🔒</span><strong>Free &amp; Confidential</strong><small>No-cost analysis, zero obligation</small></div>
        <div class="cpoint"><span class="cpoint__ic">🔄</span><strong>Cancel Anytime</strong><small>Simple plans, never a contract</small></div>
        <div class="cpoint"><span class="cpoint__ic">⚖️</span><strong>100% Legal</strong><small>Every dispute is FCRA-compliant</small></div>
      </div>

      <div class="cert__actions">
        <a href="{{ config('services.ghl.calendar_url') ?: '/get-funded' }}" target="_blank" rel="noopener" class="btn btn--gold btn--lg">Book My Free Consultation
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 6l6 6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        </a>
        <a href="tel:+10000000000" class="btn btn--outline btn--lg">Call / Text Us</a>
      </div>

      <p class="cert__sign">“We work every round until it's done right.” <b>— Javon Sparkman, Founder</b></p>
    </div>
  </div>
</section>

<!-- ===== FAQ ===== -->
<section class="section faq" id="faq">
  <div class="container faq__grid">
    <div class="faq__intro reveal">
      <span class="kicker">Questions</span>
      <h2 class="section__title">Answers Before <span class="txt-gold">You Start</span></h2>
      <p>Still curious? Book a free call and we'll walk through your exact situation.</p>
      <a href="#contact" class="btn btn--red">Book Free Call</a>
    </div>
    <div class="faq__list">
      <details class="faq__item reveal" open>
        <summary>How long does credit repair take?</summary>
        <p>Most clients see their first deletions within 30–45 days. A full transformation typically takes 3–6 months depending on your report.</p>
      </details>
      <details class="faq__item reveal">
        <summary>Is credit repair legal?</summary>
        <p>Absolutely. You have the right under the Fair Credit Reporting Act (FCRA) to dispute any item that is inaccurate, outdated, or unverifiable. We simply do it expertly on your behalf.</p>
      </details>
      <details class="faq__item reveal">
        <summary>How much does it cost?</summary>
        <p>Your first consultation is free. Ongoing plans start at $99/mo, and your exact quote is set once we review your report — no surprises, cancel anytime.</p>
      </details>
      <details class="faq__item reveal">
        <summary>Can you guarantee a specific score?</summary>
        <p>No ethical company can guarantee exact numbers, and we never will. What we guarantee is an aggressive, compliant, results-driven process — and full transparency the whole way.</p>
      </details>
      <details class="faq__item reveal">
        <summary>Do you help with business credit too?</summary>
        <p>Yes. Our Elite plan builds fundable business credit separate from your personal profile, from EIN setup to net-30 vendor accounts.</p>
      </details>
    </div>
  </div>
</section>

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
      <a href="#home" class="brand brand--footer">
        <img class="footer__logo" src="/images/logo-white.png" alt="Sparkman Solutions" />
      </a>
      <p>Premium credit restoration &amp; financial freedom. Your future, unlocked.</p>
    </div>
    <div class="footer__col">
      <h4>Company</h4>
      <a href="/about">About</a>
      <a href="/services">Services</a>
      <a href="#results">Why Us</a>
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
