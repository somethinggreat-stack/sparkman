<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Thin Authorize.Net client (JSON API, no SDK dependency).
 *
 * Uses Accept.js opaque data (payment nonce) so raw card data never
 * touches our server — keeps us out of heavy PCI scope.
 */
class AuthorizeNet
{
    public function __construct(
        protected ?string $loginId = null,
        protected ?string $transactionKey = null,
        protected ?string $env = null,
    ) {
        $this->loginId        = $loginId ?? config('services.authorizenet.login_id');
        $this->transactionKey = $transactionKey ?? config('services.authorizenet.transaction_key');
        $this->env            = $env ?? config('services.authorizenet.env', 'sandbox');
    }

    public function endpoint(): string
    {
        return $this->env === 'production'
            ? 'https://api.authorize.net/xml/v1/request.api'
            : 'https://apitest.authorize.net/xml/v1/request.api';
    }

    public function isConfigured(): bool
    {
        return ! empty($this->loginId) && ! empty($this->transactionKey);
    }

    /**
     * SSL verify option for outbound calls.
     * Returns a CA-bundle path when available, false when explicitly disabled,
     * otherwise true (system default).
     */
    protected function sslVerify(): bool|string
    {
        if (config('services.authorizenet.verify_ssl', true) === false) {
            return false;
        }
        $bundle = config('services.authorizenet.ca_bundle');
        return ($bundle && is_file($bundle)) ? $bundle : true;
    }

    protected function auth(): array
    {
        return ['name' => $this->loginId, 'transactionKey' => $this->transactionKey];
    }

