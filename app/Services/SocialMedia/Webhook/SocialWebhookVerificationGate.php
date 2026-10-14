<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Ensures social platform webhooks are cryptographically verified when secrets are configured.
 */
class SocialWebhookVerificationGate
{
    public function __construct(
        private readonly MetaWebhookSignatureVerifier $metaVerifier,
        private readonly TwitterWebhookSignatureVerifier $twitterVerifier,
        private readonly HmacSha256HexWebhookSignatureVerifier $hmacHexVerifier,
        private readonly TikTokWebhookSignatureVerifier $tikTokVerifier,
    ) {}

    public function assertMetaWebhook(Request $request, string $configKey = 'facebook'): void
    {
        $secret = (string) config("services.{$configKey}.webhook_app_secret", '');
        if ($secret === '') {
            $secret = (string) config("services.{$configKey}.client_secret", '');
        }

        $this->assertSignature(
            $request,
            $secret,
            $request->header('X-Hub-Signature-256'),
            fn (string $payload, ?string $header, string $sec) => $this->metaVerifier->verify($payload, $header, $sec),
            "{$configKey} webhook",
        );
    }

    public function assertTwitterWebhook(Request $request): void
    {
        $secret = (string) config('services.twitter.consumer_secret', '');

        $this->assertSignature(
            $request,
            $secret,
            $request->header('X-Twitter-Webhooks-Signature'),
            fn (string $payload, ?string $header, string $sec) => $this->twitterVerifier->verify($payload, $header, $sec),
            'twitter webhook',
        );
    }

    public function assertLinkedInWebhook(Request $request): void
    {
        $secret = (string) config('services.linkedin.webhook_secret', '');

        $this->assertSignature(
            $request,
            $secret,
            $request->header('X-LI-Signature'),
            fn (string $payload, ?string $header, string $sec) => $this->hmacHexVerifier->verify($payload, $header, $sec),
            'linkedin webhook',
        );
    }

    public function assertTikTokWebhook(Request $request): void
    {
        $secret = (string) config('services.tiktok.client_secret', '');

        $this->assertSignature(
            $request,
            $secret,
            $request->header('TikTok-Signature') ?? $request->header('Tiktok-Signature'),
            fn (string $payload, ?string $header, string $sec) => $this->tikTokVerifier->verify($payload, $header, $sec),
            'tiktok webhook',
        );
    }

    public function assertPinterestWebhook(Request $request): void
    {
        $secret = (string) config('services.pinterest.webhook_secret', '');

        $this->assertSignature(
            $request,
            $secret,
            $request->header('X-Pinterest-Signature'),
            fn (string $payload, ?string $header, string $sec) => $this->hmacHexVerifier->verify($payload, $header, $sec),
            'pinterest webhook',
        );
    }

    /**
     * @param  callable(string, ?string, string): bool  $verify
     */
    private function assertSignature(
        Request $request,
        string $secret,
        ?string $signatureHeader,
        callable $verify,
        string $label,
    ): void {
        $payload = $request->getContent();

        if ($secret !== '') {
            if (! $verify($payload, $signatureHeader, $secret)) {
                throw new BadRequestHttpException("Invalid {$label} signature.");
            }

            return;
        }

        if (! app()->environment(['local', 'testing'])) {
            throw new AccessDeniedHttpException("{$label} verification is not configured.");
        }
    }
}
