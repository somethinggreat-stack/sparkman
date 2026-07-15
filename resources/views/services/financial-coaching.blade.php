@extends('layouts.service')

@section('title', 'Financial Coaching — Sparkman Solutions')
@section('description', 'One-on-one guidance so your new score stays high — budgeting, funding readiness, and real wealth habits.')
@section('heroImg', '/images/svc-coaching.jpg')
@section('crumb', 'Financial Coaching')
@section('heroTitle', 'Financial Coaching')
@section('heroSub', 'One-on-one guidance so your new score stays high — budgeting, funding readiness, and real wealth habits.')

@section('leadform')
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Beyond the Score</span>
      <h2 class="section__title">Book Your <span class="txt-gold">Coaching Session</span></h2>
      <p class="section__sub">Tell us your goals and get matched with a strategist for a free first session — no cost, no obligation.</p>
    </div>
    <div class="apply__card reveal">
      <aside class="apply__aside">
        <span class="apply__stars">★★★★★</span>
        <h3>A strategist in<br />your corner.</h3>
        <ul class="apply__perks">
          <li><span>✓</span> Free first coaching session</li>
          <li><span>✓</span> A personalized game plan</li>
          <li><span>✓</span> Budgeting &amp; funding readiness</li>
          <li><span>✓</span> Reply within 1 business day</li>
        </ul>
        <div class="apply__founder">
          <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
          <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
        </div>
      </aside>
      <form class="apply__form lead-form" id="pageForm" data-lead-type="financial-coaching" novalidate>
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
          <select id="p_focus" name="focus" required>
            <option value="" disabled selected></option>
            <option>Budgeting</option>
            <option>Funding readiness</option>
            <option>Saving &amp; wealth habits</option>
            <option>Overall financial strategy</option>
            <option>Not sure yet</option>
          </select>
          <label for="p_focus" class="label--select">I want help with…</label>
        </div>
        <div class="field">
          <textarea id="p_message" name="message" rows="2" placeholder=" "></textarea>
          <label for="p_message">What are your goals? (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Book My Free Session</button>
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
        <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">Beyond the Score</span>
      <h2 class="section__title reveal">A strategist in <span class="txt-gold">your corner.</span></h2>
      <p class="reveal">One-on-one guidance so your new score actually works for you — budgeting, funding readiness, and real wealth habits.</p>
      <p class="reveal">You get a dedicated strategist long after the deletions, focused on your bigger financial goals.</p>
      <ul class="ticks reveal">
        <li>1-on-1 personal strategy</li>
        <li>Budgeting &amp; funding readiness</li>
        <li>Wealth-building habits</li>
        <li>Guidance long after deletions</li>
      </ul>
      <a href="#apply" class="btn btn--gold btn--lg reveal">Start My Free Analysis</a>
    </div>
  </div>
</section>
@endsection
