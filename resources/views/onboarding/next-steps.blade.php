<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>What happens next — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="/styles.css" />
<style>
  body{background:linear-gradient(180deg,#fff,#eef3fc)}
  .ns{padding:110px 20px 0;max-width:1180px;margin:0 auto}
  .ns__hero{text-align:center;margin:6px auto 30px;max-width:640px}
  .ns__check{width:64px;height:64px;border-radius:50%;display:grid;place-items:center;font-size:1.8rem;margin:0 auto 16px;
    background:linear-gradient(135deg,#2fae5f,#1c8a49);color:#fff;box-shadow:0 16px 34px -14px rgba(28,138,73,.6)}
  .ns__hero h1{font-size:2rem;font-weight:800;color:var(--blue-900);letter-spacing:-.02em}
  .ns__hero p{color:var(--slate);margin-top:8px;font-size:1.02rem}
</style>
</head>
<body>

@include('partials.nav', ['solidNav' => true])

<div class="ns">
  <div class="ns__hero">
    <div class="ns__check">✓</div>
    <h1>You're all set 🎉</h1>
    <p>Your account is activated — you're one step closer to transforming your credit.</p>
  </div>

  @include('partials.what-next')
</div>

@include('partials.footer')

<script src="/script.js"></script>
</body>
</html>
