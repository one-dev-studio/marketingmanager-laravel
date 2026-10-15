<?php

declare(strict_types=1);

namespace Tests\Feature\Billing;

use App\Models\Invoice;
use App\Models\Organization;
use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StripePaymentIntentWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_payment_intent_succeeded_marks_invoice_paid_using_invoice_id_metadata(): void
    {
        $organization = Organization::factory()->create();
        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'pending',
        ]);

        $this->postJson('/webhooks/stripe', [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_test_success',
                    'metadata' => [
                        'invoice_id' => $invoice->id,
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertSame('paid', $invoice->fresh()->status);
        $this->assertDatabaseHas('payments', [
            'invoice_id' => $invoice->id,
            'transaction_id' => 'pi_test_success',
            'status' => 'completed',
        ]);
    }

    public function test_payment_intent_succeeded_is_idempotent_for_duplicate_transaction_id(): void
    {
        $organization = Organization::factory()->create();
        $invoice = Invoice::factory()->create([
            'organization_id' => $organization->id,
            'status' => 'paid',
        ]);

        Payment::factory()->create([
            'organization_id' => $organization->id,
            'invoice_id' => $invoice->id,
            'transaction_id' => 'pi_duplicate',
            'status' => 'completed',
        ]);

        $this->postJson('/webhooks/stripe', [
            'type' => 'payment_intent.succeeded',
            'data' => [
                'object' => [
                    'id' => 'pi_duplicate',
                    'metadata' => [
                        'invoice_id' => $invoice->id,
                    ],
                ],
            ],
        ])->assertOk();

        $this->assertSame(1, Payment::where('transaction_id', 'pi_duplicate')->count());
    }
}
