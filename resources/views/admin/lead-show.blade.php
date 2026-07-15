@extends('admin.layout')
@section('title', $lead->name)

@php
  // Qualifier criteria labels + the answer that "passes" each rule.
  $critLabels = [
    'credit_score' => ['label' => '680+ credit score',                  'pass' => '680+'],
    'cards_3500'   => ['label' => '2+ cards with a $3,500+ limit',       'pass' => 'yes'],
    'open_cards'   => ['label' => '4–5 open credit card accounts',       'pass' => 'yes'],
    'credit_age'   => ['label' => 'Average credit age of 5+ years',       'pass' => 'yes'],
    'clean'        => ['label' => 'No collections or charge-offs',       'pass' => 'yes'],
    'inquiries'    => ['label' => 'Fewer than 3 recent inquiries',       'pass' => 'yes'],
    'utilization'  => ['label' => 'Credit utilization below 30%',        'pass' => 'yes'],
  ];
  $meta      = $lead->meta ?? [];
  $q         = $meta['qualifier'] ?? null;
  $isFunnel  = ($meta['funnel'] ?? null) === 'qualifier';
  $failed    = $meta['failed_criteria'] ?? [];
  $goalMap   = ['business' => 'Business funding', 'personal' => 'Personal funding', 'both' => 'Business + personal'];
@endphp

@section('content')
  <a class="btn btn--ghost" href="{{ url()->previous() }}" style="margin-bottom:18px">← Back</a>

  @if (session('ok'))
    <div class="card" style="border-color:#bfe6cd;background:#f2fbf5;padding:12px 18px;margin-bottom:16px;color:#177245;font-weight:600">{{ session('ok') }}</div>
  @endif

  <div style="display:grid;grid-template-columns:1.4fr .9fr;gap:22px;align-items:start">
    <!-- Full submission -->
    <div style="display:grid;gap:22px">
      <div class="card">
        <div class="card__head">
          <h2>{{ $lead->name }}</h2>
          <span class="tag tag--gold">{{ $lead->typeLabel() }}</span>
        </div>
        <div style="padding:6px 22px 20px">
          <table>
            <tbody>
              <tr><th style="width:200px">Status</th><td><span class="tag {{ $lead->statusColor() }}">{{ ucfirst($lead->status) }}</span></td></tr>
              <tr><th>Email</th><td><a href="mailto:{{ $lead->email }}">{{ $lead->email }}</a></td></tr>
              <tr><th>Phone</th><td><a href="tel:{{ $lead->phone }}">{{ $lead->phone }}</a></td></tr>
              <tr><th>Primary interest</th><td>{{ $lead->goal ?? '—' }}</td></tr>
              @if ($lead->funding_type)<tr><th>Funding type</th><td>{{ $lead->funding_type }}</td></tr>@endif
              @if ($lead->amount)<tr><th>Amount sought</th><td>{{ $lead->amount }}</td></tr>@endif
              @if (!$isFunnel)<tr><th>Message</th><td>{{ $lead->message ?: '—' }}</td></tr>@endif
              <tr><th>Source page</th><td class="muted">{{ $lead->source_url ?? '—' }}</td></tr>
              <tr><th>IP address</th><td class="muted">{{ $lead->ip_address ?? '—' }}</td></tr>
              <tr><th>Received</th><td class="muted">{{ $lead->created_at->format('M j, Y g:i A') }}</td></tr>
              @if (!empty($meta))
                @foreach ($meta as $k => $v)
                  @if (!in_array($k, ['source_url', 'funnel', 'qualified', 'failed_criteria', 'qualifier']) && !is_array($v))
                    <tr><th style="text-transform:capitalize">{{ str_replace('_',' ',$k) }}</th><td>{{ $v }}</td></tr>
                  @endif
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
      </div>

      {{-- Fundability assessment (only for qualifier-funnel leads) --}}
      @if ($isFunnel && $q)
        <div class="card">
          <div class="card__head">
            <h2>Fundability assessment</h2>
            @if ($lead->qualified)
              <span class="tag tag--green">✓ Funding-ready</span>
            @else
              <span class="tag tag--red">Funding prep needed</span>
            @endif
          </div>
          <div style="padding:6px 22px 22px">
            <p class="muted" style="margin:2px 0 16px">
              @if ($lead->qualified)
                Passed all {{ count($critLabels) }} funding criteria — route to funding / book a call.
              @else
                Failed {{ count($failed) }} of {{ count($critLabels) }} criteria — route to credit repair (Funding Prep), then re-qualify.
              @endif
            </p>

            <table>
              <tbody>
                <tr>
                  <th style="width:200px">Funding goal</th>
                  <td>{{ $goalMap[$q['funding_goal'] ?? ''] ?? ($q['funding_goal'] ?? '—') }}</td>
                </tr>
                @foreach ($critLabels as $key => $rule)
                  @php $ans = $q[$key] ?? null; $ok = $ans === $rule['pass']; @endphp
                  <tr>
                    <th>{{ $rule['label'] }}</th>
                    <td>
                      @if ($ok)
                        <span class="tag tag--green">✓ {{ $ans }}</span>
                      @else
                        <span class="tag tag--red">✗ {{ $ans ?? '—' }}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            @php
              $biz = array_filter([
                'Business name'   => $q['business_name']   ?? null,
                'Time in business'=> $q['business_age']    ?? null,
                'Has website'     => $q['has_website']      ?? null,
                'Has EIN'         => $q['has_ein']          ?? null,
                'Monthly revenue' => $q['monthly_revenue']  ?? null,
              ], fn ($v) => filled($v));
            @endphp
            @if (!empty($biz))
              <h3 style="font-size:.9rem;color:var(--navy);margin:22px 0 8px">Business details</h3>
              <table>
                <tbody>
                  @foreach ($biz as $label => $val)
                    <tr><th style="width:200px">{{ $label }}</th><td>{{ $val }}</td></tr>
                  @endforeach
                </tbody>
              </table>
            @endif
          </div>
        </div>
      @endif
    </div>

    <!-- Manage -->
    <div class="card">
      <div class="card__head"><h2>Manage lead</h2></div>
      <form method="POST" action="{{ route('admin.leads.status', $lead) }}" style="padding:20px 22px">
        @csrf
        <label style="display:block;font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:6px">Pipeline status</label>
        <select name="status" class="search" style="width:100%;margin-bottom:16px">
          @foreach (\App\Models\Lead::STATUSES as $s)
            <option value="{{ $s }}" @selected($lead->status === $s)>{{ ucfirst($s) }}</option>
          @endforeach
        </select>

        <label style="display:block;font-size:.8rem;font-weight:700;color:var(--navy);margin-bottom:6px">Internal notes</label>
        <textarea name="admin_notes" rows="6" class="search" style="width:100%;margin-bottom:16px;resize:vertical">{{ $lead->admin_notes }}</textarea>

        <button type="submit" class="btn" style="width:100%">Save changes</button>
      </form>
    </div>
  </div>
@endsection
