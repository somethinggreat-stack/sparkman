<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>@yield('code') — Sparkman Solutions</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet" />
<style>
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:linear-gradient(160deg,#0a1f52,#07163c);
    color:#fff;min-height:100vh;display:grid;place-items:center;text-align:center;padding:24px}
  .wrap{max-width:520px}
  .code{font-size:clamp(4.5rem,18vw,8rem);font-weight:800;line-height:1;letter-spacing:-.04em;
    background:linear-gradient(120deg,#f4cd63,#d9a83a);-webkit-background-clip:text;background-clip:text;-webkit-text-fill-color:transparent}
  h1{font-size:1.6rem;font-weight:800;margin:10px 0 12px;letter-spacing:-.02em}
  p{color:#c3d0ef;font-size:1.02rem;line-height:1.6;margin-bottom:28px}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:13px 26px;border-radius:11px;font-weight:700;
    font-size:.95rem;background:linear-gradient(120deg,#d9a83a,#f4cd63);color:#3a2708;text-decoration:none}
  .btn:hover{filter:brightness(1.05)}
  .home{display:block;margin-top:18px;color:#8ea3d6;font-size:.85rem;text-decoration:none}
  .home:hover{color:#c3d0ef}
</style>
</head>
<body>
  <div class="wrap">
    <div class="code">@yield('code', 'Error')</div>
    <h1>@yield('title', 'Something went wrong')</h1>
    <p>@yield('message', 'An error occurred. Please try again.')</p>
    <a href="/" class="btn">← Back to Home</a>
    <a href="/get-funded" class="home">Or start your free credit analysis</a>
  </div>
</body>
</html>
