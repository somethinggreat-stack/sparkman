<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use App\Services\AuthorizeNet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected function plan(string $slug): ?array
    {
        $plan = config("plans.$slug");
        return $plan ? array_merge($plan, ['slug' => $slug]) : null;
    }

    /** Show the secure checkout page for a plan. */
    public function show(string $plan)
    {
        $planData = $this->plan($plan);
        abort_unless($planData, 404);

        return view('checkout.show', [
            'plan'      => $planData,
            'clientKey' => config('services.authorizenet.client_key'),
            'loginId'   => config('services.authorizenet.login_id'),
            'anetEnv'   => config('services.authorizenet.env', 'sandbox'),
        ]);
    }

    /** Process the payment via Authorize.Net Accept.js opaque data. */
    public function process(Request $request, string $plan, AuthorizeNet $anet)
    {
        Log::info('[checkout] START', [
            'plan'     => $plan,
            'anet_env' => config('services.authorizenet.env'),
            'endpoint' => $anet->endpoint(),
            'ip'       => $request->ip(),
        ]);

        $planData = $this->plan($plan);
        abort_unless($planData, 404);

        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:80'],
            'last_name'       => ['required', 'string', 'max:80'],
            'email'           => ['required', 'email', 'max:180'],
            'phone'           => ['required', 'string', 'max:40'],
            'card_name'       => ['nullable', 'string', 'max:120'],
            'street'          => ['nullable', 'string', 'max:180'],
            'city'            => ['nullable', 'string', 'max:120'],
            'state'           => ['nullable', 'string', 'max:40'],
            'zip'             => ['nullable', 'string', 'max:20'],
            'terms'           => ['accepted'],
            'privacy'         => ['accepted'],
            'marketing'       => ['nullable'],
            'data_descriptor' => ['required', 'string'],  // from Accept.js
            'data_value'      => ['required', 'string'],   // from Accept.js (payment nonce)
        ], [
            'terms.accepted'   => 'Please accept the Terms of Service to continue.',
            'privacy.accepted' => 'Please accept the Privacy Policy to continue.',
        ]);

        Log::info('[checkout] validation passed', [
            'plan'   => $plan,
            'email'  => $data['email'],
            'amount' => $planData['down'],
            'has_data_descriptor' => ! empty($data['data_descriptor']),
            'has_data_value'      => ! empty($data['data_value']),
        ]);

        $customer = [
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
        ];

        // Create the pending payment record up-front.
        $payment = Payment::create([
            'name'           => trim($data['first_name'] . ' ' . $data['last_name']),
            'email'          => $data['email'],
            'phone'          => $data['phone'],
            'plan'           => $plan,
            'description'    => $planData['name'] . ' — down payment',
            'amount'         => $planData['down'],
            'monthly_amount' => $planData['monthly'] ?? null,
            'type'           => isset($planData['monthly']) ? 'subscription_initial' : 'one_time',
            'status'         => 'pending',
            'meta'           => [
                'address'   => array_filter([
                    'street' => $data['street'] ?? null,
                    'city'   => $data['city'] ?? null,
                    'state'  => $data['state'] ?? null,
                    'zip'    => $data['zip'] ?? null,
                ]),
                'marketing_opt_in' => (bool) ($data['marketing'] ?? false),
            ],
        ]);

        Log::info('[checkout] payment record created', [
            'payment_id' => $payment->id,
            'type'       => $payment->type,
        ]);

        // 1) Tokenize the card into a reusable Customer Profile (the Accept.js
        //    nonce is single-use, so we can't use it for both the charge and the
        //    subscription — the profile lets us reuse the stored card).
        Log::info('[checkout] step 1: creating customer profile', ['payment_id' => $payment->id]);
        try {
            $profile = $anet->createCustomerProfile(
                $data['data_descriptor'],
                $data['data_value'],
                $customer,
                'SPK-' . $payment->id,
            );
        } catch (\Throwable $e) {
            Log::error('[checkout] step 1 EXCEPTION: createCustomerProfile', [
                'payment_id' => $payment->id,
                'exception'  => $e->getMessage(),
            ]);
            $payment->update(['status' => 'failed', 'meta' => ['error' => 'profile_exception: ' . $e->getMessage()]]);

            return back()->withInput()->withErrors(['card' => 'We could not reach our payment processor. No charge was made — please try again in a moment.']);
        }

        if (! $profile['success']) {
            Log::error('[checkout] step 1 FAILED: createCustomerProfile', [
                'payment_id' => $payment->id,
                'message'    => $profile['message'] ?? null,
                'raw'        => $profile['raw'] ?? null,
            ]);
            $payment->update(['status' => 'failed', 'meta' => ['error' => $profile['message']]]);

            return back()->withInput()->withErrors(['card' => 'We could not verify your card: ' . $profile['message']]);
        }

        Log::info('[checkout] step 1 OK: profile created', [
            'payment_id'         => $payment->id,
            'profile_id'         => $profile['profileId'] ?? null,
            'payment_profile_id' => $profile['paymentProfileId'] ?? null,
        ]);

        // 2) Charge the down payment against the stored profile.
        Log::info('[checkout] step 2: charging profile', [
            'payment_id' => $payment->id,
            'amount'     => (float) $planData['down'],
        ]);
        try {
            $charge = $anet->chargeProfile(
                $profile['profileId'],
                $profile['paymentProfileId'],
                (float) $planData['down'],
                'SPK-' . $payment->id,
            );
        } catch (\Throwable $e) {
            // Network error mid-charge: we cannot be sure whether the card was
            // charged. Flag for manual review and tell the user NOT to retry.
            Log::critical('[checkout] step 2 EXCEPTION: chargeProfile — AMBIGUOUS CHARGE STATE', [
                'payment_id' => $payment->id,
                'exception'  => $e->getMessage(),
            ]);
            $payment->update(['status' => 'review', 'meta' => array_merge($payment->meta ?? [], ['error' => 'charge_exception: ' . $e->getMessage()])]);

            return back()->withInput()->withErrors(['card' => 'Your payment may not have completed and we could not confirm it. Please do NOT retry — contact us and we will confirm your order right away.']);
        }

        if (! $charge['success']) {
            Log::error('[checkout] step 2 FAILED: chargeProfile', [
                'payment_id' => $payment->id,
                'message'    => $charge['message'] ?? null,
                'raw'        => $charge['raw'] ?? null,
            ]);
            $payment->update(['status' => 'failed', 'meta' => ['error' => $charge['message']]]);

            return back()->withInput()->withErrors(['card' => 'Payment declined: ' . $charge['message']]);
        }

        Log::info('[checkout] step 2 OK: charge approved', [
            'payment_id'     => $payment->id,
            'transaction_id' => $charge['transactionId'] ?? null,
            'account_type'   => $charge['accountType'] ?? null,
            'last4'          => $charge['last4'] ?? null,
        ]);

        $payment->update([
            'status'                 => 'paid',
            'authnet_transaction_id' => $charge['transactionId'],
            'card_type'              => $charge['accountType'],
            'card_last4'             => $charge['last4'],
            'onboarding_token'       => Str::random(40),
            'meta'                   => array_merge($payment->meta ?? [], [
                'authnet_profile_id'         => $profile['profileId'],
                'authnet_payment_profile_id' => $profile['paymentProfileId'],
            ]),
        ]);

        // 3) Create the recurring subscription from the same stored profile.
        if (! empty($planData['monthly'])) {
            Log::info('[checkout] step 3: creating subscription', [
                'payment_id' => $payment->id,
                'monthly'    => (float) $planData['monthly'],
            ]);
            try {
                $sub = $anet->createSubscriptionFromProfile(
                    $profile['profileId'],
                    $profile['paymentProfileId'],
                    (float) $planData['monthly'],
                    'Sparkman ' . $planData['name'],
                );
            } catch (\Throwable $e) {
                // Down payment already succeeded — never fail the request here.
                // Record the subscription as at-risk so the team can set it up.
                Log::error('[checkout] step 3 EXCEPTION: createSubscriptionFromProfile', [
                    'payment_id' => $payment->id,
                    'exception'  => $e->getMessage(),
                ]);
                $sub = ['success' => false, 'subscriptionId' => null, 'message' => 'subscription_exception: ' . $e->getMessage()];
            }

            $subscription = Subscription::create([
                'payment_id'              => $payment->id,
                'authnet_subscription_id' => $sub['subscriptionId'] ?? null,
                'name'                    => $payment->name,
                'email'                   => $payment->email,
                'plan'                    => $plan,
                'amount'                  => $planData['monthly'],
                'interval'                => 'monthly',
                'status'                  => $sub['success'] ? 'active' : 'at_risk',
                'started_at'              => now(),
                'next_billing_at'         => now()->addDays(30),
                'meta'                    => $sub['success'] ? null : ['error' => $sub['message']],
            ]);

            $payment->update(['authnet_subscription_id' => $subscription->authnet_subscription_id]);

            Log::info('[checkout] step 3 done: subscription', [
                'payment_id'      => $payment->id,
                'success'         => $sub['success'] ?? false,
                'subscription_id' => $sub['subscriptionId'] ?? null,
                'message'         => $sub['message'] ?? null,
            ]);
        }

        // Off to the service agreement (must sign before onboarding).
        Log::info('[checkout] COMPLETE, redirecting to agreement', [
            'payment_id'     => $payment->id,
            'transaction_id' => $charge['transactionId'] ?? null,
        ]);

        return redirect()->route('agreement.show', $payment->onboarding_token);
    }

    public function thankYou(Payment $payment)
    {
        return view('checkout.thankyou', compact('payment'));
    }
}
