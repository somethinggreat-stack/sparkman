<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\GoHighLevel;
use Illuminate\Http\Request;

class QualifierController extends Controller
{
    /**
     * Funding qualification criteria — ALL must pass to be "funding-ready".
     * Fail any → automatically routed to Credit Repair ("Funding Prep").
     */
    private const CRITERIA = [
        'credit_score' => ['label' => '680+ credit score',                 'pass' => '680+'],
        'cards_3500'   => ['label' => '2+ credit cards with a $3,500+ limit','pass' => 'yes'],
        'open_cards'   => ['label' => '4–5 open credit card accounts',      'pass' => 'yes'],
        'credit_age'   => ['label' => 'Average credit age of 5+ years',     'pass' => 'yes'],
        'clean'        => ['label' => 'No collections or charge-offs',      'pass' => 'yes'],
        'inquiries'    => ['label' => 'Fewer than 3 recent inquiries',      'pass' => 'yes'],
        'utilization'  => ['label' => 'Credit utilization below 30%',       'pass' => 'yes'],
    ];

    public function show()
    {
        return view('qualify.show', [
            'communityUrl' => config('services.community.telegram_url'),
        ]);
    }

    public function submit(Request $request, GoHighLevel $ghl)
    {
        $data = $request->validate([
            'first_name'   => ['required', 'string', 'max:80'],
            'last_name'    => ['required', 'string', 'max:80'],
            'email'        => ['required', 'email', 'max:180'],
            'phone'        => ['required', 'string', 'max:40'],
            'funding_goal' => ['required', 'in:business,personal,both'],
            'amount'       => ['nullable', 'string', 'max:60'],
            // credit qualification
            'credit_score' => ['required', 'in:under-680,680+'],
            'cards_3500'   => ['required', 'in:yes,no'],
            'open_cards'   => ['required', 'in:yes,no'],
            'credit_age'   => ['required', 'in:yes,no'],
            'clean'        => ['required', 'in:yes,no'],
            'inquiries'    => ['required', 'in:yes,no'],
            'utilization'  => ['required', 'in:yes,no'],
            // business (optional; LLC age preferred, not a dealbreaker)
            'business_name' => ['nullable', 'string', 'max:160'],
            'business_age'  => ['nullable', 'string', 'max:60'],
            'has_website'   => ['nullable', 'string', 'max:10'],
            'has_ein'       => ['nullable', 'string', 'max:10'],
            'monthly_revenue' => ['nullable', 'string', 'max:60'],
        ]);

        // Score against the criteria.
        $failed = [];
        foreach (self::CRITERIA as $key => $rule) {
            if (($data[$key] ?? null) !== $rule['pass']) {
                $failed[] = $rule['label'];
            }
        }
        $qualified = count($failed) === 0;
        $outcome   = $qualified ? 'funding' : 'credit-repair';

        $lead = Lead::create([
            'type'       => $outcome,                 // 'funding' or 'credit-repair'
            'status'     => 'new',
            'qualified'  => $qualified,
            'name'       => trim($data['first_name'] . ' ' . $data['last_name']),
            'email'      => $data['email'],
            'phone'      => $data['phone'],
            'goal'       => $qualified ? 'Funding-ready' : 'Needs credit repair (Funding Prep)',
            'amount'     => $data['amount'] ?? null,
            'source_url' => '/get-funded',
            'ip_address' => $request->ip(),
            'meta'       => [
                'funnel'    => 'qualifier',
                'qualified' => $qualified,
                'failed_criteria' => $failed,
                'qualifier' => [
                    'funding_goal'    => $data['funding_goal'],
                    'credit_score'    => $data['credit_score'],
                    'cards_3500'      => $data['cards_3500'],
                    'open_cards'      => $data['open_cards'],
                    'credit_age'      => $data['credit_age'],
                    'clean'           => $data['clean'],
                    'inquiries'       => $data['inquiries'],
                    'utilization'     => $data['utilization'],
                    'business_name'   => $data['business_name'] ?? null,
                    'business_age'    => $data['business_age'] ?? null,
                    'has_website'     => $data['has_website'] ?? null,
                    'has_ein'         => $data['has_ein'] ?? null,
                    'monthly_revenue' => $data['monthly_revenue'] ?? null,
                ],
            ],
        ]);

        // Push to GoHighLevel (safe no-op until configured).
        $ghl->pushLead($lead);

        return redirect()->route('qualify.result', ['outcome' => $outcome])
            ->with('q_name', $data['first_name'])
            ->with('q_failed', $failed)
            ->with('q_goal', $data['funding_goal']);
    }

    public function result(string $outcome)
    {
        abort_unless(in_array($outcome, ['funding', 'credit-repair'], true), 404);

        // Direct visits without a fresh submission go back to the qualifier.
        if (! session()->has('q_name')) {
            return redirect()->route('qualify.show');
        }

        return view('qualify.result', [
            'outcome'      => $outcome,
            'name'         => session('q_name'),
            'failed'       => session('q_failed', []),
            'goal'         => session('q_goal'),
            'communityUrl' => config('services.community.telegram_url'),
        ]);
    }
}
