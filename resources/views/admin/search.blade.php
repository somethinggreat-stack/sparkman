@extends('admin.layout')
@section('title', 'Search')

@section('content')
  <form class="dsearch" method="GET" action="{{ route('admin.search') }}">
    <input type="search" name="q" value="{{ $q }}" placeholder="Search across leads, paid clients, payments…" autofocus />
    <button class="btn" type="submit">Search</button>
  </form>

  @if ($q === '')
    <div class="card"><div class="empty"><b>Type something to search</b>Searches names, emails &amp; phone numbers across leads, clients and payments.</div></div>
  @else
    <p class="muted" style="margin-bottom:18px">Results for “<b>{{ $q }}</b>” — {{ $leads->count() + $clients->count() + $payments->count() }} match(es).</p>

    <div class="card">
      <div class="card__head"><h2>Leads ({{ $leads->count() }})</h2></div>
      <div class="tablewrap"><table>
        <thead><tr><th>Name</th><th>Type</th><th>Status</th><th>Contact</th><th></th></tr></thead>
        <tbody>
          @forelse ($leads as $l)
            <tr>
              <td><a href="{{ route('admin.leads.show', $l) }}"><b>{{ $l->name }}</b></a></td>
              <td><span class="tag tag--gold">{{ $l->typeLabel() }}</span></td>
              <td><span class="tag {{ $l->statusColor() }}">{{ ucfirst($l->status) }}</span></td>
              <td class="muted">{{ $l->email }} · {{ $l->phone }}</td>
              <td><a class="btn btn--ghost" href="{{ route('admin.leads.show', $l) }}">Open</a></td>
            </tr>
          @empty
            <tr><td colspan="5" class="empty">No matching leads.</td></tr>
          @endforelse
        </tbody>
      </table></div>
    </div>

    <div class="card">
      <div class="card__head"><h2>Paid Clients ({{ $clients->count() }})</h2></div>
      <div class="tablewrap"><table>
        <thead><tr><th>Name</th><th>Contact</th><th>Service</th><th></th></tr></thead>
        <tbody>
          @forelse ($clients as $c)
            <tr>
              <td><b>{{ $c->full_name }}</b></td>
              <td class="muted">{{ $c->email }} · {{ $c->phone }}</td>
              <td>{{ ucwords(str_replace('-',' ',$c->service)) }}</td>
              <td><a class="btn btn--ghost" href="{{ route('admin.clients.show', $c) }}">Open</a></td>
            </tr>
          @empty
            <tr><td colspan="4" class="empty">No matching clients.</td></tr>
          @endforelse
        </tbody>
      </table></div>
    </div>

    <div class="card">
      <div class="card__head"><h2>Payments ({{ $payments->count() }})</h2></div>
      <div class="tablewrap"><table>
        <thead><tr><th>Name</th><th>Plan</th><th>Amount</th><th>Status</th><th>When</th></tr></thead>
        <tbody>
          @forelse ($payments as $p)
            <tr>
              <td><b>{{ $p->name }}</b><br><span class="muted">{{ $p->email }}</span></td>
              <td>{{ ucfirst($p->plan) }}</td>
              <td><b>${{ number_format($p->amount,2) }}</b></td>
              <td><span class="tag {{ $p->status==='paid'?'tag--green':'tag--slate' }}">{{ ucfirst($p->status) }}</span></td>
              <td class="muted">{{ $p->created_at->format('M j, Y') }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="empty">No matching payments.</td></tr>
          @endforelse
        </tbody>
      </table></div>
    </div>
  @endif
@endsection
