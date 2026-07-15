<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * End-to-end smoke tests for the Sparkman Solutions site + dashboard.
 * Payments use a faked Authorize.Net endpoint (no live keys required).
 *
 * DO NOT DELETE — these guard the full lead → payment → onboarding flow.
 */
class SmokeTest extends TestCase
{
    use RefreshDatabase;

    /** @test — every public marketing page renders. */
    public function public_pages_load(): void
    {
        foreach ([
            '/', '/services', '/process', '/about', '/pricing', '/faq', '/funding',
            '/credit-repair', '/business-credit', '/debt-validation',
            '/credit-building', '/financial-coaching',
            '/checkout/individual', '/checkout/aggressive', '/checkout/couple',
            '/terms', '/privacy', '/disclaimer', '/get-funded',
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    /** @test — leads are captured and categorized by service type. */
    public function leads_are_captured_by_type(): void
    {
        $types = [
            'popup', 'credit-repair', 'business-credit',
            'debt-validation', 'credit-building', 'financial-coaching', 'funding',
        ];

        foreach ($types as $type) {
            $this->postJson('/lead', [
                'type'  => $type,
                'name'  => "Lead $type",
                'email' => "$type@example.com",
                'phone' => '5551234567',
                'goal'  => 'Test interest',
            ])->assertOk()->assertJson(['ok' => true]);

            $this->assertDatabaseHas('leads', ['type' => $type, 'email' => "$type@example.com"]);
        }
    }

    /** @test — service-specific select fields normalize into "goal". */
    public function service_select_maps_to_goal(): void
    {
        $this->postJson('/lead', [
            'type' => 'business-credit', 'name' => 'Biz', 'email' => 'biz@x.com',
            'phone' => '5550001111', 'business_stage' => '1–3 years old',
        ])->assertOk();

        $this->assertDatabaseHas('leads', ['email' => 'biz@x.com', 'goal' => '1–3 years old']);
    }

    /** @test — admin area is protected and the env login works. */
    public function admin_requires_login(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));

        // wrong credentials rejected
        $this->post(route('admin.login.post'), ['email' => 'nope@x.com', 'password' => 'bad'])
            ->assertSessionHasErrors();

        // correct credentials (from config/.env) accepted
        $this->post(route('admin.login.post'), [
            'email'    => Config::get('services.admin.email'),
            'password' => Config::get('services.admin.password'),
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertTrue(session('is_admin'));
    }

    /** @test — every dashboard view loads for an authed admin. */
    public function dashboard_views_load(): void
    {
        $this->withSession(['is_admin' => true, 'admin_email' => 'admin@x.com']);

        foreach ([
            route('admin.dashboard'),
            route('admin.search', ['q' => 'test']),
            route('admin.leads'),
            route('admin.leads.type', 'credit-repair'),
            route('admin.leads.type', 'funding'),
            route('admin.payments'),
            route('admin.clients'),
            route('admin.subscriptions'),
            route('admin.subscriptions.atrisk'),
        ] as $url) {
            $this->get($url)->assertOk();
        }
    }

    /** @test — checkout charges (mocked), records payment + subscription, redirects to onboarding. */
    public function checkout_processes_payment_and_creates_subscription(): void
    {
        Config::set('services.authorizenet.login_id', 'testlogin');
        Config::set('services.authorizenet.transaction_key', 'testkey');

        Http::fake([
            '*authorize.net*' => Http::response([
                'messages' => ['resultCode' => 'Ok', 'message' => [['code' => 'I00001', 'text' => 'Successful.']]],
                'customerProfileId' => '5000001',
                'customerPaymentProfileIdList' => ['6000001'],
                'transactionResponse' => [
                    'responseCode' => '1', 'transId' => '40000001',
                    'accountType' => 'Visa', 'accountNumber' => 'XXXX1111',
                    'messages' => [['description' => 'This transaction has been approved.']],
                ],
                'subscriptionId' => '9990001',
            ], 200),
        ]);

        $response = $this->post(route('checkout.process', 'individual'), [
            'first_name' => 'Jane', 'last_name' => 'Smith',
            'email' => 'jane@example.com', 'phone' => '5551234567',
            'card_name' => 'Jane Smith', 'street' => '1 Main St', 'city' => 'Detroit', 'state' => 'MI', 'zip' => '48201',
            'terms' => '1', 'privacy' => '1',
            'data_descriptor' => 'COMMON.ACCEPT.INAPP.PAYMENT',
            'data_value' => 'fake-nonce-value',
        ]);

        $payment = Payment::first();
        $this->assertNotNull($payment);
        $this->assertEquals('paid', $payment->status);
        $this->assertEquals('40000001', $payment->authnet_transaction_id);
        $this->assertEquals('1111', $payment->card_last4);
        $this->assertNotNull($payment->onboarding_token);

        // After payment, the client must sign the service agreement before onboarding.
        $response->assertRedirect(route('agreement.show', $payment->onboarding_token));

        $this->assertDatabaseHas('subscriptions', [
            'payment_id' => $payment->id, 'status' => 'active', 'amount' => 99.00,
        ]);
    }

    /** @test — the service agreement is mandatory: onboarding is gated until it's signed. */
    public function agreement_must_be_signed_before_onboarding(): void
    {
        $payment = Payment::create([
            'name' => 'Signer One', 'email' => 'signer@example.com', 'phone' => '5551234567',
            'plan' => 'individual', 'amount' => 497, 'monthly_amount' => 99,
            'status' => 'paid', 'onboarding_token' => Str::random(40),
        ]);
        $token = $payment->onboarding_token;

        // Agreement page renders with the client's name auto-filled.
        $this->get(route('agreement.show', $token))->assertOk()->assertSee('Signer One');

        // Onboarding is blocked until the agreement is signed.
        $this->get(route('onboarding.show', $token))->assertRedirect(route('agreement.show', $token));

        // Cannot POST onboarding without signing.
        $this->post(route('onboarding.store', $token), [
            'first_name' => 'Signer', 'last_name' => 'One', 'email' => 'signer@example.com',
            'phone' => '5551234567', 'ssn' => '123-45-6789',
            'dob_month' => 5, 'dob_day' => 14, 'dob_year' => 1990,
        ])->assertRedirect(route('agreement.show', $token));
        $this->assertDatabaseCount('clients', 0);

        // Signing without a signature / without agreeing is rejected.
        $this->post(route('agreement.sign', $token), ['signature' => '', 'agree' => '1'])
            ->assertSessionHasErrors('signature');
        $this->post(route('agreement.sign', $token), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
        ])->assertSessionHasErrors('agree');

        // Valid signature stores the record and forwards to onboarding.
        $this->post(route('agreement.sign', $token), [
            'signature' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==',
            'agree' => '1',
        ])->assertRedirect(route('onboarding.show', $token));

        $payment->refresh();
        $this->assertNotNull($payment->agreement_signed_at);
        $this->assertEquals('Signer One', $payment->agreement_name);
        $this->assertStringStartsWith('data:image/png;base64,', $payment->agreement_signature);

        // Now onboarding is reachable.
        $this->get(route('onboarding.show', $token))->assertOk();
    }

    /** @test — declined card marks the payment failed and does not onboard. */
    public function declined_card_is_handled(): void
    {
        Config::set('services.authorizenet.login_id', 'testlogin');
        Config::set('services.authorizenet.transaction_key', 'testkey');

        Http::fake([
            '*authorize.net*' => Http::response([
                'messages' => ['resultCode' => 'Ok'],
                'transactionResponse' => [
                    'responseCode' => '2',
                    'errors' => [['errorCode' => '2', 'errorText' => 'This transaction has been declined.']],
                ],
            ], 200),
        ]);

        $this->post(route('checkout.process', 'individual'), [
            'first_name' => 'Jim', 'last_name' => 'Broke', 'email' => 'jim@example.com',
            'phone' => '5550000000', 'terms' => '1', 'privacy' => '1',
            'data_descriptor' => 'x', 'data_value' => 'y',
        ])->assertSessionHasErrors('card');

        $this->assertEquals('failed', Payment::first()->status);
    }

    /** @test — onboarding saves an encrypted-SSN client and marks the payment onboarded. */
    public function onboarding_saves_client_with_encrypted_ssn(): void
    {
        $payment = Payment::create([
            'name' => 'Jane Smith', 'email' => 'jane@example.com', 'phone' => '5551234567',
            'plan' => 'individual', 'amount' => 497, 'monthly_amount' => 99,
            'status' => 'paid', 'onboarding_token' => Str::random(40),
            'agreement_signed_at' => now(), // agreement already signed
        ]);

        $this->get(route('onboarding.show', $payment->onboarding_token))->assertOk();

        $this->post(route('onboarding.store', $payment->onboarding_token), [
            'first_name' => 'Jane', 'last_name' => 'Smith',
            'email' => 'jane@example.com', 'phone' => '5551234567',
            'street' => '123 Main St', 'city' => 'Detroit', 'state' => 'MI', 'zip' => '48201',
            'ssn' => '123-45-6789',
            'dob_month' => 5, 'dob_day' => 14, 'dob_year' => 1990,
        ])->assertRedirect(route('onboarding.next', $payment->onboarding_token));

        $client = Client::first();
        $this->assertNotNull($client);
        $this->assertEquals('123456789', $client->ssn);       // decrypts via cast
        $this->assertEquals('6789', $client->ssnLast4());
        $this->assertEquals('credit-repair', $client->service);

        // stored value is encrypted (not plaintext) in the DB
        $raw = \DB::table('clients')->where('id', $client->id)->value('ssn');
        $this->assertNotEquals('123456789', $raw);

        $this->assertNotNull($payment->fresh()->onboarded_at);

        // shows up in the paid credit repair clients dashboard view
        $this->withSession(['is_admin' => true]);
        $this->get(route('admin.clients'))->assertOk()->assertSee('Jane Smith');
    }

    /** @test — onboarding rejects an under-18 date of birth. */
    public function onboarding_rejects_under_18(): void
    {
        $payment = Payment::create([
            'name' => 'Kid', 'email' => 'kid@example.com', 'phone' => '5551234567',
            'plan' => 'individual', 'amount' => 497, 'status' => 'paid',
            'onboarding_token' => Str::random(40),
            'agreement_signed_at' => now(), // agreement already signed
        ]);

        $this->post(route('onboarding.store', $payment->onboarding_token), [
            'first_name' => 'Kid', 'last_name' => 'Young', 'email' => 'kid@example.com',
            'phone' => '5551234567', 'ssn' => '111-22-3333',
            'dob_month' => 1, 'dob_day' => 1, 'dob_year' => date('Y') - 10,
        ])->assertSessionHasErrors('dob_year');

        $this->assertDatabaseCount('clients', 0);
    }

    /** @test — checkout blocks submission without accepting the agreements. */
    public function checkout_requires_agreements(): void
    {
        Config::set('services.authorizenet.login_id', 'testlogin');
        Config::set('services.authorizenet.transaction_key', 'testkey');

        $this->post(route('checkout.process', 'individual'), [
            'first_name' => 'No', 'last_name' => 'Agree', 'email' => 'no@example.com',
            'phone' => '5551234567', 'data_descriptor' => 'x', 'data_value' => 'y',
        ])->assertSessionHasErrors(['terms', 'privacy']);

        $this->assertDatabaseCount('payments', 0);
    }

    /** @test — a lead can be opened in detail and have its status updated. */
    public function lead_detail_and_status_update(): void
    {
        $this->withSession(['is_admin' => true]);

        $lead = \App\Models\Lead::create([
            'type' => 'business-credit', 'name' => 'Detail Lead',
            'email' => 'detail@example.com', 'phone' => '5551112222', 'goal' => '1–3 years old',
        ]);

        $this->get(route('admin.leads.show', $lead))
            ->assertOk()
            ->assertSee('Detail Lead')
            ->assertSee('detail@example.com');

        $this->post(route('admin.leads.status', $lead), [
            'status' => 'won', 'admin_notes' => 'Closed the deal.',
        ])->assertRedirect();

        $this->assertEquals('won', $lead->fresh()->status);
        $this->assertEquals('Closed the deal.', $lead->fresh()->admin_notes);
    }

    /** @test — global search finds a lead by email. */
    public function global_search_finds_records(): void
    {
        $this->withSession(['is_admin' => true]);

        \App\Models\Lead::create([
            'type' => 'funding', 'name' => 'Searchable Person',
            'email' => 'findme@example.com', 'phone' => '5559998888',
        ]);

        $this->get(route('admin.search', ['q' => 'findme']))
            ->assertOk()
            ->assertSee('Searchable Person');
    }

    /** @test — qualifier routes a fully-qualified lead to the funding branch. */
    public function qualifier_routes_fundable_to_funding(): void
    {
        $this->post(route('qualify.submit'), [
            'first_name' => 'Fund', 'last_name' => 'Ready',
            'email' => 'fundable@example.com', 'phone' => '5551110000',
            'funding_goal' => 'business', 'amount' => '$25,000 – $75,000',
            'credit_score' => '680+', 'cards_3500' => 'yes', 'open_cards' => 'yes',
            'credit_age' => 'yes', 'clean' => 'yes', 'inquiries' => 'yes', 'utilization' => 'yes',
        ])->assertRedirect(route('qualify.result', 'funding'));

        $this->assertDatabaseHas('leads', [
            'email' => 'fundable@example.com', 'type' => 'funding', 'qualified' => 1,
        ]);
    }

    /** @test — failing any criterion routes the lead to the credit-repair branch. */
    public function qualifier_routes_unqualified_to_credit_repair(): void
    {
        $this->post(route('qualify.submit'), [
            'first_name' => 'Needs', 'last_name' => 'Repair',
            'email' => 'repair@example.com', 'phone' => '5551110001',
            'funding_goal' => 'personal', 'amount' => 'Not sure yet',
            'credit_score' => 'under-680', 'cards_3500' => 'no', 'open_cards' => 'yes',
            'credit_age' => 'yes', 'clean' => 'yes', 'inquiries' => 'yes', 'utilization' => 'yes',
        ])->assertRedirect(route('qualify.result', 'credit-repair'));

        $this->assertDatabaseHas('leads', [
            'email' => 'repair@example.com', 'type' => 'credit-repair', 'qualified' => 0,
        ]);
    }

    /** @test — Authorize.Net webhook flips a subscription to past_due. */
    public function webhook_updates_subscription_status(): void
    {
        Config::set('services.authorizenet.signature_key', 'WEBHOOK_TEST_KEY');

        $sub = Subscription::create([
            'name' => 'Jane Smith', 'email' => 'jane@example.com', 'plan' => 'individual',
            'amount' => 99, 'status' => 'active', 'authnet_subscription_id' => '9990001',
        ]);

        $body = json_encode([
            'eventType' => 'net.authorize.customer.subscription.suspended',
            'payload' => ['id' => '9990001'],
        ]);
        $sig = 'sha512=' . strtoupper(hash_hmac('sha512', $body, 'WEBHOOK_TEST_KEY'));

        // Wrong signature is rejected.
        $this->call('POST', '/webhooks/authorizenet', [], [], [], [
            'HTTP_X_ANET_SIGNATURE' => 'sha512=DEADBEEF', 'CONTENT_TYPE' => 'application/json',
        ], $body)->assertStatus(401);

        // Valid signature is accepted and updates the subscription.
        $this->call('POST', '/webhooks/authorizenet', [], [], [], [
            'HTTP_X_ANET_SIGNATURE' => $sig, 'CONTENT_TYPE' => 'application/json',
        ], $body)->assertOk();

        $sub->refresh();
        $this->assertEquals('past_due', $sub->status);
        $this->assertEquals(1, $sub->failed_payments);
    }
}
