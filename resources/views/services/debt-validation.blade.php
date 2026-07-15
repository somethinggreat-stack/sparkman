@extends('layouts.service')

@section('title', 'Debt Validation — Sparkman Solutions')
@section('description', 'We force collectors to prove a debt is truly yours — and remove whatever they can\'t legally validate.')
@section('heroImg', '/images/svc-debt.jpg')
@section('crumb', 'Debt Validation')
@section('heroTitle', 'Debt Validation')
@section('heroSub', 'We force collectors to prove a debt is truly yours — and remove whatever they can\'t legally validate.')

@section('leadform')
<section class="section apply" id="apply">
  <div class="apply__bg" aria-hidden="true"></div>
  <div class="container">
    <div class="section__head reveal">
      <span class="kicker">Know Your Rights</span>
      <h2 class="section__title">Challenge Your <span class="txt-gold">Debts</span></h2>
      <p class="section__sub">Tell us about the collections on your report and we'll make them prove it — or remove it. Free and no obligation.</p>
    </div>
    <div class="apply__card reveal">
      <aside class="apply__aside">
        <span class="apply__stars">★★★★★</span>
        <h3>Make collectors<br />prove it.</h3>
        <ul class="apply__perks">
          <li><span>✓</span> Free debt &amp; collections review</li>
          <li><span>✓</span> Formal validation letters</li>
          <li><span>✓</span> FCRA &amp; FDCPA compliant</li>
          <li><span>✓</span> Reply within 1 business day</li>
        </ul>
        <div class="apply__founder">
          <img src="/assets/img/owner-hands.jpg" alt="Javon Sparkman" />
          <p><strong>Javon Sparkman</strong><small>Founder &amp; Lead Strategist</small></p>
        </div>
      </aside>
      <form class="apply__form lead-form" id="pageForm" data-lead-type="debt-validation" novalidate>
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
          <select id="p_debt" name="debt_type" required>
            <option value="" disabled selected></option>
            <option>Collections</option>
            <option>Charge-offs</option>
            <option>A debt I don't recognize</option>
            <option>Multiple collectors</option>
            <option>Not sure</option>
          </select>
          <label for="p_debt" class="label--select">What are you dealing with?</label>
        </div>
        <div class="field">
          <textarea id="p_message" name="message" rows="2" placeholder=" "></textarea>
          <label for="p_message">Tell us about the debt (optional)</label>
        </div>
        <button type="submit" class="btn btn--gold btn--lg btn--block">Challenge My Debts</button>
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
        <img src="/assets/img/owner-alt.jpg" alt="Javon Sparkman, Founder of Sparkman Solutions" />
      </div>
      <div class="about__badge">
        <strong>Javon Sparkman</strong>
        <span>Founder &amp; Lead Credit Strategist</span>
      </div>
    </div>
    <div class="about__copy">
      <span class="kicker reveal">Know Your Rights</span>
      <h2 class="section__title reveal">Make them <span class="txt-gold">prove it.</span></h2>
      <p class="reveal">Under federal law, collectors must prove a debt is truly yours. If they can't validate it, it has to come off your report.</p>
      <p class="reveal">We exercise that right relentlessly on your behalf — challenging questionable collections and forcing real accountability.</p>
      <ul class="ticks reveal">
        <li>Formal debt validation letters</li>
        <li>Collector accountability</li>
        <li>100% FCRA &amp; FDCPA compliant</li>
        <li>Remove what can't be verified</li>
      </ul>
      <a href="#apply" class="btn btn--gold btn--lg reveal">Start My Free Analysis</a>
    </div>
  </div>
</section>
@endsection
