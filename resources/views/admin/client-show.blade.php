@extends('admin.layout')
@section('title', $client->full_name)

@section('content')
  <a class="btn btn--ghost" href="{{ route('admin.clients') }}" style="margin-bottom:18px">← Back to clients</a>

  <div class="card">
    <div class="card__head">
      <h2>{{ $client->full_name }}</h2>
      <span class="tag {{ $client->crc_synced_at ? 'tag--green' : 'tag--slate' }}">
        {{ $client->crc_synced_at ? 'Synced to Credit Repair Cloud' : 'CRC sync pending' }}
      </span>
    </div>
    <div style="padding:8px 22px 22px">
      <table>
        <tbody>
          <tr><th style="width:220px">Full name</th><td>{{ $client->first_name }} {{ $client->middle_name }} {{ $client->last_name }} {{ $client->suffix }}</td></tr>
          <tr><th>Email</th><td>{{ $client->email }}</td></tr>
          <tr><th>Phone</th><td>{{ $client->phone }}</td></tr>
          <tr><th>Address</th><td>{{ $client->street ?: '—' }}<br>{{ $client->city }}{{ $client->state ? ', '.$client->state : '' }} {{ $client->zip }}</td></tr>
          <tr><th>SSN</th><td>•••-••-{{ $client->ssnLast4() ?? '••••' }} <span class="muted">(encrypted at rest)</span></td></tr>
          <tr><th>Date of birth</th><td>{{ $client->dob ? $client->dob->format('M j, Y') : '—' }}</td></tr>
          <tr><th>Service</th><td>{{ ucwords(str_replace('-', ' ', $client->service)) }}</td></tr>
          <tr><th>Status</th><td><span class="tag tag--green">{{ ucfirst($client->status) }}</span></td></tr>
          <tr><th>CRC contact ID</th><td>{{ $client->crc_contact_id ?? '—' }}</td></tr>
          <tr><th>Linked payment</th><td>{{ $client->payment_id ? '#'.$client->payment_id : '—' }}</td></tr>
          <tr><th>Enrolled</th><td>{{ $client->created_at->format('M j, Y g:i A') }}</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  @php $pmt = $client->payment; @endphp
  @if ($pmt)
    <div class="card">
      <div class="card__head">
        <h2>Service Agreement</h2>
        <span class="tag {{ $pmt->agreement_signed_at ? 'tag--green' : 'tag--red' }}">
          {{ $pmt->agreement_signed_at ? 'Signed' : 'Not signed' }}
        </span>
      </div>
      <div style="padding:8px 22px 22px">
        @if ($pmt->agreement_signed_at)
          <table>
            <tbody>
              <tr><th style="width:220px">Signed by</th><td>{{ $pmt->agreement_name ?? $pmt->name }}</td></tr>
              <tr><th>Signed at</th><td>{{ $pmt->agreement_signed_at->format('M j, Y g:i A') }}</td></tr>
              <tr><th>IP address</th><td class="muted">{{ $pmt->agreement_ip ?? '—' }}</td></tr>
              <tr><th>Representative</th><td>Javon Sparkman <span class="muted">(auto-signed for Credit2Capitals)</span></td></tr>
            </tbody>
          </table>
          @if ($pmt->agreement_signature)
            <div style="margin-top:16px">
              <div class="muted" style="font-size:.74rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;margin-bottom:8px">Client signature</div>
              <img src="{{ $pmt->agreement_signature }}" alt="Client signature" style="max-width:340px;width:100%;border:1px solid var(--line);border-radius:10px;background:#fff" />
            </div>
          @endif
        @else
          <p class="muted" style="padding:6px 0">The client has not signed the service agreement yet.</p>
        @endif
      </div>
    </div>
  @endif
@endsection
