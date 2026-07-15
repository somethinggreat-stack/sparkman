@extends('admin.layout')
@section('title', $title)

@section('content')
  <div class="card">
    <div class="card__head"><h2>{{ $title }} <span class="muted" style="font-weight:600">({{ $clients->total() }})</span></h2></div>
    <div class="tablewrap">
      <table>
        <thead>
          <tr><th>Client</th><th>Contact</th><th>Location</th><th>SSN</th><th>CRC Sync</th><th>Status</th><th>Enrolled</th><th></th></tr>
        </thead>
        <tbody>
          @forelse ($clients as $c)
            <tr>
              <td><b>{{ $c->full_name }}</b></td>
              <td class="muted">{{ $c->email }}<br>{{ $c->phone }}</td>
              <td class="muted">{{ $c->city }}{{ $c->state ? ', '.$c->state : '' }}</td>
              <td class="muted">•••-••-{{ $c->ssnLast4() ?? '••••' }}</td>
              <td>
                @if ($c->crc_synced_at)
                  <span class="tag tag--green">Synced</span>
                @else
                  <span class="tag tag--slate">Pending</span>
                @endif
              </td>
              <td><span class="tag {{ $c->status === 'active' ? 'tag--green' : 'tag--slate' }}">{{ ucfirst($c->status) }}</span></td>
              <td class="muted">{{ $c->created_at->format('M j, Y') }}</td>
              <td><a class="btn btn--ghost" href="{{ route('admin.clients.show', $c) }}">View</a></td>
            </tr>
          @empty
            <tr><td colspan="8" class="empty"><b>No paid clients yet</b>Clients appear here after they pay and complete onboarding.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if ($clients->hasPages())<div class="pager">{!! $clients->links('admin.pager') !!}</div>@endif
  </div>
@endsection
