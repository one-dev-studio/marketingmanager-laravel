<?php

declare(strict_types=1);

namespace Tests\Feature\SocialMedia;

use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SocialWebhookSecurityTest extends TestCase
{
    public function test_facebook_webhook_rejects_invalid_signature_when_secret_configured(): void
    {
        Config::set('services.facebook.webhook_app_secret', 'meta_test_secret');

        $payload = json_encode(['entry' => []], JSON_THROW_ON_ERROR);

        $response = $this->call(
            'POST',
            '/webhooks/facebook',
            [],
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_X-Hub-Signature-256' => 'sha256=invalid',
            ],
            $payload,
        );

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->getStatusCode());
    }

    public function test_facebook_webhook_accepts_valid_meta_signature_when_secret_configured(): void
    {
        $secret = 'meta_test_secret';
        Config::set('services.facebook.webhook_app_secret', $secret);

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
    }

    public function test_twitter_webhook_rejects_invalid_signature_when_secret_configured(): void
    {
        Config::set('services.twitter.consumer_secret', 'twitter_consumer_secret');

        $response = $this->postJson('/webhooks/twitter', [
            'tweet_create_events' => [],
        ], [
            'X-Twitter-Webhooks-Signature' => 'not-valid',
        ]);

        $response->assertStatus(400);
        $this->assertNotSame(419, $response->getStatusCode());
    }
}
