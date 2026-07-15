<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * One-shot (idempotent) provisioning of the GoHighLevel location:
 *  - creates the custom fields for every value the website sends
 *  - creates the "Credit Repair" and "Funding" pipelines (5-6 stages each)
 *  - removes the default "Marketing Pipeline"
 *  - writes the data-key -> GHL fieldKey map to storage/app/ghl_fields.json
 *
 * Re-runnable: existing fields/pipelines (matched by name) are reused, not duplicated.
 */
class GhlSetupCommand extends Command
{
    protected $signature = 'ghl:setup {--keep-marketing : Do not delete the default Marketing Pipeline}';
    protected $description = 'Provision GHL custom fields + Credit Repair/Funding pipelines for the Sparkman funnel.';

    private string $base = 'https://services.leadconnectorhq.com';

    /** data_key => human field name (GHL derives the fieldKey from the name) */
    private array $fields = [
        'funding_goal'    => 'Funding Goal',
        'amount'          => 'Funding Amount Sought',
        'credit_score'    => 'Credit Score Range',
        'cards_3500'      => 'Has 2 Plus Cards 3500 Limit',
        'open_cards'      => 'Has 4 To 5 Open Cards',
        'credit_age'      => 'Average Credit Age 5 Plus Years',
        'clean'           => 'No Collections Or Chargeoffs',
        'inquiries'       => 'Fewer Than 3 Inquiries',
        'utilization'     => 'Utilization Under 30',
        'business_name'   => 'Business Name',
        'business_age'    => 'Time In Business',
        'has_website'     => 'Has Business Website',
        'has_ein'         => 'Has EIN Or DB',
        'monthly_revenue' => 'Monthly Revenue',
        'outcome'         => 'Qualification Outcome',
        'source_url'      => 'Lead Source URL',
        'interest'        => 'Interested In',
    ];

    /** pipeline name => ordered stage names */
    private array $pipelines = [
        'Credit Repair' => [
            'New Lead', 'Onboarded (Paid)',
            'Disputes In Progress', 'Results Achieved', 'Funding-Ready',
        ],
        'Funding' => [
            'New Application', 'Contacted', 'Documents Collected',
            'Submitted to Lenders', 'Approved', 'Funded',
        ],
        // Fed by the calendar (appointments) via a GHL workflow.
        'Calls & Appointments' => [
            'Scheduled', 'Confirmed', 'Completed', 'No-Show', 'Cancelled',
        ],
    ];

    public function handle(): int
    {
        $loc = config('services.ghl.location_id');
        if (! config('services.ghl.api_key') || ! $loc) {
            $this->error('GHL not configured (GHL_API_KEY / GHL_LOCATION_ID).');
            return self::FAILURE;
        }

        // ---- 1) Custom fields ----
        $this->info('Custom fields...');
        $existing = collect(data_get($this->get("/locations/$loc/customFields"), 'customFields', []))
            ->keyBy(fn ($f) => strtolower($f['name']));

        $map = [];
        foreach ($this->fields as $dataKey => $name) {
            $found = $existing->get(strtolower($name));
            if ($found) {
                $map[$dataKey] = ['id' => $found['id'], 'key' => $found['fieldKey']];
                $this->line("  = $name  ({$found['fieldKey']})");
                continue;
            }
            $res = $this->post("/locations/$loc/customFields", [
                'name' => $name, 'dataType' => 'TEXT', 'model' => 'contact', 'placeholder' => '',
            ]);
            $id  = data_get($res, 'customField.id');
            $key = data_get($res, 'customField.fieldKey');
            if ($id) {
                $map[$dataKey] = ['id' => $id, 'key' => $key];
                $this->line("  + $name  ($key)");
            } else {
                $this->warn("  ! failed to create $name: " . json_encode($res));
            }
        }
        Storage::put('ghl_fields.json', json_encode($map, JSON_PRETTY_PRINT));
        $this->info('  Saved map -> storage/app/ghl_fields.json (' . count($map) . ' fields)');

        // ---- 2) Pipelines ----
        $this->info('Pipelines...');
        $pipes = collect(data_get($this->get("/opportunities/pipelines?locationId=$loc"), 'pipelines', []));
        $byName = $pipes->keyBy(fn ($p) => strtolower($p['name']));

        foreach ($this->pipelines as $name => $stages) {
            if ($byName->has(strtolower($name))) {
                $this->line("  = $name (exists)");
                continue;
            }
            $stagePayload = [];
            foreach (array_values($stages) as $i => $s) {
                $stagePayload[] = ['name' => $s, 'position' => $i];
            }
            $res = $this->post('/opportunities/pipelines', [
                'locationId' => $loc, 'name' => $name, 'stages' => $stagePayload,
            ]);
            $id = data_get($res, 'pipeline.id') ?? data_get($res, 'id');
            $id ? $this->line("  + $name ($id)") : $this->warn("  ! failed $name: " . json_encode($res));
        }

        // ---- 3) Remove Marketing Pipeline ----
        if (! $this->option('keep-marketing')) {
            $mk = $byName->get('marketing pipeline');
            if ($mk) {
                $del = $this->delete("/opportunities/pipelines/{$mk['id']}?locationId=$loc");
                $this->info('  Marketing Pipeline delete: ' . ($del['ok'] ? 'removed' : 'could not remove (' . $del['status'] . ') — delete it in the UI'));
            }
        }

        $this->newLine();
        $this->info('GHL setup complete.');
        return self::SUCCESS;
    }

    // ---- HTTP helpers ----
    private function client()
    {
        return Http::withToken(config('services.ghl.api_key'))
            ->withHeaders(['Version' => '2021-07-28'])->acceptJson()
            ->withOptions(['verify' => is_file(storage_path('certs/cacert.pem')) ? storage_path('certs/cacert.pem') : true])
            ->timeout(30);
    }
    private function get(string $path): array { return $this->client()->get($this->base . $path)->json() ?? []; }
    private function post(string $path, array $body): array { return $this->client()->post($this->base . $path, $body)->json() ?? []; }
    private function delete(string $path): array { $r = $this->client()->delete($this->base . $path); return ['ok' => $r->successful(), 'status' => $r->status()]; }
}
