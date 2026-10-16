<?php

declare(strict_types=1);

namespace App\Services\Broadcasting;

/**
 * Resolves Laravel Echo / Pusher wiring from environment without requiring live keys in CI.
 */
final class BroadcastingConfiguration
{
    /**
     * Driver requested via BROADCAST_DRIVER or BROADCAST_CONNECTION.
     */
    public function requestedDriver(): string
    {
        $driver = env('BROADCAST_DRIVER') ?? env('BROADCAST_CONNECTION');

        if (! is_string($driver) || $driver === '') {
            return 'log';
        }

        return $driver;
    }

    /**
     * Whether server-side Pusher credentials are present for the pusher connection.
     */
    public function isPusherServerConfigured(): bool
    {
        return $this->filledEnv('PUSHER_APP_KEY')
            && $this->filledEnv('PUSHER_APP_SECRET')
            && $this->filledEnv('PUSHER_APP_ID');
    }

    /**
     * Whether the Vite client can initialize Echo (build-time VITE_* or mirrored PUSHER_APP_KEY).
     */
    public function isPusherClientConfigured(): bool
    {
        return $this->filledEnv('VITE_PUSHER_APP_KEY') || $this->filledEnv('PUSHER_APP_KEY');
    }

    /**
     * Broadcast driver used at runtime; falls back to log when pusher is incomplete.
     */
    public function effectiveDriver(): string
    {
        $requested = $this->requestedDriver();

        if ($requested === 'pusher' && ! $this->isPusherServerConfigured()) {
            return 'log';
        }

        return $requested;
    }

    /**
     * Public client configuration shared with Inertia (no secrets).
     *
     * @return array{enabled: bool, key: string|null, cluster: string, host: string|null, port: int|null, scheme: string}
     */
    public function clientConfig(): array
    {
        $key = $this->envString('VITE_PUSHER_APP_KEY') ?? $this->envString('PUSHER_APP_KEY');
        $cluster = $this->envString('VITE_PUSHER_APP_CLUSTER')
            ?? $this->envString('PUSHER_APP_CLUSTER')
            ?? 'mt1';

        $host = $this->envString('VITE_PUSHER_HOST') ?? $this->envString('PUSHER_HOST');
        $portRaw = $this->envString('VITE_PUSHER_PORT') ?? $this->envString('PUSHER_PORT');
        $port = $portRaw !== null && $portRaw !== '' ? (int) $portRaw : null;

        $scheme = $this->envString('VITE_PUSHER_SCHEME')
            ?? $this->envString('PUSHER_SCHEME')
            ?? 'https';

        return [
            'enabled' => $key !== null && $key !== '',
            'key' => $key,
            'cluster' => $cluster,
            'host' => $host,
            'port' => $port,
            'scheme' => $scheme,
        ];
    }

    private function filledEnv(string $key): bool
    {
        $value = env($key);

        return is_string($value) && $value !== '';
    }

    private function envString(string $key): ?string
    {
        $value = env($key);

        if (! is_string($value) || $value === '') {
            return null;
        }

        return $value;
    }
}
