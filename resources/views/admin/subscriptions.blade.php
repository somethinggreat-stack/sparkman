@extends('admin.layout')
@section('title', $title)

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>{{ $title }} <span class="muted" style="font-weight:600">({{ $subscriptions->total() }})</span></h2>
      @if ($atRisk)<span class="tag tag--red">Needs attention</span>@endif
    </div>
    <div class="tablewrap">
      <table>
        <thead>
          <tr><th>Customer</th><th>Plan</th><th>Amount</th><th>Status</th><th>Failed</th><th>Next bill</th><th>Started</th></tr>
        </thead>
        <tbody>
          @forelse ($subscriptions as $s)
            <tr>
              <td><b>{{ $s->name }}</b><br><span class="muted">{{ $s->email }}</span></td>
              <td>{{ ucfirst($s->plan ?? '—') }}</td>
              <td><b>${{ number_format($s->amount, 2) }}</b>/{{ $s->interval }}</td>
              <td>
                @php $klass = match($s->status){ 'active' => 'tag--green', 'cancelled','completed' => 'tag--slate', default => 'tag--red' }; @endphp
                <span class="tag {{ $klass }}">{{ ucfirst(str_replace('_',' ',$s->status)) }}</span>
              </td>
              <td>{{ $s->failed_payments ? '<span>'.$s->failed_payments.'</span>' : '—' }}</td>
              <td class="muted">{{ $s->next_billing_at ? $s->next_billing_at->format('M j, Y') : '—' }}</td>
              <td class="muted">{{ $s->started_at ? $s->started_at->format('M j, Y') : '—' }}</td>
            </tr>
          @empty
            <tr><td colspan="7" class="empty"><b>{{ $atRisk ? 'No subscriptions at risk 🎉' : 'No subscriptions yet' }}</b>{{ $atRisk ? 'Failed or past-due subscriptions will surface here.' : 'Recurring plans will appear here after checkout.' }}</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($subscriptions->hasPages())<div class="pager">{!! $subscriptions->links('admin.pager') !!}</div>@endif
  </div>
@endsection
