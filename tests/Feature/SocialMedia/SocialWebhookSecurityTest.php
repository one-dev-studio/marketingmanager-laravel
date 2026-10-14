<?php

declare(strict_types=1);

namespace Tests\Feature\SocialMedia;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SocialWebhookSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_facebook_webhook_rejects_invalid_signature_when_app_secret_configured(): void
    {
        Config::set('services.facebook.app_secret', 'meta_app_secret_test');

        $response = $this->postJson('/webhooks/facebook', [
            'entry' => [],
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_facebook_webhook_accepts_valid_meta_signature_when_app_secret_configured(): void
    {
        $secret = 'meta_app_secret_test';
        Config::set('services.facebook.app_secret', $secret);

        $payload = json_encode(['entry' => []], JSON_THROW_ON_ERROR);
        $signature = 'sha256='.hash_hmac('sha256', $payload, $secret);

        $response = $this->call(
            'POST',
            '/webhooks/facebook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Hub-Signature-256' => $signature,
            ],
            $payload,
        );

        $response->assertStatus(200);
        $this->assertNotSame(419, $response->status());
    }

    public function test_facebook_subscribe_handshake_skips_signature_when_verify_token_matches(): void
    {
        Config::set('services.facebook.app_secret', 'meta_app_secret_test');
        Config::set('services.facebook.webhook_verify_token', 'verify_token_test');

        $response = $this->post('/webhooks/facebook', [
            'hub_mode' => 'subscribe',
            'hub_verify_token' => 'verify_token_test',
            'hub_challenge' => 'challenge_123',
        ]);

        $response->assertStatus(200);
        $response->assertSee('challenge_123');
        $this->assertNotSame(419, $response->status());
    }

    public function test_instagram_webhook_rejects_invalid_signature_when_app_secret_configured(): void
    {
        Config::set('services.facebook.app_secret', 'meta_app_secret_test');

        $response = $this->postJson('/webhooks/instagram', [
            'entry' => [],
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_linkedin_webhook_rejects_missing_signature_when_client_secret_configured(): void
    {
        Config::set('services.linkedin.client_secret', 'linkedin_secret_test');

        $response = $this->postJson('/webhooks/linkedin', [
            'eventType' => 'POST_CREATED',
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_linkedin_webhook_accepts_valid_signature_when_client_secret_configured(): void
    {
        $secret = 'linkedin_secret_test';
        Config::set('services.linkedin.client_secret', $secret);

        $payload = json_encode(['eventType' => 'POST_CREATED'], JSON_THROW_ON_ERROR);
        $signature = base64_encode(hash_hmac('sha256', $payload, $secret, true));

        $response = $this->call(
            'POST',
            '/webhooks/linkedin',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-LI-Signature' => $signature,
            ],
            $payload,
        );

        $response->assertStatus(200);
    }

    public function test_twitter_webhook_rejects_invalid_signature_when_consumer_secret_configured(): void
    {
        Config::set('services.twitter.client_secret', 'twitter_consumer_secret');

        $response = $this->postJson('/webhooks/twitter', [
            'tweet_create_events' => [],
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_tiktok_webhook_rejects_invalid_signature_when_client_secret_configured(): void
    {
        Config::set('services.tiktok.client_secret', 'tiktok_client_secret');

        $response = $this->postJson('/webhooks/tiktok', [
            'event' => ['event_type' => 'video.publish.complete'],
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_pinterest_webhook_rejects_invalid_signature_when_signing_secret_configured(): void
    {
        Config::set('services.pinterest.webhook_signing_secret', 'pinterest_signing_secret');

        $response = $this->postJson('/webhooks/pinterest', [
            'data' => ['id' => 'pin_1'],
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->status());
    }

    public function test_pinterest_webhook_accepts_valid_signature_when_signing_secret_configured(): void
    {
        $secret = 'pinterest_signing_secret';
        Config::set('services.pinterest.webhook_signing_secret', $secret);

        $payload = json_encode(['data' => ['id' => 'pin_1']], JSON_THROW_ON_ERROR);
        $signature = hash_hmac('sha256', $payload, $secret);

        $response = $this->call(
            'POST',
            '/webhooks/pinterest',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Pinterest-Signature' => $signature,
            ],
            $payload,
        );

        $response->assertStatus(200);
    }
}
