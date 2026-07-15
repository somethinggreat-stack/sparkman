<?php

namespace App\Services;

use App\Models\Lead;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Pushes leads/qualifier results into GoHighLevel (CRM + automations).
 *
 * Safe no-op until GHL_ENABLED=true and GHL_API_KEY + GHL_LOCATION_ID are set,
 * so the funnel never fails if GHL isn't wired yet. Mirrors CreditRepairCloud.
 */
class GoHighLevel
{
    public function isEnabled(): bool
    {
        return (bool) config('services.ghl.enabled')
            && ! empty(config('services.ghl.api_key'))
            && ! empty(config('services.ghl.location_id'));
    }

    /**
     * Upsert a contact in GHL with tags reflecting the funnel outcome.
     * Tags let the client's GHL workflows (email/SMS/AI chatbot/pipeline) fire.
     */
    public function pushLead(Lead $lead): bool
    {
        if (! $this->isEnabled()) {
            Log::info('GHL push skipped (not configured)', ['lead_id' => $lead->id, 'type' => $lead->type]);
            return false;
        }

        [$first, $last] = $this->splitName($lead->name);

        $tags = array_values(array_filter([
            'sparkman-website',
            $lead->type,                                   // funding | credit-repair | popup | ...
            $lead->qualified === true ? 'funding-qualified' : null,
            $lead->qualified === false ? 'credit-repair-recommended' : null,
        ]));

        try {
            $response = $this->api()
                ->post(rtrim(config('services.ghl.endpoint'), '/') . '/contacts/upsert', [
                    'locationId' => config('services.ghl.location_id'),
                    'firstName'  => $first,
                    'lastName'   => $last,
                    'email'      => $lead->email,
                    'phone'      => $lead->phone,
                    'tags'       => $tags,
                    'source'     => 'Sparkman Website Qualifier',
                    'customFields' => $this->customFields($lead),
                ]);

            if ($response->successful()) {
                $contactId = $response->json('contact.id') ?? $response->json('id');
                $meta = array_merge($lead->meta ?? [], [
                    'ghl_contact_id' => $contactId,
                    'ghl_synced_at'  => now()->toDateTimeString(),
                ]);

                // Drop the lead into the matching pipeline as an opportunity.
                if ($contactId) {
                    $oppId = $this->createOpportunity($lead, $contactId, $tags);
                    if ($oppId) $meta['ghl_opportunity_id'] = $oppId;
                }

                $lead->update(['meta' => $meta]);
                return true;
            }

            Log::warning('GHL push failed', ['lead_id' => $lead->id, 'status' => $response->status(), 'body' => $response->body()]);
        } catch (Throwable $e) {
            Log::error('GHL push error', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }

        return false;
    }

    /**
     * Create an opportunity in the pipeline that matches the funnel outcome:
     * qualified → "Funding", not qualified → "Credit Repair". Placed in the
     * first stage. Returns the opportunity id (or null).
     */
    protected function createOpportunity(Lead $lead, string $contactId, array $tags): ?string
    {
        $pipelineName = $lead->qualified ? 'Funding' : 'Credit Repair';
        $pipeline = $this->pipelineByName($pipelineName);
        if (! $pipeline) {
            Log::warning('GHL pipeline not found', ['name' => $pipelineName]);
            return null;
        }

        $stages = collect($pipeline['stages'] ?? [])->sortBy('position')->values();
        $firstStageId = $stages->first()['id'] ?? null;
        if (! $firstStageId) return null;

        // Dedup: if this contact already has an opportunity in this pipeline, reuse it.
        if ($existing = $this->existingOpportunity($contactId, $pipeline['id'])) {
            return $existing;
        }

        try {
            $res = $this->api()->post(rtrim(config('services.ghl.endpoint'), '/') . '/opportunities/', [
                'pipelineId'      => $pipeline['id'],
                'locationId'      => config('services.ghl.location_id'),
                'name'            => trim($lead->name) . ' — ' . $pipelineName,
                'pipelineStageId' => $firstStageId,
                'status'          => 'open',
                'contactId'       => $contactId,
                'monetaryValue'   => $this->amountToNumber($lead->amount),
            ]);

            if ($res->successful()) {
                return $res->json('opportunity.id') ?? $res->json('id');
            }
            Log::warning('GHL opportunity create failed', ['lead_id' => $lead->id, 'status' => $res->status(), 'body' => $res->body()]);
        } catch (Throwable $e) {
            Log::error('GHL opportunity error', ['lead_id' => $lead->id, 'error' => $e->getMessage()]);
        }
        return null;
    }

    /** Return an existing opportunity id for this contact in the given pipeline, or null. */
    protected function existingOpportunity(string $contactId, string $pipelineId): ?string
    {
        try {
            $res = $this->api()->get(rtrim(config('services.ghl.endpoint'), '/') . '/opportunities/search', [
                'location_id' => config('services.ghl.location_id'),
                'contact_id'  => $contactId,
                'pipeline_id' => $pipelineId,
            ]);
            if ($res->successful()) {
                foreach (($res->json('opportunities') ?? []) as $opp) {
                    if (($opp['pipelineId'] ?? null) === $pipelineId) {
                        return $opp['id'] ?? null;
                    }
                }
            }
        } catch (Throwable $e) {
            Log::warning('GHL opportunity dedup check failed', ['error' => $e->getMessage()]);
        }
        return null;
    }

    /** Fetch a pipeline by name (cached for the request). */
    protected function pipelineByName(string $name): ?array
    {
        static $pipelines = null;
        if ($pipelines === null) {
            $resp = $this->api()->get(rtrim(config('services.ghl.endpoint'), '/') . '/opportunities/pipelines', [
                'locationId' => config('services.ghl.location_id'),
            ]);
            $pipelines = $resp->successful() ? ($resp->json('pipelines') ?? []) : [];
        }
        foreach ($pipelines as $p) {
            if (strtolower($p['name'] ?? '') === strtolower($name)) return $p;
        }
        return null;
    }

    /** Pull the first dollar figure out of an amount string (e.g. "$25,000 – $75,000" → 25000). */
    protected function amountToNumber($amount): float
    {
        if (preg_match('/[\d,]+/', (string) $amount, $m)) {
            return (float) str_replace(',', '', $m[0]);
        }
        return 0.0;
    }

    /** Shared GHL API client (Bearer + Version + CA bundle). */
    protected function api()
    {
        return Http::withToken(config('services.ghl.api_key'))
            ->withHeaders(['Version' => '2021-07-28'])
            ->acceptJson()
            ->withOptions(['verify' => $this->sslVerify()])
            ->timeout(30);
    }

    /** Use the bundled CA cert if present (Windows/dev has no system CA bundle). */
    protected function sslVerify()
    {
        $ca = storage_path('certs/cacert.pem');
        return is_file($ca) ? $ca : true;
    }

    /**
     * Map every website value to its GHL custom field (keys generated by `ghl:setup`
     * and stored in storage/app/ghl_fields.json).
     */
    protected function customFields(Lead $lead): array
    {
        $map = $this->fieldMap();
        if (empty($map)) return [];

        $q = (array) ($lead->meta['qualifier'] ?? []);
        $values = array_merge($q, [
            'amount'     => $lead->amount,
            'outcome'    => $lead->qualified ? 'Funding-Ready' : 'Credit Repair (Funding Prep)',
            'source_url' => $lead->source_url,
            'interest'   => $lead->qualified ? 'Funding' : 'Credit Repair',
        ]);

        $fields = [];
        foreach ($map as $dataKey => $def) {
            if (! array_key_exists($dataKey, $values)) continue;
            $v = $values[$dataKey];
            if ($v === null || $v === '') continue;
            $id = is_array($def) ? ($def['id'] ?? null) : $def;
            if (! $id) continue;
            $fields[] = ['id' => $id, 'value' => is_bool($v) ? ($v ? 'yes' : 'no') : (string) $v];
        }
        return $fields;
    }

    /** data_key => GHL fieldKey map produced by `php artisan ghl:setup`. */
    protected function fieldMap(): array
    {
        static $map = null;
        if ($map === null) {
            $map = \Illuminate\Support\Facades\Storage::exists('ghl_fields.json')
                ? (json_decode(\Illuminate\Support\Facades\Storage::get('ghl_fields.json'), true) ?: [])
                : [];
        }
        return $map;
    }

    protected function splitName(?string $name): array
    {
        $name = trim((string) $name);
        if ($name === '') return ['', ''];
        $parts = preg_split('/\s+/', $name, 2);
        return [$parts[0], $parts[1] ?? ''];
    }
}
