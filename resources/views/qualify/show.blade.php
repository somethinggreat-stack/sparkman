<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>See If You're Funding-Ready — Sparkman Solutions</title>
<meta name="description" content="Answer a few quick questions and find out in 60 seconds whether you're ready for business or personal funding — or whether we should get your credit funding-ready first." />
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  body{background:linear-gradient(180deg,#fff,#eef3fc)}
  .qz{padding:164px 20px 0;max-width:720px;margin:0 auto}
  @media(max-width:600px){.qz{padding:124px 16px 0}}
  .qz__head{text-align:center;margin-bottom:22px}
  .qz__eyebrow{display:inline-block;font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;font-weight:800;color:var(--sapphire);background:#eaf1fd;border:1px solid #cddffb;padding:7px 15px;border-radius:100px;margin-bottom:14px}
  .qz__head h1{font-family:var(--font-head);font-weight:800;color:var(--blue-900);letter-spacing:-.02em;font-size:clamp(1.8rem,4.4vw,2.6rem);line-height:1.08}
  .qz__head p{color:var(--slate);margin-top:8px}
  .qz__card{background:#fff;border:1px solid var(--line);border-radius:20px;box-shadow:0 30px 70px -44px rgba(10,31,82,.5);overflow:hidden}
  .qz__bar{height:6px;background:#e9eefb}
  .qz__bar span{display:block;height:100%;width:25%;background:linear-gradient(90deg,var(--sapphire),var(--sapphire-2));transition:width .35s var(--ease)}
  .qz__body{padding:30px 32px 28px}
  .qz__step{display:none}
  .qz__step.active{display:block;animation:qzin .35s var(--ease)}
  @keyframes qzin{from{opacity:0;transform:translateX(14px)}to{opacity:1;transform:none}}
  .qz__stepnum{font-size:.74rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:var(--sapphire);margin-bottom:6px}
  .qz__step h2{font-size:1.35rem;font-weight:800;color:var(--blue-900);margin-bottom:4px;letter-spacing:-.01em}
  .qz__step .sub{color:var(--slate);font-size:.9rem;margin-bottom:20px}
  .qz-row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .qf{margin-bottom:15px}
  .qf label.lbl{display:block;font-size:.82rem;font-weight:700;color:var(--blue-900);margin-bottom:7px}
  .qf input,.qf select{width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:10px;font-family:inherit;font-size:.95rem;background:#fff}
  .qf input:focus,.qf select:focus{outline:none;border-color:var(--sapphire);box-shadow:0 0 0 4px rgba(15,82,186,.12)}
  .qz-q{padding:14px 0;border-bottom:1px solid var(--line)}
  .qz-q:last-child{border-bottom:0}
  .qz-q .qtxt{font-weight:700;color:var(--blue-900);font-size:.94rem;margin-bottom:9px}
  .qz-yn{display:flex;gap:10px}
  .qz-pill{flex:1;display:flex;align-items:center;justify-content:center;gap:6px;padding:11px;border:1.5px solid var(--line);border-radius:10px;cursor:pointer;font-weight:700;color:var(--slate);transition:.18s;user-select:none}
  .qz-pill input{display:none}
  .qz-pill:hover{border-color:#cddffb}
  .qz-pill.sel{border-color:var(--sapphire);background:#eaf1fd;color:var(--sapphire)}
  .qz-choices{display:grid;gap:10px}
  .qz-choice{display:flex;align-items:center;gap:12px;padding:13px 16px;border:1.5px solid var(--line);border-radius:12px;cursor:pointer;font-weight:600;color:var(--blue-900);transition:.18s}
  .qz-choice input{display:none}
  .qz-choice:hover{border-color:#cddffb}
  .qz-choice.sel{border-color:var(--sapphire);background:#eaf1fd}
  .qz__nav{display:flex;justify-content:space-between;gap:12px;margin-top:24px}
  .qz__back{background:#fff;border:1px solid var(--line);color:var(--blue-900);padding:12px 22px;border-radius:100px;font-weight:700;cursor:pointer;font-family:inherit}
  .qz__back[hidden]{visibility:hidden}
  .qz__next{margin-left:auto;background:linear-gradient(120deg,var(--sapphire),var(--sapphire-2));color:#fff;border:0;padding:13px 30px;border-radius:100px;font-weight:800;cursor:pointer;font-family:inherit;font-size:.95rem}
  .qz__err{color:var(--red);font-size:.82rem;font-weight:600;margin-top:12px;min-height:1em}
  .qz__trust{display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin:24px 0 72px;color:var(--slate);font-size:.82rem;font-weight:600}
  .qz__opt{font-size:.78rem;color:var(--slate);margin-top:-8px;margin-bottom:16px}
  @media(max-width:560px){.qz-row{grid-template-columns:1fr}}
</style>
</head>
<body>

@include('partials.nav', ['solidNav' => true])

<div class="qz">
  <div class="qz__head">
    <span class="qz__eyebrow">Funding readiness check · 60 seconds</span>
    <h1>See if you're <span style="color:var(--sapphire)">funding-ready.</span></h1>
    <p>Answer a few quick questions. We'll tell you if you qualify for funding now — or get your credit funding-ready first.</p>
  </div>

  <div class="qz__card">
    <div class="qz__bar"><span id="qzBar"></span></div>
    <div class="qz__body">
      <form id="qzForm" method="POST" action="{{ route('qualify.submit') }}" novalidate>
        @csrf

        <!-- Step 1: Contact -->
        <div class="qz__step active" data-step="0">
          <div class="qz__stepnum">Step 1 of 4</div>
          <h2>Let's get you started</h2>
          <p class="sub">Where should we send your funding results?</p>
          <div class="qz-row">
            <div class="qf"><label class="lbl">First Name</label><input class="qreq" type="text" name="first_name" placeholder="Jane" /></div>
            <div class="qf"><label class="lbl">Last Name</label><input class="qreq" type="text" name="last_name" placeholder="Doe" /></div>
          </div>
          <div class="qf"><label class="lbl">Email Address</label><input class="qreq" type="email" name="email" placeholder="you@email.com" /></div>
          <div class="qf"><label class="lbl">Phone Number</label><input class="qreq" type="tel" name="phone" inputmode="numeric" maxlength="11" pattern="\d{11}" title="Enter your 11-digit phone number" placeholder="Enter 11 digits" /></div>
        </div>

        <!-- Step 2: Goal -->
        <div class="qz__step" data-step="1">
          <div class="qz__stepnum">Step 2 of 4</div>
          <h2>What are you after?</h2>
          <p class="sub">Tell us what you're trying to fund.</p>
          <div class="qz-choices" data-group="funding_goal">
            <label class="qz-choice"><input type="radio" name="funding_goal" value="business"> 🏢 Business funding</label>
            <label class="qz-choice"><input type="radio" name="funding_goal" value="personal"> 👤 Personal funding</label>
            <label class="qz-choice"><input type="radio" name="funding_goal" value="both"> ✨ Both</label>
          </div>
          <div class="qf" style="margin-top:16px"><label class="lbl">How much are you looking for?</label>
            <select class="qreq" name="amount">
              <option value="">Select an amount</option>
              <option>Under $25,000</option><option>$25,000 – $75,000</option>
              <option>$75,000 – $150,000</option><option>$150,000 – $500,000</option>
              <option>Not sure yet</option>
            </select>
          </div>
        </div>

        <!-- Step 3: Credit snapshot -->
        <div class="qz__step" data-step="2">
          <div class="qz__stepnum">Step 3 of 4</div>
          <h2>Your credit snapshot</h2>
          <p class="sub">This tells us if you're funding-ready. Be honest — it only helps us route you right.</p>

          <div class="qz-q">
            <div class="qtxt">What's your credit score range?</div>
            <div class="qz-yn" data-group="credit_score">
              <label class="qz-pill"><input type="radio" name="credit_score" value="680+"> 680+</label>
              <label class="qz-pill"><input type="radio" name="credit_score" value="under-680"> Under 680</label>
            </div>
          </div>
          @foreach ([
            'cards_3500'  => ["Do you have 2+ credit cards with a \$3,500+ limit?", '✓ Yes', '✕ No'],
            'open_cards'  => ['Do you have 4–5 open credit card accounts?', '✓ Yes', '✕ No'],
            'credit_age'  => ["What's your average credit age?", '5+ years', 'Under 5 years'],
            'clean'       => ['Is your report free of collections & charge-offs?', '✓ Yes', '✕ No'],
            'inquiries'   => ['Fewer than 3 hard inquiries in the last while?', '✓ Yes', '✕ No'],
            'utilization' => ['Is your credit utilization below 30%?', '✓ Yes', '✕ No'],
          ] as $name => $cfg)
            <div class="qz-q">
              <div class="qtxt">{{ $cfg[0] }}</div>
              <div class="qz-yn" data-group="{{ $name }}">
                <label class="qz-pill"><input type="radio" name="{{ $name }}" value="yes"> {{ $cfg[1] }}</label>
                <label class="qz-pill"><input type="radio" name="{{ $name }}" value="no"> {{ $cfg[2] }}</label>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Step 4: Business (optional) -->
        <div class="qz__step" data-step="3">
          <div class="qz__stepnum">Step 4 of 4</div>
          <h2>Your business <span style="color:var(--slate);font-weight:600;font-size:.9rem">(optional)</span></h2>
          <p class="qz__opt">Only if you're seeking business funding — an LLC 2+ years old helps but isn't required.</p>
          <div class="qf"><label class="lbl">Business name</label><input type="text" name="business_name" placeholder="Optional" /></div>
          <div class="qz-row">
            <div class="qf"><label class="lbl">Business age</label>
              <select name="business_age"><option value="">Select</option><option>Startup / not formed yet</option><option>Under 1 year</option><option>1–2 years</option><option>2+ years</option></select>
            </div>
            <div class="qf"><label class="lbl">Monthly revenue</label>
              <select name="monthly_revenue"><option value="">Select</option><option>Startup / pre-revenue</option><option>Under $10k</option><option>$10k – $50k</option><option>$50k+</option></select>
            </div>
          </div>
          <div class="qz-row">
            <div class="qf"><label class="lbl">Business website?</label>
              <select name="has_website"><option value="">Select</option><option>yes</option><option>no</option></select>
            </div>
            <div class="qf"><label class="lbl">EIN / D&amp;B set up?</label>
              <select name="has_ein"><option value="">Select</option><option>yes</option><option>no</option></select>
            </div>
          </div>
        </div>

        <div class="qz__err" id="qzErr"></div>
        <div class="qz__nav">
          <button type="button" class="qz__back" id="qzBack" hidden>← Back</button>
          <button type="button" class="qz__next" id="qzNext">Continue →</button>
          <button type="submit" class="qz__next" id="qzSubmit" style="display:none">See my results →</button>
        </div>
      </form>
    </div>
  </div>

  <div class="qz__trust">
    <span>🔒 256-bit encrypted</span><span>⚡ Instant result</span><span>✅ No hard credit pull</span>
  </div>
</div>

@include('partials.footer')

<script src="/script.js"></script>
<script>
(function () {
  var steps = Array.prototype.slice.call(document.querySelectorAll('.qz__step'));
  var cur = 0, total = steps.length;
  var bar = document.getElementById('qzBar'), err = document.getElementById('qzErr');
  var back = document.getElementById('qzBack'), next = document.getElementById('qzNext'), submit = document.getElementById('qzSubmit');
  var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  function render() {
    steps.forEach(function (s, i) { s.classList.toggle('active', i === cur); });
    bar.style.width = ((cur + 1) / total * 100) + '%';
    back.hidden = cur === 0;
    var last = cur === total - 1;
    next.style.display = last ? 'none' : '';
    submit.style.display = last ? '' : 'none';
    err.textContent = '';
  }

  // Phone: allow digits only, cap at 11.
  var phone = document.querySelector('input[name="phone"]');
  if (phone) {
    phone.addEventListener('input', function () {
      var d = phone.value.replace(/\D/g, '').slice(0, 11);
      if (d !== phone.value) phone.value = d;
    });
  }

  function validate() {
    var step = steps[cur];
    // text/select inputs marked .qreq
    var ok = true;
    step.querySelectorAll('.qreq').forEach(function (f) {
      if (!f.value.trim()) ok = false;
      if (f.type === 'email' && !emailRe.test(f.value)) ok = false;
      if (f.name === 'phone' && f.value.replace(/\D/g, '').length !== 11) ok = false;
    });
    // radio groups
    step.querySelectorAll('[data-group]').forEach(function (g) {
      var name = g.getAttribute('data-group');
      if (!step.querySelector('input[name="' + name + '"]:checked')) ok = false;
    });
    return ok;
  }

  function errMsg() {
    if (phone && steps[cur].contains(phone) && phone.value.replace(/\D/g, '').length !== 11) {
      return 'Please enter your 11-digit phone number.';
    }
    return 'Please answer everything on this step to continue.';
  }

  next.addEventListener('click', function () {
    if (!validate()) { err.textContent = errMsg(); return; }
    if (cur < total - 1) { cur++; render(); window.scrollTo({ top: 0, behavior: 'smooth' }); }
  });
  back.addEventListener('click', function () { if (cur > 0) { cur--; render(); } });

  // pill / choice selected styling
  document.querySelectorAll('.qz-yn, .qz-choices').forEach(function (grp) {
    grp.addEventListener('change', function () {
      grp.querySelectorAll('.qz-pill, .qz-choice').forEach(function (p) {
        var input = p.querySelector('input');
        p.classList.toggle('sel', input.checked);
      });
    });
  });

  document.getElementById('qzForm').addEventListener('submit', function (e) {
    if (!validate()) { e.preventDefault(); err.textContent = 'Please answer everything on this step.'; }
  });

  render();
})();
</script>
</body>
</html>
