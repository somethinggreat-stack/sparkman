<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Service Agreement — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  body{background:linear-gradient(180deg,#fff,#eef3fc)}
  .agr{padding:150px 20px 0;max-width:860px;margin:0 auto}
  @media(max-width:600px){.agr{padding:120px 16px 0}}
  .agr__paid{display:flex;align-items:center;gap:12px;justify-content:center;background:#e8f7ee;border:1px solid #bfe6cd;color:#177245;border-radius:12px;padding:12px 18px;font-weight:700;font-size:.92rem;margin-bottom:22px}
  .agr__head{text-align:center;margin-bottom:24px}
  .agr__eyebrow{display:inline-block;font-size:.72rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase;color:var(--gold-3);background:#fff;border:1px solid rgba(217,168,58,.32);padding:7px 15px;border-radius:100px;margin-bottom:16px}
  .agr__head h1{font-family:var(--font-head);font-weight:800;color:var(--blue-900);letter-spacing:-.02em;font-size:clamp(1.6rem,3.6vw,2.2rem);line-height:1.1}
  .agr__head p{color:var(--slate);margin-top:10px}
  .agr__doc{background:#fff;border:1px solid var(--line);border-radius:18px;padding:34px 38px;box-shadow:0 30px 70px -50px rgba(10,31,82,.5)}
  @media(max-width:600px){.agr__doc{padding:24px 20px}}
  .agr__meta{display:flex;flex-wrap:wrap;gap:22px;padding-bottom:18px;margin-bottom:8px;border-bottom:1px solid var(--line)}
  .agr__meta div{flex:1 1 200px}
  .agr__meta .k{font-size:.72rem;font-weight:800;letter-spacing:.06em;text-transform:uppercase;color:var(--slate)}
  .agr__meta .v{font-size:1.05rem;font-weight:700;color:var(--blue-900);margin-top:3px}
  .agr__doc h2{font-size:1.02rem;font-weight:800;color:var(--blue-900);margin:22px 0 6px}
  .agr__doc p{color:var(--ink);font-size:.94rem;line-height:1.62;margin:0 0 6px}
  .agr__doc ul{margin:4px 0 6px;padding-left:20px}
  .agr__doc li{color:var(--ink);font-size:.94rem;line-height:1.55;margin-bottom:3px}
  .agr__title{font-family:var(--font-head);font-size:1.3rem;font-weight:800;color:var(--blue-900);text-align:center;margin-bottom:18px}

  .agr__sign{margin-top:26px;display:grid;grid-template-columns:1fr 1fr;gap:26px}
  @media(max-width:640px){.agr__sign{grid-template-columns:1fr}}
  .sigbox{}
  .sigbox .lbl{font-size:.74rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:var(--slate);margin-bottom:8px;display:block}
  .sigpad-wrap{position:relative;border:2px dashed #b9c4dc;border-radius:12px;background:#fbfcfe;height:180px;overflow:hidden}
  #sigPad{touch-action:none;cursor:crosshair;display:block;width:100%;height:100%}
  .sigpad-hint{position:absolute;inset:0;display:grid;place-items:center;color:#9aa8c6;font-size:.9rem;pointer-events:none}
  .sigpad-line{position:absolute;left:18px;right:18px;bottom:42px;border-bottom:1.5px solid #cdd6ea}
  .sigclear{margin-top:8px;font-size:.82rem;font-weight:700;color:var(--sapphire);background:none;border:0;cursor:pointer;padding:0}
  .sig-name{margin-top:6px;font-size:.9rem;color:var(--slate)}
  .sig-name b{color:var(--blue-900)}
  .rep-sig{height:180px;border:2px solid var(--line);border-radius:12px;background:#fff;display:flex;flex-direction:column;justify-content:center;padding:0 20px}
  .rep-sig .script{font-family:'Dancing Script',cursive;font-size:2.6rem;color:var(--blue-900);line-height:1}
  .rep-sig .auto{font-size:.72rem;font-weight:700;color:#177245;background:#e8f7ee;border-radius:100px;padding:3px 10px;display:inline-block;margin-top:10px;align-self:flex-start}

  .agr__consent{display:flex;gap:12px;align-items:flex-start;background:#f4f8ff;border:1px solid #cddffb;border-radius:12px;padding:16px 18px;margin-top:26px}
  .agr__consent input{width:20px;height:20px;margin-top:2px;flex:none;accent-color:var(--sapphire)}
  .agr__consent label{font-size:.92rem;color:var(--ink);line-height:1.5}
  .agr__err{display:none;background:#fde1e3;border:1px solid #f5b5ba;color:#a3121c;border-radius:10px;padding:11px 15px;font-weight:600;font-size:.9rem;margin-top:16px}
  .agr__submit{margin:22px 0 60px;text-align:center}
  .agr__submit .btn{min-width:280px}
  .agr__submit .note{color:var(--slate);font-size:.82rem;margin-top:12px}
</style>
</head>
<body>

@include('partials.nav', ['solidNav' => true])

<div class="agr">
  <div class="agr__paid">✓ Payment received — one last step before we begin.</div>

  <div class="agr__head">
    <span class="agr__eyebrow">Required · Digital signature</span>
    <h1>Sign your Credit Repair Service Agreement</h1>
    <p>Please read and sign below. Your electronic signature is legally binding. Once signed, you'll move straight to onboarding.</p>
  </div>

  @if ($errors->any())
    <div class="agr__err" style="display:block">{{ $errors->first() }}</div>
  @endif

  <form id="agreeForm" method="POST" action="{{ route('agreement.sign', $token) }}">
    @csrf
    <input type="hidden" name="signature" id="sigData" />

    <div class="agr__doc">
      <div class="agr__title">Credit2Capitals Credit Repair Service Agreement</div>

      <div class="agr__meta">
        <div><div class="k">Client Name</div><div class="v">{{ $payment->name }}</div></div>
        <div><div class="k">Date</div><div class="v">{{ now()->format('F j, Y') }}</div></div>
      </div>

      <h2>Services</h2>
      <p>Credit2Capitals agrees to provide credit analysis, disputes of inaccurate, unverifiable, or misleading information, credit education, profile optimization guidance, and funding-readiness preparation.</p>

      <h2>Fees</h2>
      <p>Client agrees to pay the fee shown on the invoice, payment authorization, or service proposal. This agreement is not tied to a fixed price.</p>

      <h2>90-Day Results Guarantee</h2>
      <p>If no qualifying result is achieved within ninety (90) days after onboarding is complete and the client fully complies with this agreement, the client may request a full refund of credit repair fees.</p>
      <p>Qualifying results include at least one documented improvement such as removal/correction of an inaccurate negative account, qualifying inquiry removal, correction of inaccurate personal information, correction/removal of late payments, collections or charge-offs, or another documented improvement resulting directly from Credit2Capitals' services.</p>

      <h2>Client Responsibilities</h2>
      <ul>
        <li>Provide accurate information and requested documents.</li>
        <li>Maintain active credit monitoring throughout the program.</li>
        <li>Respond promptly to requests.</li>
        <li>Make payments on time.</li>
        <li>Avoid applying for new credit without consulting Credit2Capitals.</li>
      </ul>

      <h2>No Guarantee</h2>
      <p>Credit2Capitals does not guarantee a specific credit score, funding approval, or deletion of every account because credit reporting agencies and furnishers make final decisions.</p>

      <h2>General Terms</h2>
      <ul>
        <li>Electronic signatures are binding.</li>
        <li>Confidentiality applies to both parties.</li>
        <li>Disputes will be resolved by binding arbitration where permitted by law.</li>
        <li>Prevailing party may recover allowable attorney's fees and collection costs.</li>
        <li>Severability and entire agreement clauses apply.</li>
      </ul>

      <div class="agr__sign">
        <div class="sigbox">
          <span class="lbl">Client Signature</span>
          <div class="sigpad-wrap">
            <canvas id="sigPad"></canvas>
            <div class="sigpad-line"></div>
            <div class="sigpad-hint" id="sigHint">✍️ Sign here — finger or mouse</div>
          </div>
          <button type="button" class="sigclear" id="sigClear">Clear signature</button>
          <div class="sig-name"><b>{{ $payment->name }}</b> · {{ now()->format('M j, Y') }}</div>
        </div>

        <div class="sigbox">
          <span class="lbl">Credit2Capitals Representative</span>
          <div class="rep-sig">
            <span class="script">{{ $representative }}</span>
            <span class="auto">✓ Signed on behalf of Credit2Capitals</span>
          </div>
          <div class="sig-name"><b>{{ $representative }}</b> · {{ now()->format('M j, Y') }}</div>
        </div>
      </div>

      <div class="agr__consent">
        <input type="checkbox" name="agree" id="agreeChk" value="1" />
        <label for="agreeChk">I have read and agree to the Credit2Capitals Credit Repair Service Agreement above. I understand that my electronic signature is legally binding and has the same effect as a handwritten signature.</label>
      </div>

      <div class="agr__err" id="agreeErr"></div>
    </div>

    <div class="agr__submit">
      <button type="submit" class="btn btn--gold btn--lg">Sign &amp; Continue to Onboarding →</button>
      <p class="note">🔒 Encrypted &amp; time-stamped · You cannot proceed without signing.</p>
    </div>
  </form>
</div>

@include('partials.footer')

<script src="/script.js"></script>
<script>
(function () {
  var canvas = document.getElementById('sigPad');
  var wrap   = canvas.parentElement;
  var hint   = document.getElementById('sigHint');
  var ctx    = canvas.getContext('2d');
  var drawing = false, hasInk = false, last = null;

  function setup() {
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    var w = wrap.clientWidth, h = wrap.clientHeight;
    canvas.width  = w * ratio;
    canvas.height = h * ratio;
    ctx.scale(ratio, ratio);
    ctx.lineWidth = 2.5; ctx.lineCap = 'round'; ctx.lineJoin = 'round'; ctx.strokeStyle = '#0a1f52';
  }
  setup();

  function pos(e) {
    var r = canvas.getBoundingClientRect();
    return { x: e.clientX - r.left, y: e.clientY - r.top };
  }
  canvas.addEventListener('pointerdown', function (e) {
    drawing = true; last = pos(e);
    if (hint) hint.style.display = 'none';
    try { canvas.setPointerCapture(e.pointerId); } catch (err) {}
  });
  canvas.addEventListener('pointermove', function (e) {
    if (!drawing) return;
    var p = pos(e);
    ctx.beginPath(); ctx.moveTo(last.x, last.y); ctx.lineTo(p.x, p.y); ctx.stroke();
    last = p; hasInk = true;
  });
  function stop() { drawing = false; }
  canvas.addEventListener('pointerup', stop);
  canvas.addEventListener('pointercancel', stop);
  canvas.addEventListener('pointerleave', stop);

  document.getElementById('sigClear').addEventListener('click', function () {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    hasInk = false;
    if (hint) hint.style.display = '';
  });

  document.getElementById('agreeForm').addEventListener('submit', function (e) {
    var err = document.getElementById('agreeErr');
    err.style.display = 'none';
    if (!hasInk) {
      e.preventDefault();
      err.textContent = 'Please sign in the box above to continue.';
      err.style.display = 'block';
      canvas.scrollIntoView({ behavior: 'smooth', block: 'center' });
      return;
    }
    if (!document.getElementById('agreeChk').checked) {
      e.preventDefault();
      err.textContent = 'Please check the box to agree to the terms.';
      err.style.display = 'block';
      return;
    }
    document.getElementById('sigData').value = canvas.toDataURL('image/png');
  });
})();
</script>
</body>
</html>
