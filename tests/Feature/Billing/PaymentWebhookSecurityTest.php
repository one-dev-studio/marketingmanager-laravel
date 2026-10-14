<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PaymentWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_webhook_rejects_invalid_signature_when_secret_configured(): void
    {
        Config::set('services.stripe.webhook_secret', 'whsec_test_secret');

        $organization = Organization::factory()->create();
        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending',
        ]);

        $response = $this->postJson('/webhooks/stripe', [
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test',
                    'metadata' => [
                        'invoice_number' => $invoice->invoice_number,
                    ],
                ],
            ],
        ]);

        $response->assertStatus(400);
        $this->assertNotEquals(419, $response->getStatusCode());
        $this->assertSame('pending', $invoice->fresh()->status);
    }

    public function test_stripe_webhook_accepts_valid_signature_when_secret_configured(): void
    {
        $secret = 'whsec_test_secret';
        Config::set('services.stripe.webhook_secret', $secret);

        $organization = Organization::factory()->create();
        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending',
        ]);

        $payload = json_encode([
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test',
                    'metadata' => [
                        'invoice_number' => $invoice->invoice_number,
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR);

        $timestamp = time();
        $signature = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);
        $header = 't='.$timestamp.',v1='.$signature;

        $response = $this->call(
            'POST',
            '/webhooks/stripe',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_Stripe-Signature' => $header,
            ],
            $payload,
        );

        $response->assertStatus(200);
        $this->assertNotEquals(419, $response->getStatusCode());
        $this->assertSame('paid', $invoice->fresh()->status);
    }

    public function test_paypal_webhook_rejects_when_verification_configured_but_headers_missing(): void
    {
        Config::set('services.paypal.client_id', 'client');
        Config::set('services.paypal.secret', 'secret');
        Config::set('services.paypal.webhook_id', 'WH-123');

        $response = $this->postJson('/webhooks/paypal', [
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
            'resource' => ['id' => 'pay-1'],
        ]);

        $response->assertStatus(400);
        $this->assertNotEquals(419, $response->getStatusCode());
    }
}
