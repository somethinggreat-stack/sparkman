<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\WebhookEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /**
     * Authorize.Net webhook receiver.
     *
     * Configure in the Authorize.Net dashboard → Account → Webhooks:
     *   Endpoint:  https://YOUR-DOMAIN/webhooks/authorizenet
     *   Events:
     *     - net.authorize.customer.subscription.suspended
     *     - net.authorize.customer.subscription.terminated
     *     - net.authorize.customer.subscription.cancelled
     *     - net.authorize.customer.subscription.expiring
     *     - net.authorize.payment.authcapture.created
     *     - net.authorize.payment.refund.created
     */
    public function authorizenet(Request $request)
    {
        $raw = $request->getContent();

        // Verify the HMAC-SHA512 signature when a signature key is configured.
        $signatureKey = config('services.authorizenet.signature_key');
        if (! empty($signatureKey)) {
            $header = $request->header('X-ANET-Signature', '');
            $expected = 'sha512=' . strtoupper(hash_hmac('sha512', $raw, $signatureKey));
            if (! $header || ! hash_equals(strtoupper($header), strtoupper($expected))) {
                Log::warning('Authorize.Net webhook signature mismatch');
                return response()->json(['ok' => false], 401);
            }
        }

        $payload   = json_decode($raw, true) ?: [];
        $eventType = $payload['eventType'] ?? '';
        $subId     = $payload['payload']['id'] ?? $payload['payload']['subscription']['id'] ?? null;

        Log::info('Authorize.Net webhook', ['event' => $eventType, 'sub' => $subId]);

        WebhookEvent::create([
            'source'     => 'authorizenet',
            'event_type' => $eventType,
            'reference'  => $subId,
            'summary'    => $this->summarize($eventType, $subId),
            'payload'    => $payload,
        ]);

        if ($subId) {
            $sub = Subscription::where('authnet_subscription_id', $subId)->first();

            if ($sub) {
                switch ($eventType) {
                    case 'net.authorize.payment.authcapture.created':
                        $sub->update([
                            'status'          => 'active',
                            'failed_payments' => 0,
                            'last_payment_at' => now(),
                            'next_billing_at' => now()->addMonth(),
                        ]);
                        break;

                    case 'net.authorize.customer.subscription.expiring':
                        $sub->update(['status' => 'at_risk']);
                        break;

                    case 'net.authorize.customer.subscription.suspended':
                        $sub->update(['status' => 'past_due', 'failed_payments' => $sub->failed_payments + 1]);
                        break;

                    case 'net.authorize.customer.subscription.terminated':
                    case 'net.authorize.customer.subscription.cancelled':
                        $sub->update(['status' => 'cancelled']);
                        break;
                }
            }
        }

        return response()->json(['ok' => true]);
    }

    protected function summarize(string $eventType, ?string $ref): string
    {
        $label = [
            'net.authorize.payment.authcapture.created'         => 'Payment captured',
            'net.authorize.customer.subscription.expiring'      => 'Subscription expiring',
            'net.authorize.customer.subscription.suspended'     => 'Subscription suspended',
            'net.authorize.customer.subscription.terminated'    => 'Subscription terminated',
            'net.authorize.customer.subscription.cancelled'     => 'Subscription cancelled',
            'net.authorize.payment.refund.created'              => 'Refund created',
        ][$eventType] ?? $eventType;

        return $ref ? "{$label} · {$ref}" : $label;
    }
}
