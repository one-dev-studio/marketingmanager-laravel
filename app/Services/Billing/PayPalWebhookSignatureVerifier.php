<?php

declare(strict_types=1);

namespace App\Services\Billing;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Verifies PayPal webhook transmissions via the notifications verify API.
 */
class PayPalWebhookSignatureVerifier
{
    /**
     * @param  array<string, mixed>  $eventPayload  Decoded webhook JSON body.
     */
    public function verify(Request $request, array $eventPayload, string $clientId, string $clientSecret, string $webhookId, string $mode = 'sandbox'): bool
    {
        $transmissionId = $request->header('Paypal-Transmission-Id');
        $transmissionTime = $request->header('Paypal-Transmission-Time');
        $transmissionSig = $request->header('Paypal-Transmission-Sig');
        $certUrl = $request->header('Paypal-Cert-Url');
        $authAlgo = $request->header('Paypal-Auth-Algo');

        if (! $transmissionId || ! $transmissionTime || ! $transmissionSig || ! $certUrl || ! $authAlgo) {
            return false;
        }

        $baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $accessToken = $this->fetchAccessToken($baseUrl, $clientId, $clientSecret);
        if ($accessToken === null) {
            Log::warning('PayPal webhook verification failed: could not obtain access token.');

            return false;
        }

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->post($baseUrl.'/v1/notifications/verify-webhook-signature', [
                'auth_algo' => $authAlgo,
                'cert_url' => $certUrl,
                'transmission_id' => $transmissionId,
                'transmission_sig' => $transmissionSig,
                'transmission_time' => $transmissionTime,
                'webhook_id' => $webhookId,
                'webhook_event' => $eventPayload,
            ]);

        if (! $response->successful()) {
            Log::warning('PayPal webhook verification API error', [
                'status' => $response->status(),
            ]);

            return false;
        }

        return ($response->json('verification_status') ?? '') === 'SUCCESS';
    }

    private function fetchAccessToken(string $baseUrl, string $clientId, string $clientSecret): ?string
    {
        $response = Http::asForm()
            ->withBasicAuth($clientId, $clientSecret)
            ->post($baseUrl.'/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            return null;
        }

        $token = $response->json('access_token');

        return is_string($token) && $token !== '' ? $token : null;
    }
}
