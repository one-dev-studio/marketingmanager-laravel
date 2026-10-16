<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\Organization;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

/**
 * Webhook routes must not be blocked by CSRF middleware (external providers cannot send tokens).
 */
class WebhookCsrfExemptionTest extends TestCase
{
    use RefreshDatabase;

    public function test_stripe_webhook_unsigned_post_is_not_csrf_blocked(): void
    {
        Config::set('services.stripe.webhook_secret', 'whsec_test_secret');

        $organization = Organization::factory()->create();
        Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending',
        ]);

        $response = $this->post('/webhooks/stripe', [
            'type' => 'checkout.session.completed',
            'data' => ['object' => ['id' => 'cs_test']],
        ], [
            'Accept' => 'application/json',
        ]);

        $this->assertNotSame(419, $response->getStatusCode(), 'Webhook must not return CSRF 419');
        $response->assertStatus(400);
    }

    public function test_paypal_webhook_unsigned_post_is_not_csrf_blocked(): void
    {
        Config::set('services.paypal.client_id', 'client');
        Config::set('services.paypal.secret', 'secret');
        Config::set('services.paypal.webhook_id', 'WH-123');

        $response = $this->post('/webhooks/paypal', [
            'event_type' => 'PAYMENT.CAPTURE.COMPLETED',
        ], [
            'Accept' => 'application/json',
        ]);

        $this->assertNotSame(419, $response->getStatusCode(), 'Webhook must not return CSRF 419');
        $response->assertStatus(400);
    }

    public function test_facebook_webhook_post_is_not_csrf_blocked(): void
    {
        $response = $this->post('/webhooks/facebook', [
            'entry' => [],
        ], [
            'Accept' => 'application/json',
        ]);

        $this->assertNotSame(419, $response->getStatusCode(), 'Webhook must not return CSRF 419');
    }
}
