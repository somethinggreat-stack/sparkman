<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Login — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;min-height:100vh;display:grid;place-items:center;
    background:radial-gradient(1000px 600px at 50% -10%,#12306e,#07163c);color:#0f1c3f;padding:24px}
  .box{width:100%;max-width:400px;background:#fff;border-radius:20px;padding:38px 34px;box-shadow:0 40px 90px -30px rgba(4,13,38,.6)}
  .logo{display:block;margin:0 auto 22px;height:54px;width:auto}
  h1{font-size:1.5rem;font-weight:800;letter-spacing:-.02em;margin-bottom:4px}
  p.sub{color:#5b6b8c;font-size:.9rem;margin-bottom:26px}
  label{display:block;font-size:.8rem;font-weight:700;color:#0a1f52;margin-bottom:6px;margin-top:16px}
  input{width:100%;padding:12px 14px;border:1px solid #e6eaf2;border-radius:10px;font-family:inherit;font-size:.95rem}
  input:focus{outline:none;border-color:#d9a83a;box-shadow:0 0 0 4px rgba(217,168,58,.15)}
  button{width:100%;margin-top:24px;padding:13px;border:0;border-radius:10px;font-weight:800;font-size:.95rem;cursor:pointer;
    background:linear-gradient(120deg,#b6862a,#f4cd63);color:#3a2708;font-family:inherit}
  .err{background:#fde1e3;color:#a3121c;padding:11px 14px;border-radius:10px;font-size:.85rem;font-weight:600;margin-bottom:8px}
</style>
</head>
<body>
  <form class="box" method="POST" action="{{ route('admin.login.post') }}">
    @csrf
    <img class="logo" src="/images/logo.jpg" alt="Sparkman Solutions" />
    <h1>Admin Dashboard</h1>
    <p class="sub">Sign in to manage leads, payments &amp; clients.</p>

    @if ($errors->any())
      <div class="err">{{ $errors->first() }}</div>
    @endif

    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email') }}" autocomplete="username" required autofocus />

    <label for="password">Password</label>
    <input type="password" id="password" name="password" autocomplete="current-password" required />

    <button type="submit">Sign In</button>
  </form>
</body>
</html>
