<?php

declare(strict_types=1);

namespace Tests\Feature\Admin;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\PaymentWebhookSetting;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class PaymentWebhookSettingsTest extends TestCase
{
    use RefreshDatabase;

    private function adminUser(): User
    {
        return User::where('email', 'admin@test.com')->firstOrFail();
    }

    private function superAdminUser(): User
    {
        $user = $this->adminUser();
        if (! $user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }

        return $user;
    }

    public function test_non_super_admin_cannot_access_payment_settings(): void
    {
        $this->actingAs($this->adminUser(), 'admin')
            ->get(route('admin.settings.payments.index'))
            ->assertForbidden();
    }

    public function test_super_admin_sees_masked_fields_without_secrets(): void
    {
        PaymentWebhookSetting::query()->create([
            'stripe_webhook_secret' => 'whsec_full_secret_value_1234',
        ]);

        $response = $this->actingAs($this->superAdminUser(), 'admin')
            ->get(route('admin.settings.payments.index'));

        $response->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Settings/Payments')
                ->where('fields.stripe_webhook_secret.is_set', true)
                ->where('fields.stripe_webhook_secret.last_four', '1234')
                ->missing('fields.stripe_webhook_secret.value'));

        $content = $response->getContent();
        $this->assertIsString($content);
        $this->assertStringNotContainsString('whsec_full_secret_value_1234', $content);
    }

    public function test_update_writes_audit_log_without_secret_value(): void
    {
        $superAdmin = $this->superAdminUser();

        $this->actingAs($superAdmin, 'admin')
            ->put(route('admin.settings.payments.update'), [
                'stripe_webhook_secret' => 'whsec_audit_test_secret',
            ])
            ->assertRedirect(route('admin.settings.payments.index'));

        $log = SystemLog::query()
            ->where('message', 'Payment webhook setting updated')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertSame('stripe_webhook_secret', $log->context['field_key'] ?? null);
        $this->assertArrayNotHasKey('value', $log->context ?? []);
        $encoded = json_encode($log->context, JSON_THROW_ON_ERROR);
        $this->assertStringNotContainsString('whsec_audit_test_secret', $encoded);
    }

    public function test_stripe_webhook_uses_database_secret_before_config(): void
    {
        Config::set('services.stripe.webhook_secret', 'whsec_from_config_only');

        PaymentWebhookSetting::query()->create([
            'stripe_webhook_secret' => 'whsec_from_database',
        ]);

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

        $secret = 'whsec_from_database';
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
        $this->assertSame('paid', $invoice->fresh()->status);
    }
}
