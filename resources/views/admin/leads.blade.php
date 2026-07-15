@extends('admin.layout')
@section('title', $title)

@section('content')
  <div class="card">
    <div class="card__head">
      <h2>{{ $title }} <span class="muted" style="font-weight:600">({{ $leads->total() }})</span></h2>
      <form method="GET" style="display:flex;gap:8px">
        <input class="search" type="search" name="q" value="{{ request('q') }}" placeholder="Search name, email, phone…" />
        <button class="btn btn--ghost" type="submit">Search</button>
      </form>
    </div>
    <div class="tablewrap">
      <table>
        <thead>
          <tr><th>Name</th>@if($type==='all')<th>Type</th>@endif<th>Status</th><th>Email</th><th>Phone</th><th>Interest</th><th>Received</th><th></th></tr>
        </thead>
        <tbody>
          @forelse ($leads as $lead)
            <tr>
              <td><a href="{{ route('admin.leads.show', $lead) }}"><b>{{ $lead->name }}</b></a></td>
              @if($type==='all')<td><span class="tag tag--gold">{{ $lead->typeLabel() }}</span></td>@endif
              <td><span class="tag {{ $lead->statusColor() }}">{{ ucfirst($lead->status) }}</span></td>
              <td class="muted">{{ $lead->email }}</td>
              <td class="muted">{{ $lead->phone }}</td>
              <td>{{ $lead->goal ?? '—' }}@if($lead->amount)<br><span class="muted">{{ $lead->amount }}</span>@endif</td>
              <td class="muted">{{ $lead->created_at->format('M j, Y g:i A') }}</td>
              <td><a class="btn btn--ghost" href="{{ route('admin.leads.show', $lead) }}">Open</a></td>
            </tr>
          @empty
            <tr><td colspan="8" class="empty"><b>No leads here yet</b>New submissions will show up automatically.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($leads->hasPages())
      <div class="pager">{!! $leads->links('admin.pager') !!}</div>
    @endif
  </div>
@endsection
