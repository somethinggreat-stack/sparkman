<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Your Funding Result — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  body{background:linear-gradient(180deg,#fff 0%,#eef3fc 100%)}
  .qr{padding:104px 20px 0;max-width:760px;margin:0 auto}
  .qr__hero{text-align:center;margin-bottom:30px}
  .qr__badge{width:76px;height:76px;border-radius:22px;display:grid;place-items:center;font-size:2rem;margin:0 auto 20px;color:#fff;transform:rotate(-4deg)}
  .qr__badge--win{background:linear-gradient(135deg,var(--sapphire),var(--sapphire-2));box-shadow:0 22px 44px -18px rgba(15,82,186,.65)}
  .qr__badge--prep{background:linear-gradient(135deg,var(--gold-3),var(--gold-2));box-shadow:0 22px 44px -18px rgba(217,168,58,.7)}
  .qr__eyebrow{display:inline-block;font-size:.74rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;padding:6px 14px;border-radius:999px;margin-bottom:16px}
  .qr__eyebrow--win{color:var(--sapphire);background:#e7f0fe;border:1px solid #cfe0fd}
  .qr__eyebrow--prep{color:#8a6410;background:#fdf3d8;border:1px solid #f0d78f}
  .qr__hero h1{font-family:var(--font-head);font-weight:800;color:var(--blue-900);letter-spacing:-.02em;font-size:clamp(1.95rem,4.8vw,2.95rem);line-height:1.05}
  .qr__hero p{color:var(--slate);margin-top:14px;font-size:1.06rem;line-height:1.6;max-width:580px;margin-left:auto;margin-right:auto}
  .qr__card{background:#fff;border:1px solid var(--line);border-radius:22px;padding:34px 34px 30px;box-shadow:0 34px 80px -50px rgba(10,31,82,.55);margin-bottom:20px}
  .qr__card h2{font-size:1.22rem;font-weight:800;color:var(--blue-900);margin-bottom:6px}
  .qr__card .lede{color:var(--slate);font-size:.95rem;margin-bottom:20px}

  /* value rows */
  .fixgrid{display:grid;gap:12px;margin:4px 0 8px}
  .fixrow{display:flex;align-items:center;gap:15px;padding:15px 18px;border:1px solid var(--line);border-radius:15px;background:linear-gradient(180deg,#fff,#fbfcfe)}
  .fixrow__ic{width:38px;height:38px;flex:none;border-radius:11px;display:grid;place-items:center;font-size:.95rem;font-weight:800}
  .fixrow__t{font-weight:600;color:var(--blue-900);font-size:1rem;line-height:1.25}
  .fixrow__tag{margin-left:auto;font-size:.68rem;font-weight:800;text-transform:uppercase;letter-spacing:.05em;border-radius:999px;padding:6px 12px;white-space:nowrap}
  /* prep (gold) variant */
  .fixrow--prep .fixrow__ic{background:linear-gradient(135deg,#fff2d0,#ffe1a0);color:var(--gold-3)}
  .fixrow--prep .fixrow__tag{color:#177245;background:#e8f7ee}
  /* win (sapphire) variant */
  .fixrow--win .fixrow__ic{background:linear-gradient(135deg,#e4efff,#cfe1ff);color:var(--sapphire)}
  .fixrow--win .fixrow__tag{color:var(--sapphire);background:#e7f0fe}

  /* journey strip */
  .journey{display:flex;align-items:center;gap:6px;justify-content:center;flex-wrap:wrap;margin:24px 0 4px;color:var(--slate);font-size:.82rem;font-weight:700}
  .journey b{color:var(--blue-900)}
  .journey .now{background:var(--gold);color:#3a2708;padding:4px 11px;border-radius:999px}
  .journey .arw{color:var(--gold-3);font-weight:800}

  .qr__cta{display:flex;flex-wrap:wrap;gap:12px;margin-top:24px}
  .qr__cta .btn{flex:1 1 auto}
  .qr__note{border-radius:14px;padding:15px 18px;font-size:.9rem;margin-top:18px;line-height:1.5}
  .qr__note--win{background:#eaf1fd;border:1px solid #cddffb;color:#0c3f8f}
  .qr__note--prep{background:#fff8e6;border:1px solid #f0d38a;color:#7a5a12}

  .community{background:linear-gradient(150deg,#0a1f52,#12306e);color:#fff;border-radius:22px;padding:34px 32px;text-align:center;margin-bottom:44px;position:relative;overflow:hidden}
  .community::after{content:"";position:absolute;inset:auto -40px -60px auto;width:220px;height:220px;background:radial-gradient(circle,rgba(217,168,58,.22),transparent 70%)}
  .community h2{font-size:1.42rem;font-weight:800;margin-bottom:8px;position:relative}
  .community h2 .txt-gold{font-style:normal}
  .community p{color:#c3d0ef;max-width:520px;margin:0 auto 18px;position:relative}
  .btn--sapphire{background:linear-gradient(120deg,var(--sapphire),var(--sapphire-2));color:#fff}
  .btn--tg{background:#229ED9;color:#fff;position:relative}
  @media(max-width:560px){.qr{padding:92px 16px 0}.qr__card{padding:26px 20px 24px}.qr__cta .btn{flex:1 1 100%}}
</style>
</head>
<body>

@include('partials.nav', ['solidNav' => true])

<div class="qr">
  @if ($outcome === 'funding')
    {{-- ===== Qualified for funding ===== --}}
    <div class="qr__hero">
      <div class="qr__badge qr__badge--win">✓</div>
      <span class="qr__eyebrow qr__eyebrow--win">Funding Approved · Pre-Qualified</span>
      <h1>{{ $name }}, you're <span style="color:var(--sapphire)">funding-ready.</span></h1>
      <p>Based on your answers, you meet our funding criteria. A Sparkman strategist will reach out within <b>one business day</b> to map your options — <b>$5,000 to $500,000</b>.</p>
    </div>

    <div class="qr__card">
      <h2>What happens on your funding call</h2>
      <p class="lede">A quick, no-pressure call with a real strategist — here's the plan.</p>
      <div class="fixgrid">
        <div class="fixrow fixrow--win"><span class="fixrow__ic">1</span><span class="fixrow__t">We review your profile &amp; match the strongest funding options</span><span class="fixrow__tag">Personalized</span></div>
        <div class="fixrow fixrow--win"><span class="fixrow__ic">2</span><span class="fixrow__t">Clear terms explained by a real strategist — no hidden fees</span><span class="fixrow__tag">Transparent</span></div>
        <div class="fixrow fixrow--win"><span class="fixrow__ic">3</span><span class="fixrow__t">Approvals in as little as 24–48 hours</span><span class="fixrow__tag">Fast</span></div>
      </div>
      <div class="qr__cta">
        <a href="tel:+10000000000" class="btn btn--sapphire btn--lg">Call now to get started</a>
        <a href="/funding" class="btn btn--outline btn--lg">See funding options</a>
      </div>
      <p class="qr__note qr__note--win">📩 Check your email — we've logged your request and a strategist is already assigned to you.</p>
    </div>

  @else
    {{-- ===== Credit repair recommended (Funding Prep) ===== --}}
    <div class="qr__hero">
      <div class="qr__badge qr__badge--prep">🛠️</div>
      <span class="qr__eyebrow qr__eyebrow--prep">Funding Prep · Almost there</span>
      <h1>{{ $name }}, let's get you <span class="txt-gold">funding-ready first.</span></h1>
      <p>You're close — but lenders would likely decline you today. Fix the items below and you'll qualify for the funding you actually deserve. This is <b>Funding Prep</b>, and it's what we do best.</p>
    </div>

    <div class="qr__card">
      <h2>Your funding-prep checklist</h2>
      <p class="lede">Here's exactly what's standing between you and approval — and we handle every one.</p>
      <div class="fixgrid">
        @forelse ($failed as $f)
          <div class="fixrow fixrow--prep">
            <span class="fixrow__ic">!</span>
            <span class="fixrow__t">{{ $f }}</span>
            <span class="fixrow__tag">We fix this</span>
          </div>
        @empty
          <div class="fixrow fixrow--prep">
            <span class="fixrow__ic">✓</span>
            <span class="fixrow__t">A tailored plan to strengthen your credit profile</span>
            <span class="fixrow__tag">We fix this</span>
          </div>
        @endforelse
      </div>

      <div class="journey">
        <span class="now">You are here</span>
        <span class="arw">→</span> <b>Fix your credit</b>
        <span class="arw">→</span> <b>Funding-ready</b>
        <span class="arw">→</span> <b>Get funded</b>
      </div>

      <div class="qr__cta">
        <a href="/pricing" class="btn btn--gold btn--lg">Start my Credit Repair plan →</a>
        <a href="/credit-repair" class="btn btn--outline btn--lg">How it works</a>
      </div>
      <p class="qr__note qr__note--prep">⏱️ Once these are handled, you're first in line for funding — most clients are funding-ready in just a few months.</p>
    </div>
  @endif

  {{-- ===== Community downsell (both branches) ===== --}}
  @if ($communityUrl)
  <div class="community">
    <span class="hero__stars" style="color:var(--gold-2);letter-spacing:2px">★★★★★</span>
    <h2>Join the free <span class="txt-gold">Credit2Capital</span> community</h2>
    <p>Not ready to move forward yet? Jump into our Telegram — free game on credit &amp; funding, tips, ebooks, and access to the 90-day Funding Accelerator.</p>
    <a href="{{ $communityUrl }}" target="_blank" rel="noopener" class="btn btn--tg btn--lg">Join the Telegram community →</a>
  </div>
  @endif
</div>

@include('partials.footer')

<script src="/script.js"></script>
</body>
</html>
