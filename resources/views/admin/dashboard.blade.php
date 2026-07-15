@extends('admin.layout')
@section('title', 'Dashboard')

@section('content')
  <form class="dsearch" method="GET" action="{{ route('admin.search') }}">
    <input type="search" name="q" placeholder="Search across leads, paid clients, payments…" />
    <button class="btn" type="submit">Search</button>
  </form>

  <!-- Welcome banner -->
  <div class="welcome">
    <div class="welcome__k">
      <div class="welcome__ey">Welcome back · {{ now()->format('l, M j, Y') }}</div>
      <h2>Javon <i>Sparkman.</i></h2>
      <p>Founder, Sparkman Solutions. Everything that came through your forms — leads, paid clients, subscriptions &amp; payments — lives below.</p>
    </div>
    <div class="welcome__stats">
      <div class="welcome__stat"><b>{{ number_format($stats['total_submissions']) }}</b><small>Total leads</small></div>
      <div class="welcome__stat"><b>{{ number_format($stats['today_submissions']) }}</b><small>Today</small></div>
      <div class="welcome__stat"><b>{{ $stats['qual_total'] ? round($stats['qual_ready'] / $stats['qual_total'] * 100) : 0 }}%</b><small>Funding-ready</small></div>
    </div>
  </div>

  <!-- KPI row -->
  <div class="kpis">
    <div class="kpi"><small>Gross lifetime</small><b style="color:#177245">${{ number_format($stats['gross'], 2) }}</b><small>Captured · before refunds</small></div>
    <div class="kpi"><small>Refunded / voided</small><b style="color:#a3121c">${{ number_format($stats['refunded'], 2) }}</b><small>Lifetime</small></div>
    <div class="kpi"><small>Today</small><b>${{ number_format($stats['today_revenue'], 2) }}</b><small>Captured today</small></div>
    <div class="kpi"><small>Month to date</small><b>${{ number_format($stats['mtd'], 2) }}</b><small>{{ now()->format('F') }}</small></div>
    <div class="kpi kpi--gold"><small>MRR (active recurring)</small><b>${{ number_format($stats['mrr'], 2) }}</b><small>{{ $stats['subs_active'] }} active · {{ $stats['subs_pastdue'] }} past due</small></div>
    <div class="kpi"><small>Webhooks captured</small><b>{{ number_format($stats['webhooks']) }}</b><small>+{{ $stats['webhooks_today'] }} today</small></div>
  </div>

  <!-- Category cards -->
  <div class="cats">
    @foreach ($cats as $c)
      <a class="catcard {{ $c['gold'] ? 'catcard--gold' : '' }}" href="{{ $c['url'] }}">
        <small class="lbl">{{ $c['label'] }}</small>
        <b>{{ number_format($c['count']) }}</b>
        <span class="sub">+{{ $c['today'] }} today · <em>{{ $c['new'] }} {{ $c['newLabel'] ?? 'new' }}</em></span>
      </a>
    @endforeach
  </div>

  <!-- Recent activity -->
  <div class="panels">
    <div class="card">
      <div class="card__head"><h2>Latest paying customers</h2><a class="btn btn--ghost" href="{{ route('admin.payments') }}">View all</a></div>
      <div style="padding:6px 22px 14px">
        @forelse ($latestPayments as $p)
          <div class="rowitem">
            <div><b>{{ $p->name }}</b><br><span class="muted" style="font-size:.82rem">{{ ucfirst($p->plan) }} · ${{ number_format($p->amount,2) }}@if($p->monthly_amount) + ${{ number_format($p->monthly_amount,2) }}/mo @endif</span></div>
            <div class="r">{{ $p->created_at->diffForHumans() }}</div>
          </div>
        @empty
          <div class="empty"><b>No payments yet</b>Completed checkouts appear here.</div>
        @endforelse
      </div>
    </div>

    <div class="card">
      <div class="card__head"><h2>Latest webhook events</h2></div>
      <div style="padding:6px 22px 14px">
        @forelse ($latestWebhooks as $w)
          <div class="rowitem">
            <div><b>{{ $w->summary }}</b><br><span class="muted" style="font-size:.78rem">{{ $w->event_type }}</span></div>
            <div class="r">{{ $w->created_at->diffForHumans() }}</div>
          </div>
        @empty
          <div class="empty"><b>No webhook events yet</b>Authorize.Net events will stream here once your webhook is set.</div>
        @endforelse
      </div>
    </div>
  </div>

  <div class="card" style="margin-top:22px">
    <div class="card__head"><h2>Recent leads</h2><a class="btn btn--ghost" href="{{ route('admin.leads') }}">View all</a></div>
    <div class="tablewrap">
      <table>
        <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Contact</th><th>Interest</th><th>When</th><th></th></tr></thead>
        <tbody>
          @forelse ($recentLeads as $lead)
            <tr>
              <td><a href="{{ route('admin.leads.show', $lead) }}"><b>{{ $lead->name }}</b></a></td>
              <td><span class="tag tag--gold">{{ $lead->typeLabel() }}</span></td>
              <td><span class="tag {{ $lead->statusColor() }}">{{ ucfirst($lead->status) }}</span></td>
              <td class="muted">{{ $lead->email }}<br>{{ $lead->phone }}</td>
              <td>{{ $lead->goal ?? '—' }}</td>
              <td class="muted">{{ $lead->created_at->diffForHumans() }}</td>
              <td><a class="btn btn--ghost" href="{{ route('admin.leads.show', $lead) }}">Open</a></td>
            </tr>
          @empty
            <tr><td colspan="7" class="empty"><b>No leads yet</b>Submissions from the site forms will appear here.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
@endsection
