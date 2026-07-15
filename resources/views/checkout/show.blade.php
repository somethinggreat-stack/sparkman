<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Secure Checkout — {{ $plan['name'] }} | Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,600&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  :root{--navy:#0a1f52;--navy2:#12306e;--gold:#d9a83a;--gold2:#f4cd63;--gold3:#b6862a;--red:#e11d2a;--ink:#0f1c3f;--slate:#5b6b8c;--line:#e6eaf2}
  body{background:#eef3fc;color:var(--ink)}
  .co{max-width:1120px;margin:0 auto;padding:104px 20px 60px}
  .co__bar{display:flex;align-items:center;justify-content:space-between;margin-bottom:22px}
  .co__lock{display:inline-flex;align-items:center;gap:8px;font-weight:700;color:#177245;font-size:.86rem}
  /* hero */
  .co__hero{text-align:center;margin-bottom:26px}
  .pills{display:inline-flex;flex-wrap:wrap;gap:8px;justify-content:center;margin-bottom:16px}
  .pill{display:inline-flex;align-items:center;gap:6px;background:#fff;border:1px solid var(--line);border-radius:100px;padding:6px 14px;font-size:.74rem;font-weight:700;color:var(--navy)}
  .pill b{color:var(--gold3)}
  .co__hero h1{font-family:var(--font-head,'Plus Jakarta Sans');font-weight:800;font-size:clamp(1.9rem,4.4vw,2.9rem);line-height:1.05;letter-spacing:-.03em;color:var(--navy)}
  .co__hero h1 span{color:var(--gold3)}
  .journey{display:inline-flex;align-items:center;gap:16px;margin:16px 0 6px}
  .journey .from{font-size:1.8rem;font-weight:800;color:#b9c2d6;text-decoration:line-through}
  .journey .arrow{color:var(--gold3);font-size:1.4rem}
  .journey .to{font-size:2.6rem;font-weight:800;color:var(--navy);font-family:var(--font-head,inherit)}
  .journey .to small{display:block;font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;color:var(--gold3);font-weight:800}
  .co__hero .cap{color:var(--slate);font-size:.8rem}
  .trust{display:flex;flex-wrap:wrap;gap:18px;justify-content:center;margin-top:14px;color:var(--slate);font-size:.82rem;font-weight:600}
  .trust span{display:inline-flex;align-items:center;gap:6px}
  /* layout */
  .co__grid{display:grid;grid-template-columns:1.35fr .9fr;gap:24px;align-items:start}
  .card2{background:#fff;border:1px solid var(--line);border-radius:18px;padding:24px 26px;margin-bottom:20px;box-shadow:0 26px 60px -46px rgba(10,31,82,.5)}
  .card2__h{display:flex;align-items:center;gap:10px;margin-bottom:18px;font-weight:800;color:var(--navy);font-size:1.05rem}
  .card2__h i{width:26px;height:26px;border-radius:8px;display:grid;place-items:center;background:linear-gradient(135deg,var(--gold2),var(--gold3));color:#3a2708;font-style:normal;font-size:.8rem}
  .g2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .g3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
  .f{margin-bottom:14px}
  .f label{display:block;font-size:.76rem;font-weight:700;color:var(--navy);margin-bottom:6px;text-transform:uppercase;letter-spacing:.02em}
  .f label .r{color:var(--red)}
  .f input,.f select{width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:10px;font-family:inherit;font-size:.95rem;background:#fff}
  .f input:focus,.f select:focus{outline:none;border-color:var(--gold3);box-shadow:0 0 0 4px rgba(217,168,58,.14)}
  .brands{display:flex;gap:6px;margin-left:auto}
  .brand-badge{font-size:.62rem;font-weight:800;color:#fff;padding:3px 7px;border-radius:5px;letter-spacing:.03em}
  .b-visa{background:#1a1f71}.b-mc{background:#eb001b}.b-amex{background:#2e77bc}.b-disc{background:#e8791e}
  .paybar{display:flex;align-items:center;gap:8px;background:#f7f9fd;border:1px solid var(--line);border-radius:10px;padding:11px 14px;font-size:.82rem;color:var(--slate);margin-top:8px}
  .paybar b{color:var(--navy)}
  .minitrust{display:flex;flex-wrap:wrap;gap:14px;margin-top:14px;font-size:.76rem;color:var(--slate);font-weight:600}
  .agree{display:flex;gap:10px;align-items:flex-start;margin-bottom:12px;font-size:.86rem;color:var(--ink)}
  .agree input{margin-top:3px;width:17px;height:17px;flex:none;accent-color:var(--gold3)}
  .agree a{color:var(--gold3);font-weight:700}
  /* plan card */
  .plan2{position:sticky;top:20px;background:linear-gradient(165deg,#0a1f52,#12306e);color:#fff;border-radius:20px;overflow:hidden;box-shadow:0 40px 90px -40px rgba(10,31,82,.6)}
  .plan2__top{padding:26px 26px 8px}
  .plan2__tier{display:inline-block;font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold2);font-weight:800;margin-bottom:8px}
  .plan2__top h2{font-size:1.5rem;font-weight:800;letter-spacing:-.02em}
  .plan2__top p{color:#c3d0ef;font-size:.86rem;margin-top:8px;line-height:1.55}
  .plan2__feats{list-style:none;padding:16px 26px;margin:0;display:grid;gap:9px}
  .plan2__feats li{display:flex;gap:10px;align-items:flex-start;font-size:.86rem;color:#e6ecfb}
  .plan2__feats .ck{width:19px;height:19px;flex:none;border-radius:50%;display:grid;place-items:center;font-size:.62rem;font-weight:800;color:#08351d;background:linear-gradient(140deg,#66d68a,#2fae5f);margin-top:1px}
  .plan2__price{background:rgba(255,255,255,.06);border-top:1px solid rgba(255,255,255,.12);padding:18px 26px}
  .plan2__row{display:flex;justify-content:space-between;padding:7px 0;font-size:.9rem;color:#dbe4fa}
  .plan2__row .free{color:var(--gold2);font-weight:800}
  .plan2__total{display:flex;justify-content:space-between;align-items:center;border-top:1px solid rgba(255,255,255,.14);margin-top:8px;padding-top:14px}
  .plan2__total small{font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:#c3d0ef;font-weight:800}
  .plan2__total b{font-size:2.2rem;font-weight:800}
  .plan2__pay{padding:0 26px 24px}
  .plan2__pay .btn{width:100%;font-size:1rem;padding:16px}
  .plan2__foot{padding:0 26px 24px;color:#9fb0d8;font-size:.72rem;text-align:center;line-height:1.5}
  .co__err{background:#fde1e3;color:#a3121c;padding:12px 14px;border-radius:10px;font-weight:600;font-size:.88rem;margin-bottom:16px}
  .co__note{background:#fff8e6;border:1px solid #f0d38a;color:#7a5a12;padding:12px 14px;border-radius:10px;font-size:.84rem;margin-bottom:16px}
  @media(max-width:900px){.co__grid{grid-template-columns:1fr}.plan2{position:static}}
  @media(max-width:560px){.g2,.g3{grid-template-columns:1fr}}
</style>
</head>
<body>
@include('partials.nav', ['solidNav' => true])
<div class="co">

  <!-- Hero -->
  <div class="co__hero">
    <div class="pills">
      <span class="pill">✔ <b>FCRA</b> Certified Experts</span>
      <span class="pill">📄 Clear Agreements</span>
      <span class="pill">🔒 Secure Checkout</span>
    </div>
    <h1>One Step Away<br /><span>From Better Credit.</span></h1>
    <div class="journey">
      <span class="from">Poor</span>
      <span class="arrow">→</span>
      <span class="to">Elite<small>Where we take you</small></span>
    </div>
    <p class="cap">Done-for-you disputes &amp; credit building · individual results vary</p>
    <div class="trust">
      <span>🛡️ FCRA Certified</span><span>⚡ Same-Day Start</span><span>⭐ 4.9 / 5 Rating</span><span>🔐 256-Bit SSL</span>
    </div>
  </div>

  <form id="paymentForm" method="POST" action="{{ route('checkout.process', $plan['slug']) }}">
    @csrf
    <input type="hidden" name="data_descriptor" id="dataDescriptor" />
    <input type="hidden" name="data_value" id="dataValue" />

    @if ($errors->any())
      <div class="co__err">{{ $errors->first() }}</div>
    @endif
    @if (empty($clientKey) || empty($loginId))
      <div class="co__note">⚙️ Payments aren't fully configured yet (Authorize.Net sandbox keys pending). The form is ready — add the 4 <code>AUTHORIZENET_*</code> keys to <code>.env</code> to accept live test cards.</div>
    @endif

    <div class="co__grid">
      <!-- LEFT -->
      <div>
        <!-- Personal information -->
        <div class="card2">
          <div class="card2__h"><i>1</i> Personal Information</div>
          <div class="g2">
            <div class="f"><label>First Name <span class="r">*</span></label><input name="first_name" value="{{ old('first_name') }}" placeholder="Jane" required /></div>
            <div class="f"><label>Last Name <span class="r">*</span></label><input name="last_name" value="{{ old('last_name') }}" placeholder="Doe" required /></div>
          </div>
          <div class="g2">
            <div class="f"><label>Email Address <span class="r">*</span></label><input type="email" name="email" value="{{ old('email') }}" placeholder="john@example.com" required /></div>
            <div class="f"><label>Phone Number <span class="r">*</span></label><input type="tel" name="phone" value="{{ old('phone') }}" placeholder="(555) 000-0000" required /></div>
          </div>
          <div class="f"><label>Street Address</label><input name="street" value="{{ old('street') }}" placeholder="123 Main Street" /></div>
          <div class="g3">
            <div class="f"><label>City</label><input name="city" value="{{ old('city') }}" placeholder="Your city" /></div>
            <div class="f"><label>State</label>
              <select name="state">
                <option value="">Select</option>
                @foreach (['AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'] as $st)
                  <option value="{{ $st }}" @selected(old('state')===$st)>{{ $st }}</option>
                @endforeach
              </select>
            </div>
            <div class="f"><label>Zip Code</label><input name="zip" value="{{ old('zip') }}" placeholder="10001" /></div>
          </div>
        </div>

        <!-- Payment method -->
        <div class="card2">
          <div class="card2__h"><i>2</i> Payment Method
            <span class="brands"><span class="brand-badge b-visa">VISA</span><span class="brand-badge b-mc">MC</span><span class="brand-badge b-amex">AMEX</span><span class="brand-badge b-disc">DISC</span></span>
          </div>
          <div class="f"><label>Cardholder Name <span class="r">*</span></label><input name="card_name" value="{{ old('card_name') }}" placeholder="Jane Doe" autocomplete="cc-name" required /></div>
          <div class="f"><label>Card Number <span class="r">*</span></label><input id="cardNumber" inputmode="numeric" autocomplete="cc-number" placeholder="•••• •••• •••• ••••" /></div>
          <div class="g3">
            <div class="f"><label>Month <span class="r">*</span></label>
              <select id="cardMonth"><option value="">MM</option>@for($m=1;$m<=12;$m++)<option value="{{ sprintf('%02d',$m) }}">{{ sprintf('%02d',$m) }}</option>@endfor</select>
            </div>
            <div class="f"><label>Year <span class="r">*</span></label>
              <select id="cardYear"><option value="">YYYY</option>@for($y=(int)date('Y');$y<=date('Y')+12;$y++)<option value="{{ $y }}">{{ $y }}</option>@endfor</select>
            </div>
            <div class="f"><label>CVV <span class="r">*</span></label><input id="cardCvv" inputmode="numeric" autocomplete="cc-csc" placeholder="•••" /></div>
          </div>
          <div class="paybar">🔒 <b>Secure payment powered by Authorize.Net.</b> Your card is tokenized — details never touch our servers.</div>
          <div class="minitrust"><span>🛡️ PCI DSS Level 1</span><span>🔐 AES-256 Encrypted</span><span>🏦 Bank-Grade Security</span></div>
        </div>

        <!-- Agreements -->
        <div class="card2">
          <div class="card2__h"><i>3</i> Review &amp; Accept Your Agreements</div>
          <label class="agree"><input type="checkbox" name="terms" value="1" required> I accept the <a href="/terms" target="_blank">Terms of Service</a> and understand the credit repair process. <span class="r">*</span></label>
          <label class="agree"><input type="checkbox" name="privacy" value="1" required> I accept the <a href="/privacy" target="_blank">Privacy Policy</a> and consent to data processing. <span class="r">*</span></label>
          <label class="agree"><input type="checkbox" name="marketing" value="1"> I'd like to receive credit improvement tips &amp; exclusive member offers.</label>
        </div>
      </div>

      <!-- RIGHT: plan summary -->
      <div>
        <div class="plan2">
          <div class="plan2__top">
            <span class="plan2__tier">Your Plan</span>
            <h2>{{ $plan['name'] }}</h2>
            <p>{{ $plan['blurb'] }}</p>
          </div>
          <ul class="plan2__feats">
            @foreach ($plan['features'] as $feat)
              <li><span class="ck">✓</span> {{ $feat }}</li>
            @endforeach
          </ul>
          <div class="plan2__price">
            <div class="plan2__row"><span>{{ $plan['name'] }} — down payment</span><b>${{ number_format($plan['down'], 2) }}</b></div>
            @if (!empty($plan['monthly']))
              <div class="plan2__row"><span>Then monthly</span><b>${{ number_format($plan['monthly'], 2) }}/mo</b></div>
            @endif
            <div class="plan2__row"><span>Setup fee</span><span class="free">FREE</span></div>
            <div class="plan2__total"><small>Due today</small><b>${{ number_format($plan['down'], 2) }}</b></div>
          </div>
          <div class="plan2__pay">
            <button type="submit" class="btn btn--gold" id="payBtn">🔒 Complete Secure Checkout</button>
          </div>
          <div class="plan2__foot">
            By enrolling you agree to our Terms of Service. A confirmation email is sent immediately after checkout. Cancel anytime · your first consultation is always free.
          </div>
        </div>
      </div>
    </div>
  </form>
</div>

@include('partials.footer')

<script src="/script.js"></script>
<script src="{{ $anetEnv === 'production' ? 'https://js.authorize.net/v1/Accept.js' : 'https://jstest.authorize.net/v1/Accept.js' }}" charset="utf-8"></script>
<script>
(function () {
  var form = document.getElementById('paymentForm');
  var btn  = document.getElementById('payBtn');
  var LOGIN_ID = @json($loginId);
  var CLIENT_KEY = @json($clientKey);

  form.addEventListener('submit', function (e) {
    if (document.getElementById('dataValue').value) return; // already tokenized
    e.preventDefault();

    if (!window.Accept || !LOGIN_ID || !CLIENT_KEY) {
      alert('Payment processor not configured yet. Please contact support or try again shortly.');
      return;
    }
    btn.disabled = true; btn.textContent = 'Processing…';

    var secureData = {
      authData: { clientKey: CLIENT_KEY, apiLoginID: LOGIN_ID },
      cardData: {
        cardNumber: document.getElementById('cardNumber').value.replace(/\s/g,''),
        month: document.getElementById('cardMonth').value,
        year: document.getElementById('cardYear').value,
        cardCode: document.getElementById('cardCvv').value
      }
    };
    Accept.dispatchData(secureData, function (response) {
      if (response.messages.resultCode === 'Error') {
        btn.disabled = false; btn.textContent = '🔒 Complete Secure Checkout';
        alert(response.messages.message.map(function(m){return m.text;}).join('\n'));
        return;
      }
      document.getElementById('dataDescriptor').value = response.opaqueData.dataDescriptor;
      document.getElementById('dataValue').value = response.opaqueData.dataValue;
      form.submit();
    });
  });
})();
</script>
</body>
</html>
