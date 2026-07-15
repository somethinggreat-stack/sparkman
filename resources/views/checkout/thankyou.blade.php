<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>Thank You — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
</head>
<body style="background:linear-gradient(180deg,#fff,#eef3fc)">

@include('partials.nav', ['solidNav' => true])

<div style="min-height:60vh;display:grid;place-items:center;padding:140px 24px 60px;text-align:center">
  <div>
    <h1 style="font-size:2rem;color:var(--blue-900);font-weight:800">Payment received 🎉</h1>
    <p style="color:var(--slate);margin:12px 0 24px">Thanks, {{ $payment->name }} — your ${{ number_format($payment->amount, 2) }} payment was successful.</p>
    @if ($payment->onboarding_token && ! $payment->onboarded_at)
      <a class="btn btn--gold btn--lg" href="{{ route('onboarding.show', $payment->onboarding_token) }}">Complete your onboarding →</a>
    @else
      <a class="btn btn--gold btn--lg" href="/">Back to home</a>
    @endif
  </div>
</div>

@include('partials.footer')

<script src="/script.js"></script>
</body>
</html>
