<?php

declare(strict_types=1);

namespace App\Services\SocialMedia\Webhook;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Centralizes cryptographic verification for social platform webhooks.
 *
 * CoS: configure per-platform secrets via services.php env keys (see config/services.php).
 */
class SocialWebhookVerificationGate
{
    public function __construct(
        private readonly MetaHubSignatureVerifier $metaVerifier,
        private readonly LinkedInWebhookSignatureVerifier $linkedInVerifier,
        private readonly TwitterWebhookSignatureVerifier $twitterVerifier,
        private readonly TikTokWebhookSignatureVerifier $tikTokVerifier,
        private readonly PinterestWebhookSignatureVerifier $pinterestVerifier,
    ) {}

    public function assertFacebookWebhook(Request $request): void
    {
        if ($this->isMetaSubscribeHandshake($request)) {
            $expectedToken = (string) config('services.facebook.webhook_verify_token');
            if ($expectedToken !== '' && $request->get('hub_verify_token') === $expectedToken) {
                return;
            }
        }

        $this->assertMetaPlatformWebhook(
            $request,
            $this->resolveMetaAppSecret('facebook'),
            'Facebook',
        );
    }

    public function assertInstagramWebhook(Request $request): void
    {
        $this->assertMetaPlatformWebhook(
            $request,
            $this->resolveMetaAppSecret('instagram'),
            'Instagram',
        );
    }

    public function assertLinkedInWebhook(Request $request): void
    {
        $secret = (string) config('services.linkedin.client_secret');
        $this->assertSignedWebhook(
            $request,
            $secret,
            'LinkedIn',
            fn (string $payload, ?string $header, string $key): bool => $this->linkedInVerifier->verify($payload, $header, $key),
            'X-LI-Signature',
        );
    }

    public function assertTwitterWebhook(Request $request): void
    {
        $secret = (string) config('services.twitter.client_secret');
        $this->assertSignedWebhook(
            $request,
            $secret,
            'Twitter',
            fn (string $payload, ?string $header, string $key): bool => $this->twitterVerifier->verify($payload, $header, $key),
            'x-twitter-webhooks-signature',
        );
    }

    public function assertTikTokWebhook(Request $request): void
    {
        $secret = (string) config('services.tiktok.client_secret');
        $this->assertSignedWebhook(
            $request,
            $secret,
            'TikTok',
            fn (string $payload, ?string $header, string $key): bool => $this->tikTokVerifier->verify($payload, $header, $key),
            'Tiktok-Signature',
        );
    }

    public function assertPinterestWebhook(Request $request): void
    {
        $signingSecret = (string) (config('services.pinterest.webhook_signing_secret')
            ?: config('services.pinterest.client_secret'));

        $this->assertSignedWebhook(
            $request,
            $signingSecret,
            'Pinterest',
            fn (string $payload, ?string $header, string $key): bool => $this->pinterestVerifier->verify($payload, $header, $key),
            'X-Pinterest-Signature',
        );
    }

    private function assertMetaPlatformWebhook(Request $request, string $appSecret, string $label): void
    {
        $this->assertSignedWebhook(
            $request,
            $appSecret,
            $label,
            fn (string $payload, ?string $header, string $key): bool => $this->metaVerifier->verify($payload, $header, $key),
            'X-Hub-Signature-256',
            'X-Hub-Signature',
        );
    }

    /**
     * @param  callable(string, ?string, string): bool  $verify
     * @param  string  ...$headerNames  First non-empty request header value is used.
     */
    private function assertSignedWebhook(
        Request $request,
        string $secret,
        string $platformLabel,
        callable $verify,
        string ...$headerNames,
    ): void {
        if ($secret === '') {
            if (! app()->environment(['local', 'testing'])) {
                throw new AccessDeniedHttpException("{$platformLabel} webhook verification is not configured.");
            }

            return;
        }

        $payload = $request->getContent();
        $header = $this->firstHeader($request, ...$headerNames);

        if (! $verify($payload, $header, $secret)) {
            throw new BadRequestHttpException("Invalid {$platformLabel} webhook signature.");
        }
    }

    private function isMetaSubscribeHandshake(Request $request): bool
    {
        return $request->get('hub_mode') === 'subscribe';
    }

    private function resolveMetaAppSecret(string $platform): string
    {
        $platformSecret = (string) config("services.{$platform}.app_secret");
        if ($platformSecret !== '') {
            return $platformSecret;
        }

        $shared = (string) config('services.facebook.app_secret');
        if ($shared !== '') {
            return $shared;
        }

        $clientSecret = (string) config("services.{$platform}.client_secret");
        if ($clientSecret !== '') {
            return $clientSecret;
        }

        return (string) config('services.facebook.client_secret');
    }

    private function firstHeader(Request $request, string ...$headerNames): ?string
    {
        foreach ($headerNames as $name) {
            $value = $request->header($name);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return null;
    }
}
