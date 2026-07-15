@extends('admin.layout')
@section('title', 'All Payments')

@section('content')
  <div class="card">
    <div class="card__head"><h2>All Payments <span class="muted" style="font-weight:600">({{ $payments->total() }})</span></h2></div>
    <div class="tablewrap">
      <table>
        <thead>
          <tr><th>Customer</th><th>Plan</th><th>Amount</th><th>Monthly</th><th>Card</th><th>Txn ID</th><th>Status</th><th>Date</th></tr>
        </thead>
        <tbody>
          @forelse ($payments as $p)
            <tr>
              <td><b>{{ $p->name }}</b><br><span class="muted">{{ $p->email }}</span></td>
              <td>{{ ucfirst($p->plan ?? '—') }}</td>
              <td><b>${{ number_format($p->amount, 2) }}</b></td>
              <td class="muted">{{ $p->monthly_amount ? '$'.number_format($p->monthly_amount,2).'/mo' : '—' }}</td>
              <td class="muted">{{ $p->card_type }} @if($p->card_last4)•••• {{ $p->card_last4 }}@endif</td>
              <td class="muted">{{ $p->authnet_transaction_id ?? '—' }}</td>
              <td><span class="tag {{ $p->status === 'paid' ? 'tag--green' : ($p->status === 'failed' ? 'tag--red' : 'tag--slate') }}">{{ ucfirst($p->status) }}</span></td>
              <td class="muted">{{ $p->created_at->format('M j, Y') }}</td>
            </tr>
          @empty
            <tr><td colspan="8" class="empty"><b>No payments yet</b>Completed checkouts will appear here.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($payments->hasPages())<div class="pager">{!! $payments->links('admin.pager') !!}</div>@endif
  </div>
@endsection
