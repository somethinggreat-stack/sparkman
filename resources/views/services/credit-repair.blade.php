@extends('layouts.service')

@section('title', 'Credit Repair & Disputes — Sparkman Solutions')
@section('description', 'We challenge inaccurate, outdated, and unverifiable negatives — collections, charge-offs, late payments, repossessions and more — and remove what doesn\'t belong on your report.')
@section('heroImg', '/images/svc-credit-repair.jpg')
@section('crumb', 'Credit Repair')
@section('heroTitle', 'Credit Repair & Disputes')
@section('heroSub', 'We challenge the inaccurate, outdated, and unverifiable negatives dragging your score down — and remove what doesn\'t belong.')

@section('leadform')
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Free Analysis</span>
      <h2 class="section__title">Start Your <span class="txt-gold">Credit Repair</span></h2>
      <p class="section__sub">Send us your details and we'll pinpoint the negatives dragging your score down — free, secure, and no obligation.</p>
    </div>
    <div class="apply__card reveal">
      <aside class="apply__aside">
        <span class="apply__stars">★★★★★</span>
        <h3>See what we can<br />delete for you.</h3>
        <ul class="apply__perks">
          <li><span>✓</span> Free full credit analysis</li>
          <li><span>✓</span> Every negative item reviewed</li>
          <li><span>✓</span> A custom dispute plan</li>
          <li><span>✓</span> Reply within 1 business day</li>
        </ul>
        <div class="apply__founder">
          <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
          <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
        </div>
      </aside>
      <form class="apply__form lead-form" id="pageForm" data-lead-type="credit-repair" novalidate>
        <div class="field-row">
          <div class="field">
            <input type="text" id="p_name" name="name" required placeholder=" " autocomplete="name" />
            <label for="p_name">Full Name</label>
          </div>
          <div class="field">
            <input type="tel" id="p_phone" name="phone" required placeholder=" " autocomplete="tel" />
            <label for="p_phone">Phone Number</label>
          </div>
        </div>
        <div class="field">
          <input type="email" id="p_email" name="email" required placeholder=" " autocomplete="email" />
          <label for="p_email">Email Address</label>
        </div>
        <div class="field">
          <select id="p_concern" name="concern" required>
            <option value="" disabled selected></option>
            <option>Collections</option>
            <option>Charge-offs</option>
            <option>Late payments</option>
            <option>Repossessions</option>
            <option>Medical bills</option>
            <option>Hard inquiries</option>
            <option>Not sure / multiple</option>
          </select>
          <label for="p_concern" class="label--select">What's hurting your report?</label>
        </div>
        <div class="field">
          <textarea id="p_message" name="message" rows="2" placeholder=" "></textarea>
          <label for="p_message">Tell us about your situation (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Get My Free Analysis</button>
        <p class="form__disclaimer">🔒 Your info is secure. We never sell your data.</p>
        <div class="form__success" hidden>
          <span>✓</span> Thank you! Your request is in — we'll reach out within one business day.
        </div>
      </form>
    </div>
  </div>
</section>
@endsection

@section('content')
<section class="section about">
  <div class="container about__grid">
    <div class="about__media reveal">
      <div class="about__frame">
        <img src="/assets/img/owner-sitting.jpg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">The Damage Control</span>
      <h2 class="section__title reveal">Delete what's <span class="txt-gold">holding you back.</span></h2>
      <p class="reveal">Collections, charge-offs, late payments, repossessions, medical bills, hard inquiries — if it's inaccurate, outdated, or unverifiable, it doesn't belong on your report.</p>
      <p class="reveal">We build an aggressive, FCRA-compliant dispute strategy tailored to your exact report, then work every round until the negatives are gone for good.</p>
      <ul class="ticks reveal">
        <li>Every negative item challenged</li>
        <li>All 3 bureaus worked in parallel</li>
        <li>Unlimited, multi-round disputes</li>
        <li>Real-time deletion tracking</li>
      </ul>
      <a href="#apply" class="btn btn--gold btn--lg reveal">Start My Free Analysis</a>
    </div>
  </div>
</section>
@endsection
