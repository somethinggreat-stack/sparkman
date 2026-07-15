<?php

namespace App\Services;

use App\Models\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleXMLElement;
use Throwable;

/**
 * Credit Repair Cloud integration.
 *
 * Ported from the Victoria Funnel project: posts an XML <crcloud><lead> record
 * to CRC's /api/lead/insertRecord endpoint (form-encoded apiauthkey/secretkey/xmlData)
 * and parses the XML response for success/errors.
 *
 * sync(Client) is the app entry point (called after onboarding); insertClient()
 * is the low-level Victoria-compatible method also used by `php artisan crc:test`.
 */
class CreditRepairCloud
{
    protected string $apiKey;
    protected string $secretKey;
    protected string $baseUrl;
    protected ?string $assignedTo;
    protected ?string $agreement;

    public function __construct()
    {
        $this->apiKey     = (string) config('services.crc.api_key');
        $this->secretKey  = (string) config('services.crc.secret_key');
        $this->baseUrl    = rtrim((string) config('services.crc.base_url'), '/');
        $this->assignedTo = config('services.crc.assigned_to');
        $this->agreement  = config('services.crc.agreement');
    }

    public function isConfigured(): bool
    {
        return $this->apiKey !== '' && $this->secretKey !== '';
    }

    /**
     * App entry point: push a newly-onboarded Client to CRC and record the outcome.
     * Never throws — onboarding always completes; the sync result is stored on the client.
     */
    public function sync(Client $client): bool
    {
        if (! $this->isConfigured()) {
            Log::info('CRC sync skipped (not configured)', ['client_id' => $client->id]);
            return false;
        }

        // Full SSN (decrypted by the model cast) — CRC's ssno field only takes the last 4.
        $fullSsn = preg_replace('/\D+/', '', (string) $client->ssn);

        // Phone → digits, drop a US leading 1 so CRC gets a clean 10-digit number.
        $phone = preg_replace('/\D+/', '', (string) $client->phone);
        if (strlen($phone) === 11 && str_starts_with($phone, '1')) {
            $phone = substr($phone, 1);
        }

        $payload = [
            'type'           => 'Client',
            'firstname'      => $client->first_name,
            'lastname'       => $client->last_name,
            'middlename'     => $client->middle_name,
            'suffix'         => $client->suffix,
            'email'          => $client->email,
            'phone'          => $phone,
            'street_address' => $client->street,
            'city'           => $client->city,
            'state'          => $client->state,
            'zip'            => $client->zip,
            'ssn'            => $fullSsn !== '' ? substr($fullSsn, -4) : null,
            'birth_date'     => $client->dob ? $client->dob->format('m/d/Y') : null,
            'memo'           => sprintf(
                "Onboarded via Sparkman Solutions at %s\nFull SSN on file: %s",
                now()->toDateTimeString(),
                $fullSsn
            ),
        ];

        $result = $this->insertClient($payload);

        $meta = $client->meta ?? [];
        $meta['crc'] = [
            'status'  => $result['ok'] ? 'sent' : 'failed',
            'message' => $result['message'] ?? null,
            'at'      => now()->toDateTimeString(),
        ];
        $update = ['meta' => $meta];

        if ($result['ok']) {
            $update['crc_synced_at'] = now();
            $cid = data_get($result['parsed'], 'result.client_id')
                ?? data_get($result['parsed'], 'client_id')
                ?? data_get($result['parsed'], 'result.id');
            if ($cid) {
                $update['crc_contact_id'] = is_array($cid) ? (string) reset($cid) : (string) $cid;
            }
        } else {
            Log::warning('CRC client creation failed', ['client_id' => $client->id, 'message' => $result['message']]);
        }

        $client->update($update);

        return $result['ok'];
    }

