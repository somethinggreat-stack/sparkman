<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Services\GoHighLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LeadController extends Controller
{
    /** All accepted lead source types. */
    private const TYPES = [
        'popup', 'credit', 'credit-repair', 'business-credit', 'debt-validation',
        'credit-building', 'financial-coaching', 'funding',
    ];

    /**
     * Store a lead from any site form (popup, service pages, funding).
     */
    public function store(Request $request, GoHighLevel $ghl)
    {
        $data = $request->validate([
            'type'         => ['nullable', 'string', 'max:40'],
            'name'         => ['required', 'string', 'max:120'],
            'email'        => ['required', 'email', 'max:180'],
            'phone'        => ['required', 'string', 'max:40'],
            'goal'         => ['nullable', 'string', 'max:160'],
            'funding_type' => ['nullable', 'string', 'max:120'],
            'amount'       => ['nullable', 'string', 'max:120'],
            'message'      => ['nullable', 'string', 'max:2000'],
            'source_url'   => ['nullable', 'string', 'max:255'],
            // service-specific selects (all optional)
            'concern'        => ['nullable', 'string', 'max:160'],
            'business_stage' => ['nullable', 'string', 'max:160'],
            'debt_type'      => ['nullable', 'string', 'max:160'],
            'credit_stage'   => ['nullable', 'string', 'max:160'],
            'focus'          => ['nullable', 'string', 'max:160'],
        ]);

        $type = in_array($data['type'] ?? null, self::TYPES, true) ? $data['type'] : 'credit';

        // Whichever service-specific select was used becomes the primary "goal".
        $goal = $data['goal']
            ?? $data['concern']
            ?? $data['business_stage']
            ?? $data['debt_type']
            ?? $data['credit_stage']
            ?? $data['focus']
            ?? null;

        $lead = Lead::create([
            'type'         => $type,
            'name'         => $data['name'],
            'email'        => $data['email'],
            'phone'        => $data['phone'],
            'goal'         => $goal,
            'funding_type' => $data['funding_type'] ?? null,
            'amount'       => $data['amount'] ?? null,
            'message'      => $data['message'] ?? null,
            'source_url'   => $data['source_url'] ?? $request->headers->get('referer'),
            'ip_address'   => $request->ip(),
            'meta'         => $request->except([
                '_token', 'name', 'email', 'phone', 'type', 'message',
            ]) ?: null,
        ]);

        // Push to GoHighLevel so on-site leads enter CRM automations, not just
        // the DB. Safe no-op until GHL is configured; never breaks the response.
        try {
            $ghl->pushLead($lead);
        } catch (\Throwable $e) {
            Log::warning('[lead] GHL push failed', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }

        return response()->json([
            'ok'      => true,
            'id'      => $lead->id,
            'message' => "Thank you! Your request is in — we'll reach out within one business day.",
        ]);
    }
}
