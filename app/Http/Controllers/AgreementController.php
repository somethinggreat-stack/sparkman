<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;

class AgreementController extends Controller
{
    /** The representative who counter-signs every agreement (auto-signed). */
    public const REPRESENTATIVE = 'Javon Sparkman';

    protected function payment(string $token): Payment
    {
        return Payment::where('onboarding_token', $token)->firstOrFail();
    }

    /** Show the credit-repair service agreement for signing (post-payment). */
    public function show(string $token)
    {
        $payment = $this->payment($token);

        // Already signed → straight to onboarding.
        if ($payment->agreement_signed_at) {
            return redirect()->route('onboarding.show', $token);
        }

        return view('agreement.show', [
            'payment'        => $payment,
            'token'          => $token,
            'representative' => self::REPRESENTATIVE,
        ]);
    }

    /** Store the client's signature, then forward to onboarding. */
    public function sign(Request $request, string $token)
    {
        $payment = $this->payment($token);

        if ($payment->agreement_signed_at) {
            return redirect()->route('onboarding.show', $token);
        }

        $data = $request->validate([
            'signature' => ['required', 'string', 'regex:/^data:image\/png;base64,/'],
            'agree'     => ['accepted'],
        ], [
            'signature.required' => 'Please sign in the box to continue.',
            'signature.regex'    => 'Your signature did not save — please sign again.',
            'agree.accepted'     => 'You must agree to the terms to continue.',
        ]);

        $payment->update([
            'agreement_signed_at' => now(),
            'agreement_signature' => $data['signature'],
            'agreement_ip'        => $request->ip(),
            'agreement_name'      => $payment->name,
        ]);

        return redirect()->route('onboarding.show', $token);
    }
}