    /**
     * Insert a new Client (or Lead) into Credit Repair Cloud.
     *
     * @param array $data Normalized client data
     * @return array{ok: bool, status: int, body: string, parsed: ?array, message: ?string}
     */
    public function insertClient(array $data): array
    {
        $xml = new SimpleXMLElement('<crcloud/>');
        $lead = $xml->addChild('lead');

        $this->addChildIfPresent($lead, 'type', $data['type'] ?? 'Client');
        $this->addChildIfPresent($lead, 'firstname',  $data['firstname']  ?? null);
        $this->addChildIfPresent($lead, 'lastname',   $data['lastname']   ?? null);
        $this->addChildIfPresent($lead, 'middlename', $data['middlename'] ?? null);
        $this->addChildIfPresent($lead, 'suffix',     $data['suffix']     ?? null);
        $this->addChildIfPresent($lead, 'email',      $data['email']      ?? null);

        // Phone numbers — strip to digits, send as mobile
        if (!empty($data['phone'])) {
            $digits = preg_replace('/\D+/', '', $data['phone']);
            $this->addChildIfPresent($lead, 'phone_mobile', $digits);
        }

        $this->addChildIfPresent($lead, 'street_address', $data['street_address'] ?? null);
        $this->addChildIfPresent($lead, 'city',           $data['city']           ?? null);
        $this->addChildIfPresent($lead, 'state',          isset($data['state']) ? strtoupper($data['state']) : null);
        $this->addChildIfPresent($lead, 'zip',            $data['zip']            ?? null);

        if (!empty($data['ssn'])) {
            $this->addChildIfPresent($lead, 'ssno', preg_replace('/\D+/', '', $data['ssn']));
        }

        $this->addChildIfPresent($lead, 'birth_date', $data['birth_date'] ?? null);
        $this->addChildIfPresent($lead, 'memo',       $data['memo']       ?? null);

        if ($this->assignedTo) {
            $lead->addChild('client_assigned_to', $this->assignedTo);
        }

        // Portal access is only allowed when type === "Client" per CRC error 4412.
        // For Leads we skip it entirely.
        $type = $data['type'] ?? 'Client';
        if ($type === 'Client' && !empty($data['email'])) {
            $lead->addChild('client_portal_access', 'on');
            $lead->addChild('client_userid', htmlspecialchars($data['email'], ENT_XML1 | ENT_QUOTES, 'UTF-8'));
            if ($this->agreement) {
                $lead->addChild('client_agreement', $this->agreement);
            }
            $lead->addChild('send_setup_password_info_via_email', 'yes');
        }

        return $this->postXml('/api/lead/insertRecord', $xml);
    }

    /**
     * Low-level: post XML to a CRC endpoint and parse the response.
     */
    public function postXml(string $path, SimpleXMLElement $xml): array
    {
        $url = $this->baseUrl . $path;
        $body = $xml->asXML();

        try {
            $response = Http::asForm()
                ->withOptions(['verify' => $this->sslVerify()])
                ->timeout(30)
                ->post($url, [
                    'apiauthkey' => $this->apiKey,
                    'secretkey'  => $this->secretKey,
                    'xmlData'    => $body,
                ]);
        } catch (Throwable $e) {
            Log::error('CRC request threw', ['url' => $url, 'error' => $e->getMessage()]);
            return ['ok' => false, 'status' => 0, 'body' => '', 'parsed' => null, 'message' => $e->getMessage()];
        }

        $rawBody = $response->body();

        $parsed = null;
        try {
            $parsed = $rawBody !== '' ? json_decode(json_encode(simplexml_load_string($rawBody)), true) : null;
        } catch (\Throwable $e) {
            $parsed = null;
        }

        // Start from HTTP success — then narrow based on the XML body.
        $ok = $response->successful();
        $message = null;
        $errorNo = null;

        if ($parsed) {
            // Common <success>True|False</success> envelope
            $successVal = $parsed['success'] ?? $parsed['Success'] ?? null;
            if (is_array($successVal)) $successVal = (string) reset($successVal);
            if (is_string($successVal) && strtolower(trim($successVal)) === 'false') {
                $ok = false;
            }

            // Pull nested <result><errors><error_no/error_message></errors></result>
            $errors = $parsed['result']['errors'] ?? $parsed['Result']['errors'] ?? null;
            if (is_array($errors)) {
                $errorNo = $errors['error_no']      ?? $errors['ErrorNo']      ?? null;
                $message = $errors['error_message'] ?? $errors['ErrorMessage'] ?? null;
                if (is_array($errorNo)) $errorNo = (string) reset($errorNo);
                if (is_array($message)) $message = (string) reset($message);
                if ($errorNo) $ok = false;
            }

            // Fallback locations
            $message = $message
                ?? ($parsed['message'] ?? null)
                ?? ($parsed['Message'] ?? null)
                ?? ($parsed['result']['message'] ?? null);
            if (is_array($message)) $message = (string) reset($message);
        }

        // Defensive: any "error" marker in the raw body should fail us
        if (stripos($rawBody, '<error_no>') !== false
            || stripos($rawBody, '<errorcode>') !== false) {
            $ok = false;
        }

        Log::channel('stack')->info('CRC request', [
            'url'    => $url,
            'status' => $response->status(),
            'sent'   => $body,
            'body'   => $rawBody,
            'ok'     => $ok,
        ]);

        return [
            'ok'      => $ok,
            'status'  => $response->status(),
            'body'    => $rawBody,
            'parsed'  => $parsed,
            'message' => $message,
        ];
    }

    /**
     * Guzzle "verify" value: use the bundled CA cert if present (needed on
     * Windows/dev where PHP has no system CA bundle), otherwise default true.
     */
    protected function sslVerify()
    {
        $ca = storage_path('certs/cacert.pem');
        return is_file($ca) ? $ca : true;
    }

    protected function addChildIfPresent(SimpleXMLElement $parent, string $name, $value): void
    {
        if ($value === null || $value === '') return;
        // Escape special chars for safe XML
        $parent->addChild($name, htmlspecialchars((string) $value, ENT_XML1 | ENT_QUOTES, 'UTF-8'));
    }
}
