@php
    use App\Models\Lead;
    use App\Models\Payment;
    use App\Models\Subscription;
    use App\Models\Client;

    $byType = Lead::selectRaw('type, count(*) as c')->groupBy('type')->pluck('c', 'type');
    $leadTotal = (int) $byType->sum();
    $cnt = fn ($t) => (int) ($byType[$t] ?? 0);

    $subsAtRisk = Subscription::where(fn ($q) => $q->whereIn('status', ['at_risk', 'past_due'])->orWhere('failed_payments', '>', 0))->count();

    $curType = request()->route('type');

    // Every lead now flows through the qualifier → two live buckets.
    $leadTypes = [
        'funding'       => 'Funding Ready',
        'credit-repair' => 'Credit Repair Ready',
    ];

    // Legacy buckets from retired on-site forms — only shown if they still hold data.
    $legacyTypes = [
        'popup'              => 'Popup (legacy)',
        'business-credit'    => 'Business Credit (legacy)',
        'debt-validation'    => 'Debt Validation (legacy)',
        'credit-building'    => 'Credit Building (legacy)',
        'financial-coaching' => 'Financial Coaching (legacy)',
        'credit'             => 'General (legacy)',
    ];
    foreach ($legacyTypes as $t => $label) {
        if ($cnt($t) > 0) {
            $leadTypes[$t] = $label;
        }
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>@yield('title', 'Dashboard') — Sparkman Admin</title>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
<style>
  :root{--navy:#0a1f52;--navy2:#0d2a6b;--gold:#d9a83a;--gold2:#f4cd63;--red:#e11d2a;--ink:#0f1c3f;--slate:#5b6b8c;--line:#e6eaf2;--bg:#f4f6fb}
  html{font-size:15px}
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:'Plus Jakarta Sans',system-ui,sans-serif;background:var(--bg);color:var(--ink);-webkit-font-smoothing:antialiased}
  a{text-decoration:none;color:inherit}
  .adm{display:grid;grid-template-columns:264px 1fr;min-height:100vh}
  /* sidebar */
  .adm__side{background:linear-gradient(180deg,#0a1f52,#07163c);color:#fff;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;overflow-y:auto}
  .adm__brand{display:flex;align-items:center;gap:10px;padding:22px 22px 16px}
  .adm__logo{height:42px;width:auto;display:block}
  .adm__brand span b{color:var(--gold2)}
  .adm__badge{width:34px;height:34px;border-radius:9px;display:grid;place-items:center;font-weight:800;color:#3a2708;background:linear-gradient(135deg,var(--gold2),var(--gold));flex:none}
  .adm__nav{padding:8px 12px;flex:1}
  .adm__group{font-size:.68rem;letter-spacing:.14em;text-transform:uppercase;color:#7f92c0;padding:18px 12px 8px;font-weight:700}
  .adm__link{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:9px 12px;border-radius:9px;color:#c7d2ee;font-weight:600;font-size:.9rem;transition:background .15s,color .15s;margin-bottom:2px}
  .adm__link:hover{background:rgba(255,255,255,.06);color:#fff}
  .adm__link.active{background:linear-gradient(120deg,rgba(217,168,58,.22),rgba(217,168,58,.08));color:#fff;box-shadow:inset 3px 0 0 var(--gold)}
  .adm__pill{background:rgba(255,255,255,.12);color:#dbe4fa;font-size:.72rem;font-weight:700;padding:2px 8px;border-radius:20px;min-width:22px;text-align:center}
  .adm__link.active .adm__pill{background:var(--gold);color:#3a2708}
  .adm__pill--red{background:var(--red);color:#fff}
  .adm__logout{margin:12px;padding:12px}
  .adm__logout button{width:100%;padding:10px;border:1px solid rgba(255,255,255,.16);background:rgba(255,255,255,.04);color:#fff;border-radius:9px;font-weight:600;cursor:pointer;font-family:inherit}
  .adm__logout button:hover{background:rgba(255,255,255,.1)}
  /* main */
  .adm__main{display:flex;flex-direction:column;min-width:0}
  .adm__top{display:flex;align-items:center;justify-content:space-between;padding:20px 30px;background:#fff;border-bottom:1px solid var(--line);position:sticky;top:0;z-index:5}
  .adm__top h1{font-size:1.3rem;font-weight:800;letter-spacing:-.02em}
  .adm__who{font-size:.85rem;color:var(--slate);font-weight:600}
  .adm__content{padding:26px 34px;max-width:1560px;width:100%;margin:0 auto}
  /* kpi */
  .kpis{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:18px;margin-bottom:28px}
  .kpi{background:#fff;border:1px solid var(--line);border-radius:16px;padding:22px}
  .kpi b{display:block;font-size:2rem;font-weight:800;letter-spacing:-.02em;color:var(--navy)}
  .kpi small{color:var(--slate);font-weight:600;font-size:.82rem}
  .kpi--gold{background:linear-gradient(150deg,var(--gold),var(--gold2));border-color:transparent}
  .kpi--gold b,.kpi--gold small{color:#3a2708}
  /* card + table */
  .card{background:#fff;border:1px solid var(--line);border-radius:16px;overflow:hidden;margin-bottom:26px}
  .card__head{display:flex;align-items:center;justify-content:space-between;gap:16px;padding:18px 22px;border-bottom:1px solid var(--line)}
  .card__head h2{font-size:1.05rem;font-weight:800}
  .tablewrap{overflow-x:auto}
  table{width:100%;border-collapse:collapse;font-size:.82rem}
  th{text-align:left;padding:10px 16px;color:var(--slate);font-weight:700;font-size:.7rem;letter-spacing:.04em;text-transform:uppercase;border-bottom:1px solid var(--line);white-space:nowrap}
  td{padding:10px 16px;border-bottom:1px solid var(--line);vertical-align:middle}
  tr:last-child td{border-bottom:0}
  tbody tr:hover{background:#fafbfe}
  .tag{display:inline-block;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;background:#eef2fb;color:var(--navy)}
  .tag--gold{background:#fbeecb;color:#7a5a12}
  .tag--green{background:#dcf5e6;color:#177245}
  .tag--red{background:#fde1e3;color:#a3121c}
  .tag--slate{background:#eef2fb;color:var(--slate)}
  .muted{color:var(--slate)}
  .empty{padding:50px 20px;text-align:center;color:var(--slate)}
  .empty b{display:block;font-size:1.05rem;color:var(--ink);margin-bottom:6px}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:9px 16px;border-radius:9px;font-weight:700;font-size:.85rem;background:linear-gradient(120deg,var(--gold),var(--gold2));color:#3a2708;border:0;cursor:pointer;font-family:inherit}
  .btn--ghost{background:#fff;border:1px solid var(--line);color:var(--navy)}
  .search{padding:9px 14px;border:1px solid var(--line);border-radius:9px;font-family:inherit;font-size:.88rem;min-width:220px}
  .pager{display:flex;gap:6px;padding:16px 22px;flex-wrap:wrap}
  .pager a,.pager span{padding:6px 11px;border-radius:8px;border:1px solid var(--line);font-size:.82rem;font-weight:600;color:var(--navy)}
  .pager .active-page{background:var(--navy);color:#fff;border-color:var(--navy)}
  /* dashboard */
  .dsearch{display:flex;gap:10px;margin-bottom:24px}
  .dsearch input{flex:1;padding:14px 18px;border:1px solid var(--line);border-radius:12px;font-family:inherit;font-size:.95rem;background:#fff}
  .dsearch input:focus{outline:none;border-color:var(--gold);box-shadow:0 0 0 4px rgba(217,168,58,.14)}
  .welcome{position:relative;overflow:hidden;border-radius:20px;padding:28px 30px;margin-bottom:24px;color:#fff;
    background:linear-gradient(120deg,#0a1f52,#12306e 60%,#173b82);display:flex;align-items:center;justify-content:space-between;gap:20px;flex-wrap:wrap}
  .welcome::after{content:"";position:absolute;right:-60px;top:-60px;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(217,168,58,.28),transparent 70%)}
  .welcome__k{position:relative;z-index:1}
  .welcome__ey{font-size:.72rem;letter-spacing:.14em;text-transform:uppercase;color:var(--gold-2);font-weight:800;margin-bottom:8px}
  .welcome h2{font-size:2rem;font-weight:800;letter-spacing:-.02em}
  .welcome h2 i{color:var(--gold-2);font-style:normal}
  .welcome p{color:#c3d0ef;margin-top:6px;max-width:520px;font-size:.92rem}
  .welcome__stats{position:relative;z-index:1;display:flex;gap:12px}
  .welcome__stat{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);border-radius:14px;padding:16px 22px;text-align:center;min-width:120px}
  .welcome__stat b{display:block;font-size:1.8rem;font-weight:800}
  .welcome__stat small{color:#c3d0ef;font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;font-weight:700}
  .cats{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:16px;margin-bottom:26px}
  .catcard{display:block;background:#fff;border:1px solid var(--line);border-radius:16px;padding:20px 22px;transition:transform .2s,box-shadow .2s}
  .catcard:hover{transform:translateY(-3px);box-shadow:0 20px 40px -26px rgba(10,31,82,.4)}
  .catcard small.lbl{color:var(--slate);font-weight:700;font-size:.74rem;letter-spacing:.03em;text-transform:uppercase}
  .catcard b{display:block;font-size:2rem;font-weight:800;color:var(--navy);margin:8px 0 4px}
  .catcard .sub{font-size:.78rem;color:var(--slate);font-weight:600}
  .catcard .sub em{color:var(--gold-3);font-style:normal;font-weight:700}
  .catcard--gold{background:linear-gradient(150deg,var(--gold),var(--gold2));border-color:transparent}
  .catcard--gold small.lbl,.catcard--gold b,.catcard--gold .sub{color:#3a2708}
  .panels{display:grid;grid-template-columns:1fr 1fr;gap:22px}
  .rowitem{display:flex;justify-content:space-between;gap:12px;padding:13px 0;border-bottom:1px solid var(--line)}
  .rowitem:last-child{border-bottom:0}
  .rowitem b{font-weight:700}
  .rowitem .r{text-align:right;color:var(--slate);font-size:.82rem;white-space:nowrap}
  @media(max-width:820px){.adm{grid-template-columns:1fr}.adm__side{position:static;height:auto}.panels{grid-template-columns:1fr}}
</style>
</head>
<body class="adm">
  <aside class="adm__side">
    <a href="{{ route('admin.dashboard') }}" class="adm__brand">
      <img src="/images/logo-white.png" alt="Sparkman Solutions" class="adm__logo" />
    </a>
    <nav class="adm__nav">
      <a href="{{ route('admin.dashboard') }}" class="adm__link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

      <div class="adm__group">Leads</div>
      <a href="{{ route('admin.leads') }}" class="adm__link {{ request()->routeIs('admin.leads') && !$curType ? 'active' : '' }}">
        All Leads <span class="adm__pill">{{ $leadTotal }}</span>
      </a>
      @foreach ($leadTypes as $t => $label)
        <a href="{{ route('admin.leads.type', $t) }}" class="adm__link {{ $curType === $t ? 'active' : '' }}">
          {{ $label }} <span class="adm__pill">{{ $cnt($t) }}</span>
        </a>
      @endforeach

      <div class="adm__group">Payments &amp; Clients</div>
      <a href="{{ route('admin.payments') }}" class="adm__link {{ request()->routeIs('admin.payments') ? 'active' : '' }}">
        All Payments <span class="adm__pill">{{ Payment::count() }}</span>
      </a>
      <a href="{{ route('admin.clients') }}" class="adm__link {{ request()->routeIs('admin.clients*') ? 'active' : '' }}">
        Paid Credit Repair Clients <span class="adm__pill">{{ Client::where('service','credit-repair')->count() }}</span>
      </a>
      <a href="{{ route('admin.subscriptions') }}" class="adm__link {{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
        Subscriptions <span class="adm__pill">{{ Subscription::count() }}</span>
      </a>
      <a href="{{ route('admin.subscriptions.atrisk') }}" class="adm__link {{ request()->routeIs('admin.subscriptions.atrisk') ? 'active' : '' }}">
        Subscriptions At Risk <span class="adm__pill {{ $subsAtRisk ? 'adm__pill--red' : '' }}">{{ $subsAtRisk }}</span>
      </a>
    </nav>
    <div class="adm__logout">
      <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Log out</button>
      </form>
    </div>
  </aside>

  <div class="adm__main">
    <header class="adm__top">
      <h1>@yield('title', 'Dashboard')</h1>
      <span class="adm__who">{{ session('admin_email') }}</span>
    </header>
    <div class="adm__content">
      @yield('content')
    </div>
  </div>
</body>
</html>
