@extends('layouts.service')

@section('title', 'Credit Building — Sparkman Solutions')
@section('description', 'Tradelines, secured strategies, and reporting tools that add positive history quickly and responsibly.')
@section('heroImg', '/images/svc-building.jpg')
@section('crumb', 'Credit Building')
@section('heroTitle', 'Credit Building')
@section('heroSub', 'Tradelines, secured strategies, and reporting tools that add positive history fast and responsibly.')

@section('leadform')
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Build Momentum</span>
      <h2 class="section__title">Build Your <span class="txt-gold">Credit</span></h2>
      <p class="section__sub">Tell us where you're starting and we'll build a plan to add positive history fast — free and no obligation.</p>
    </div>
    <div class="apply__card reveal">
      <aside class="apply__aside">
        <span class="apply__stars">★★★★★</span>
        <h3>Add the history<br />lenders love.</h3>
        <ul class="apply__perks">
          <li><span>✓</span> Free credit review</li>
          <li><span>✓</span> Personalized tradeline strategy</li>
          <li><span>✓</span> Secured-credit plan</li>
          <li><span>✓</span> Reply within 1 business day</li>
        </ul>
        <div class="apply__founder">
          <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
          <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
        </div>
      </aside>
      <form class="apply__form lead-form" id="pageForm" data-lead-type="credit-building" novalidate>
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
          <select id="p_credit" name="credit_stage" required>
            <option value="" disabled selected></option>
            <option>No credit yet</option>
            <option>Thin file (little history)</option>
            <option>Rebuilding after damage</option>
            <option>Good — want it great</option>
            <option>Not sure</option>
          </select>
          <label for="p_credit" class="label--select">My credit is…</label>
        </div>
        <div class="field">
          <textarea id="p_message" name="message" rows="2" placeholder=" "></textarea>
          <label for="p_message">What's your goal? (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Start Building</button>
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
        <img src="/assets/img/owner-laugh.jpg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">Build Momentum</span>
      <h2 class="section__title reveal">Add the <span class="txt-gold">positive history</span> lenders love.</h2>
      <p class="reveal">We don't just remove the bad — we build the good. Tradelines, secured strategies, and reporting tools add positive history quickly and responsibly.</p>
      <p class="reveal">Your profile keeps getting stronger, so your score climbs and stays there.</p>
      <ul class="ticks reveal">
        <li>Strategic tradelines</li>
        <li>Secured-credit strategies</li>
        <li>On-time reporting tools</li>
        <li>Long-term score growth</li>
      </ul>
      <a href="#apply" class="btn btn--gold btn--lg reveal">Start My Free Analysis</a>
    </div>
  </div>
</section>
@endsection
