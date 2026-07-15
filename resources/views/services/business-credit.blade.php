@extends('layouts.service')

@section('title', 'Business Credit — Sparkman Solutions')
@section('description', 'Build fundable business credit separate from your personal profile — from EIN and entity setup to net-30 vendor accounts.')
@section('heroImg', '/images/svc-business.jpg')
@section('crumb', 'Business Credit')
@section('heroTitle', 'Business Credit')
@section('heroSub', 'Build fundable business credit that\'s separate from your personal profile — from EIN setup to net-30 vendors.')

@section('leadform')
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Business Funding</span>
      <h2 class="section__title">Build Your <span class="txt-gold">Business Credit</span></h2>
      <p class="section__sub">Tell us about your business and we'll map the fastest path to a fundable profile — free and no obligation.</p>
    </div>
    <div class="apply__card reveal">
      <aside class="apply__aside">
        <span class="apply__stars">★★★★★</span>
        <h3>Get your business<br />funding-ready.</h3>
        <ul class="apply__perks">
          <li><span>✓</span> Free business credit review</li>
          <li><span>✓</span> EIN &amp; entity guidance</li>
          <li><span>✓</span> Net-30 vendor roadmap</li>
          <li><span>✓</span> Reply within 1 business day</li>
        </ul>
        <div class="apply__founder">
          <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
          <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
        </div>
      </aside>
      <form class="apply__form lead-form" id="pageForm" data-lead-type="business-credit" novalidate>
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
          <select id="p_stage" name="business_stage" required>
            <option value="" disabled selected></option>
            <option>Just an idea</option>
            <option>Brand new (under 1 year)</option>
            <option>1–3 years old</option>
            <option>3+ years old</option>
            <option>Not sure</option>
          </select>
          <label for="p_stage" class="label--select">My business is…</label>
        </div>
        <div class="field">
          <textarea id="p_message" name="message" rows="2" placeholder=" "></textarea>
          <label for="p_message">What do you need funding for? (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Build My Business Credit</button>
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
        <img src="/assets/img/portrait-suit.jpg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">Fund Your Vision</span>
      <h2 class="section__title reveal">Credit that works <span class="txt-gold">for your business.</span></h2>
      <p class="reveal">Build fundable business credit that's completely separate from your personal profile — from EIN and entity setup to net-30 vendor accounts and beyond.</p>
      <p class="reveal">We position your business to qualify for real capital without putting your personal credit on the line.</p>
      <ul class="ticks reveal">
        <li>EIN &amp; entity setup guidance</li>
        <li>Net-30 vendor accounts</li>
        <li>Separate from personal credit</li>
        <li>A funding-ready profile</li>
      </ul>
      <a href="#apply" class="btn btn--gold btn--lg reveal">Start My Free Analysis</a>
    </div>
  </div>
</section>
@endsection
