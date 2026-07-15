<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Your Information — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  body{background:linear-gradient(180deg,#fff,#eef3fc)}
  .ob{padding:110px 20px 0}
  .ob__wrap{max-width:1180px;margin:0 auto}
  .ob__form-wrap{max-width:760px;margin:0 auto}
  .paid{background:linear-gradient(135deg,#177245,#1c8a49);color:#fff;border-radius:18px;padding:24px 26px;text-align:center;margin:0 auto 30px;max-width:760px;box-shadow:0 24px 60px -34px rgba(28,138,73,.7)}
  .paid__check{width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,.16);display:grid;place-items:center;font-size:1.5rem;margin:0 auto 12px}
  .paid h1{font-size:1.6rem;font-weight:800;letter-spacing:-.02em}
  .paid p{color:#d7f2e2;margin-top:6px;font-size:.98rem}
  .mustfill{display:flex;gap:12px;align-items:flex-start;background:#0a1f52;color:#fff;border-radius:16px;padding:20px 24px;margin:34px auto 24px;max-width:760px}
  .mustfill .ic{font-size:1.4rem}
  .mustfill b{color:var(--gold-2)}
  .mustfill p{color:#dbe4fa;font-size:.95rem;line-height:1.55;margin-top:2px}
  .ob__head{text-align:center;margin-bottom:22px}
  .ob__head h2{font-size:1.9rem;font-weight:800;color:var(--blue-900);letter-spacing:-.02em}
  .ob__head p{color:var(--slate);margin-top:6px}
  .ob__req{font-size:.82rem;color:var(--slate);margin-top:8px}
  .ob__req b{color:var(--red)}
  .sect{background:#fff;border:1px solid var(--line);border-radius:18px;padding:26px 28px;margin-bottom:18px;box-shadow:0 24px 60px -46px rgba(10,31,82,.5)}
  .sect__h{display:flex;align-items:center;gap:12px;margin-bottom:18px}
  .sect__n{width:34px;height:34px;flex:none;border-radius:10px;display:grid;place-items:center;font-weight:800;color:#3a2708;background:linear-gradient(135deg,var(--gold-2),var(--gold-3))}
  .sect__h h2{font-size:1.15rem;font-weight:800;color:var(--blue-900)}
  .grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .grid3{display:grid;grid-template-columns:1fr 1fr 1fr;gap:14px}
  .fld{margin-bottom:14px}
  .fld label{display:block;font-size:.82rem;font-weight:700;color:var(--blue-900);margin-bottom:6px}
  .fld label .req{color:var(--red)}
  .fld input,.fld select{width:100%;padding:12px 14px;border:1px solid var(--line);border-radius:10px;font-family:inherit;font-size:.95rem;background:#fff}
  .fld input:focus,.fld select:focus{outline:none;border-color:var(--gold-3);box-shadow:0 0 0 4px rgba(217,168,58,.14)}
  .fld small{display:block;color:var(--slate);font-size:.78rem;margin-top:5px}
  .verify-note{background:#0a1f52;color:#dbe4fa;border-radius:12px;padding:14px 16px;font-size:.85rem;margin-bottom:18px;display:flex;gap:10px}
  .verify-note b{color:var(--gold-2)}
  .ob__err{background:#fde1e3;color:#a3121c;padding:12px 14px;border-radius:10px;font-weight:600;font-size:.88rem;margin-bottom:16px}
  .phone-row{display:flex;gap:10px}
  .phone-row .cc{flex:none;width:88px;display:grid;place-items:center;border:1px solid var(--line);border-radius:10px;font-weight:700;color:var(--blue-900);background:#f7f9fd}
  @media(max-width:620px){.grid2,.grid3{grid-template-columns:1fr}}
</style>
</head>
<body>

@include('partials.nav', ['solidNav' => true])

<div class="ob">
  <div class="ob__wrap">

    <!-- Payment success -->
    <div class="paid">
      <div class="paid__check">✓</div>
      <h1>Your payment went through 🎉</h1>
      <p>You're one step closer to transforming your credit.</p>
    </div>

    <!-- Here's what happens next (5 cards) -->
    @include('partials.what-next')

    <!-- Required notice -->
    <div class="mustfill">
      <span class="ic">📝</span>
      <div>
        <b>Please complete the onboarding form below.</b>
        <p>You <b style="color:#fff">must</b> fill out this form in order for us to start working on your credit. It gives us what the bureaus require to pull your file and submit disputes on your behalf.</p>
      </div>
    </div>

    <div class="ob__form-wrap">
      <div class="ob__head">
        <h2>Your information</h2>
        <p>Tell me who's about to win.</p>
        <p class="ob__req">Fields with <b>*</b> are required</p>
      </div>

      @if ($errors->any())
        <div class="ob__err">{{ $errors->first() }}</div>
      @endif

      @php $addr = data_get($payment->meta, 'address', []); @endphp
      <form method="POST" action="{{ route('onboarding.store', $token) }}">
        @csrf

        <!-- 01 Identity -->
        <div class="sect">
          <div class="sect__h"><span class="sect__n">01</span><h2>Identity</h2></div>
          <div class="grid2">
            <div class="fld"><label>First Name <span class="req">*</span></label><input name="first_name" value="{{ old('first_name', $payment->name ? explode(' ', $payment->name)[0] : '') }}" placeholder="Jane" required /></div>
            <div class="fld"><label>Last Name <span class="req">*</span></label><input name="last_name" value="{{ old('last_name') }}" placeholder="Smith" required /></div>
          </div>
          <div class="grid2">
            <div class="fld"><label>Middle Name</label><input name="middle_name" value="{{ old('middle_name') }}" placeholder="Optional" /></div>
            <div class="fld"><label>Suffix</label>
              <select name="suffix">
                <option value="">None</option>
                <option>Jr.</option><option>Sr.</option><option>II</option><option>III</option><option>IV</option>
              </select>
            </div>
          </div>
        </div>

        <!-- 02 Contact -->
        <div class="sect">
          <div class="sect__h"><span class="sect__n">02</span><h2>Contact</h2></div>
          <div class="fld">
            <label>Email Address <span class="req">*</span></label>
            <input type="email" name="email" value="{{ old('email', $payment->email) }}" placeholder="you@email.com" required />
            <small>We'll send your portal access here.</small>
          </div>
          <div class="fld">
            <label>Phone Number <span class="req">*</span></label>
            <div class="phone-row">
              <div class="cc">🇺🇸 +1</div>
              <input type="tel" name="phone" value="{{ old('phone', $payment->phone) }}" placeholder="(555) 123-4567" required style="flex:1" />
            </div>
            <small>10-digit US number. We text appointment reminders only.</small>
          </div>
        </div>

        <!-- 03 Address -->
        <div class="sect">
          <div class="sect__h"><span class="sect__n">03</span><h2>Address</h2></div>
          <div class="fld"><label>Street Address</label><input name="street" value="{{ old('street', $addr['street'] ?? '') }}" placeholder="123 Main Street, Apt 4B" /></div>
          <div class="grid3">
            <div class="fld"><label>City</label><input name="city" value="{{ old('city', $addr['city'] ?? '') }}" placeholder="Your city" /></div>
            <div class="fld"><label>State</label>
              <select name="state">
                <option value="">Select state</option>
                @foreach (['AL','AK','AZ','AR','CA','CO','CT','DE','DC','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY'] as $st)
                  <option value="{{ $st }}" @selected(old('state', $addr['state'] ?? '')===$st)>{{ $st }}</option>
                @endforeach
              </select>
            </div>
            <div class="fld"><label>Zip Code</label><input name="zip" value="{{ old('zip', $addr['zip'] ?? '') }}" placeholder="12345" /></div>
          </div>
        </div>

        <!-- 04 Verification -->
        <div class="sect">
          <div class="sect__h"><span class="sect__n">04</span><h2>Verification</h2></div>
          <div class="verify-note">
            <span>🔒</span>
            <div><b>256-bit encrypted.</b> We need these to pull your credit file and submit disputes to the bureaus on your behalf. Your data never leaves our secure system.</div>
          </div>
          <div class="fld">
            <label>Full Social Security Number <span class="req">*</span></label>
            <input name="ssn" value="{{ old('ssn') }}" placeholder="XXX-XX-XXXX" inputmode="numeric" required />
            <small>Required by the credit bureaus to identify your file.</small>
          </div>
          <div class="fld">
            <label>Date of Birth <span class="req">*</span></label>
            <div class="grid3">
              <select name="dob_month" required>
                <option value="">Month</option>
                @foreach (['January','February','March','April','May','June','July','August','September','October','November','December'] as $i => $m)
                  <option value="{{ $i+1 }}" @selected((int)old('dob_month')===$i+1)>{{ $m }}</option>
                @endforeach
              </select>
              <select name="dob_day" required>
                <option value="">Day</option>
                @for ($d = 1; $d <= 31; $d++)<option value="{{ $d }}" @selected((int)old('dob_day')===$d)>{{ $d }}</option>@endfor
              </select>
              <select name="dob_year" required>
                <option value="">Year</option>
                @for ($y = date('Y') - 18; $y >= 1940; $y--)<option value="{{ $y }}" @selected((int)old('dob_year')===$y)>{{ $y }}</option>@endfor
              </select>
            </div>
            <small>You must be 18 or older to enroll.</small>
          </div>
        </div>

        <button type="submit" class="btn btn--gold btn--lg btn--block">Submit &amp; activate my account →</button>
        <p style="text-align:center;color:var(--slate);font-size:.8rem;margin:12px 0 6px">
          By submitting, you agree to our <a href="/privacy" target="_blank" style="color:var(--gold-3);font-weight:700">Privacy Policy</a>, <a href="/terms" target="_blank" style="color:var(--gold-3);font-weight:700">Terms of Service</a>, and <a href="/disclaimer" target="_blank" style="color:var(--gold-3);font-weight:700">Disclaimer</a>.
        </p>
      </form>
    </div>
  </div>
</div>

@include('partials.footer')

<script src="/script.js"></script>
</body>
</html>