    protected function send(array $payload): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Authorize.Net is not configured. Set AUTHORIZENET_LOGIN_ID and AUTHORIZENET_TRANSACTION_KEY in .env.');
        }

        $response = Http::acceptJson()
            ->withOptions(['verify' => $this->sslVerify()])
            ->timeout(30)
            ->post($this->endpoint(), $payload);

        // Authorize.Net returns JSON with a UTF-8 BOM that breaks strict parsers.
        $body = ltrim($response->body(), "\xEF\xBB\xBF \t\n\r");
        $json = json_decode($body, true) ?: [];

        return $json;
    }

    /**
     * Charge a one-time amount using an Accept.js opaque payment nonce.
     *
     * @return array{success:bool,transactionId:?string,accountType:?string,last4:?string,message:string,raw:array}
     */
    public function chargeOpaque(string $dataDescriptor, string $dataValue, float $amount, array $customer = [], string $invoice = null): array
    {
        $payload = [
            'createTransactionRequest' => [
                'merchantAuthentication' => $this->auth(),
                'refId' => Str::limit($invoice ?? 'inv', 20, ''),
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => number_format($amount, 2, '.', ''),
                    'payment' => [
                        'opaqueData' => [
                            'dataDescriptor' => $dataDescriptor,
                            'dataValue' => $dataValue,
                        ],
                    ],
                    'customer' => array_filter([
                        'email' => $customer['email'] ?? null,
                    ]),
                    'billTo' => array_filter([
                        'firstName' => $customer['first_name'] ?? null,
                        'lastName'  => $customer['last_name'] ?? null,
                        'email'     => $customer['email'] ?? null,
                    ]),
                ],
            ],
        ];

        $json = $this->send($payload);

        return $this->normalizeTransaction($json);
    }

    /**
     * Create a recurring (ARB) subscription billed monthly.
     *
     * @return array{success:bool,subscriptionId:?string,message:string,raw:array}
     */
    public function createSubscription(string $dataDescriptor, string $dataValue, float $monthlyAmount, string $name, array $customer, int $totalOccurrences = 9999, int $startInDays = 30): array
    {
        $start = now()->addDays($startInDays)->toDateString();

        $payload = [
            'ARBCreateSubscriptionRequest' => [
                'merchantAuthentication' => $this->auth(),
                'subscription' => [
                    'name' => Str::limit($name, 50, ''),
                    'paymentSchedule' => [
                        'interval' => ['length' => 1, 'unit' => 'months'],
                        'startDate' => $start,
                        'totalOccurrences' => $totalOccurrences,
                    ],
                    'amount' => number_format($monthlyAmount, 2, '.', ''),
                    'payment' => [
                        'opaqueData' => [
                            'dataDescriptor' => $dataDescriptor,
                            'dataValue' => $dataValue,
                        ],
                    ],
                    'customer' => array_filter(['email' => $customer['email'] ?? null]),
                    'billTo' => array_filter([
                        'firstName' => $customer['first_name'] ?? null,
                        'lastName'  => $customer['last_name'] ?? null,
                    ]),
                ],
            ],
        ];

        $json = $this->send($payload);
        $ok = ($json['messages']['resultCode'] ?? '') === 'Ok';

        return [
            'success' => $ok,
            'subscriptionId' => $json['subscriptionId'] ?? null,
            'message' => $json['messages']['message'][0]['text'] ?? ($ok ? 'Subscription created' : 'Subscription failed'),
            'raw' => $json,
        ];
    }

    /**
     * Create a reusable Customer Profile from an Accept.js opaque nonce.
     * The nonce is single-use, so we store the card as a profile and reuse it
     * for both the down payment and the recurring subscription.
     *
     * @return array{success:bool,profileId:?string,paymentProfileId:?string,message:string,raw:array}
     */
    public function createCustomerProfile(string $dataDescriptor, string $dataValue, array $customer, string $ref): array
    {
        $payload = [
            'createCustomerProfileRequest' => [
                'merchantAuthentication' => $this->auth(),
                'profile' => [
                    'merchantCustomerId' => Str::limit($ref, 20, ''),
                    'description' => Str::limit('Sparkman ' . $ref, 255, ''),
                    'email' => $customer['email'] ?? null,
                    'paymentProfiles' => [
                        'customerType' => 'individual',
                        'billTo' => array_filter([
                            'firstName' => $customer['first_name'] ?? null,
                            'lastName'  => $customer['last_name'] ?? null,
                        ]),
                        'payment' => [
                            'opaqueData' => ['dataDescriptor' => $dataDescriptor, 'dataValue' => $dataValue],
                        ],
                    ],
                ],
                'validationMode' => 'none',
            ],
        ];

        $json = $this->send($payload);
        $ok = ($json['messages']['resultCode'] ?? '') === 'Ok';

        $profileId = $json['customerProfileId'] ?? null;
        $paymentProfileId = $json['customerPaymentProfileIdList'][0]
            ?? ($json['customerPaymentProfileId'] ?? null);

        // Handle the "duplicate profile" case — reuse the existing profile.
        if (! $ok && ! $profileId) {
            $text = $json['messages']['message'][0]['text'] ?? '';
            if (preg_match('/ID (\d+) already exists/i', $text, $m)) {
                $profileId = $m[1];
                $paymentProfileId = $this->firstPaymentProfileId($profileId);
                $ok = (bool) $paymentProfileId;
            }
        }

        return [
            'success'          => $ok && $profileId && $paymentProfileId,
            'profileId'        => $profileId,
            'paymentProfileId' => $paymentProfileId,
            'message'          => $json['messages']['message'][0]['text'] ?? ($ok ? 'Profile created' : 'Profile failed'),
            'raw'              => $json,
        ];
    }

    /** Look up the first payment profile id on an existing customer profile. */
    protected function firstPaymentProfileId(string $profileId): ?string
    {
        $json = $this->send([
            'getCustomerProfileRequest' => [
                'merchantAuthentication' => $this->auth(),
                'customerProfileId' => $profileId,
            ],
        ]);

        return $json['profile']['paymentProfiles'][0]['customerPaymentProfileId'] ?? null;
    }

    /** Charge a stored customer payment profile (used for the down payment). */
    public function chargeProfile(string $profileId, string $paymentProfileId, float $amount, string $invoice = null): array
    {
        $json = $this->send([
            'createTransactionRequest' => [
                'merchantAuthentication' => $this->auth(),
                'refId' => Str::limit($invoice ?? 'inv', 20, ''),
                'transactionRequest' => [
                    'transactionType' => 'authCaptureTransaction',
                    'amount' => number_format($amount, 2, '.', ''),
                    'profile' => [
                        'customerProfileId' => $profileId,
                        'paymentProfile' => ['paymentProfileId' => $paymentProfileId],
                    ],
                ],
            ],
        ]);

        return $this->normalizeTransaction($json);
    }

    /** Create a monthly ARB subscription from a stored customer payment profile. */
    public function createSubscriptionFromProfile(string $profileId, string $paymentProfileId, float $monthlyAmount, string $name, int $totalOccurrences = 9999, int $startInDays = 30): array
    {
        $json = $this->send([
            'ARBCreateSubscriptionRequest' => [
                'merchantAuthentication' => $this->auth(),
                'subscription' => [
                    'name' => Str::limit($name, 50, ''),
                    'paymentSchedule' => [
                        'interval' => ['length' => 1, 'unit' => 'months'],
                        'startDate' => now()->addDays($startInDays)->toDateString(),
                        'totalOccurrences' => $totalOccurrences,
                    ],
                    'amount' => number_format($monthlyAmount, 2, '.', ''),
                    'profile' => [
                        'customerProfileId' => $profileId,
                        'customerPaymentProfileId' => $paymentProfileId,
                    ],
                ],
            ],
        ]);

        $ok = ($json['messages']['resultCode'] ?? '') === 'Ok';

        return [
            'success' => $ok,
            'subscriptionId' => $json['subscriptionId'] ?? null,
            'message' => $json['messages']['message'][0]['text'] ?? ($ok ? 'Subscription created' : 'Subscription failed'),
            'raw' => $json,
        ];
    }

    protected function normalizeTransaction(array $json): array
    {
        $resultOk = ($json['messages']['resultCode'] ?? '') === 'Ok';
        $tx = $json['transactionResponse'] ?? [];
        $responseCode = $tx['responseCode'] ?? null; // 1 = approved
        $approved = $resultOk && $responseCode === '1';

        $message = $tx['messages'][0]['description']
            ?? $tx['errors'][0]['errorText']
            ?? $json['messages']['message'][0]['text']
            ?? ($approved ? 'Approved' : 'Declined');

        return [
            'success'       => $approved,
            'transactionId' => $tx['transId'] ?? null,
            'accountType'   => $tx['accountType'] ?? null,
            'last4'         => isset($tx['accountNumber']) ? substr(preg_replace('/\D/', '', $tx['accountNumber']), -4) : null,
            'message'       => $message,
            'raw'           => $json,
        ];
    }
}
