<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Payment;
use App\Services\CreditRepairCloud;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    protected function payment(string $token): Payment
    {
        return Payment::where('onboarding_token', $token)->firstOrFail();
    }

    /** Show the onboarding form (only for paid, not-yet-onboarded payments). */
    public function show(string $token)
    {
        $payment = $this->payment($token);

        // The service agreement must be signed before onboarding.
        if (! $payment->agreement_signed_at) {
            return redirect()->route('agreement.show', $token);
        }

        if ($payment->onboarded_at) {
            return redirect()->route('onboarding.next', $token);
        }

        return view('onboarding.form', [
            'payment'   => $payment,
            'token'     => $token,
            'enrollUrl' => config('services.myfreescorenow.enroll_url'),
        ]);
    }

    /** Save the onboarding data as a paid Client and sync to Credit Repair Cloud. */
    public function store(Request $request, string $token, CreditRepairCloud $crc)
    {
        $payment = $this->payment($token);

        // The service agreement must be signed before onboarding can be saved.
        if (! $payment->agreement_signed_at) {
            return redirect()->route('agreement.show', $token);
        }

        $data = $request->validate([
            'first_name'  => ['required', 'string', 'max:80'],
            'last_name'   => ['required', 'string', 'max:80'],
            'middle_name' => ['nullable', 'string', 'max:80'],
            'suffix'      => ['nullable', 'string', 'max:20'],
            'email'       => ['required', 'email', 'max:180'],
            'phone'       => ['required', 'string', 'max:40'],
            'street'      => ['nullable', 'string', 'max:180'],
            'city'        => ['nullable', 'string', 'max:120'],
            'state'       => ['nullable', 'string', 'max:40'],
            'zip'         => ['nullable', 'string', 'max:20'],
            'ssn'         => ['required', 'string', 'regex:/^\d{3}-?\d{2}-?\d{4}$/'],
            'dob_month'   => ['required', 'integer', 'between:1,12'],
            'dob_day'     => ['required', 'integer', 'between:1,31'],
            'dob_year'    => ['required', 'integer', 'min:1900', 'max:' . (date('Y') - 18)],
        ], [
            'ssn.regex'    => 'Please enter a valid 9-digit Social Security Number.',
            'dob_year.max' => 'You must be 18 or older to enroll.',
        ]);

        $dob = sprintf('%04d-%02d-%02d', $data['dob_year'], $data['dob_month'], $data['dob_day']);

        $client = Client::updateOrCreate(
            ['payment_id' => $payment->id],
            [
                'lead_id'     => $payment->lead_id,
                'first_name'  => $data['first_name'],
                'last_name'   => $data['last_name'],
                'middle_name' => $data['middle_name'] ?? null,
                'suffix'      => $data['suffix'] ?? null,
                'email'       => $data['email'],
                'phone'       => $data['phone'],
                'street'      => $data['street'] ?? null,
                'city'        => $data['city'] ?? null,
                'state'       => $data['state'] ?? null,
                'zip'         => $data['zip'] ?? null,
                'ssn'         => preg_replace('/\D/', '', $data['ssn']),
                'dob'         => $dob,
                'service'     => config("plans.{$payment->plan}.service", 'credit-repair'),
                'status'      => 'active',
            ]
        );

        $payment->update(['client_id' => $client->id, 'onboarded_at' => now()]);

        // Push to Credit Repair Cloud (safe no-op until configured).
        $crc->sync($client);

        return redirect()->route('onboarding.next', $token);
    }

    /** "Here's what happens next" page. */
    public function nextSteps(string $token)
    {
        $payment = $this->payment($token);

        return view('onboarding.next-steps', [
            'payment'    => $payment,
            'enrollUrl'  => config('services.myfreescorenow.enroll_url'),
        ]);
    }
}
