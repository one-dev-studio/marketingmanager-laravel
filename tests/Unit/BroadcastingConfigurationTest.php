<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\Broadcasting\BroadcastingConfiguration;
use Illuminate\Support\Facades\Broadcast;
use Tests\TestCase;

class BroadcastingConfigurationTest extends TestCase
{
    protected function tearDown(): void
    {
        $this->clearEnv([
            'BROADCAST_DRIVER',
            'BROADCAST_CONNECTION',
            'PUSHER_APP_ID',
            'PUSHER_APP_KEY',
            'PUSHER_APP_SECRET',
            'VITE_PUSHER_APP_KEY',
        ]);

        parent::tearDown();
    }

    public function test_effective_driver_falls_back_to_log_when_pusher_keys_missing(): void
    {
        $this->setEnv('BROADCAST_DRIVER', 'pusher');

        $config = new BroadcastingConfiguration;

        $this->assertSame('log', $config->effectiveDriver());
        $this->assertFalse($config->isPusherServerConfigured());
    }

    public function test_effective_driver_uses_pusher_when_credentials_present(): void
    {
        $this->setEnv('BROADCAST_DRIVER', 'pusher');
        $this->setEnv('PUSHER_APP_ID', 'app-id');
        $this->setEnv('PUSHER_APP_KEY', 'app-key');
        $this->setEnv('PUSHER_APP_SECRET', 'app-secret');

        $config = new BroadcastingConfiguration;

        $this->assertSame('pusher', $config->effectiveDriver());
        $this->assertTrue($config->isPusherServerConfigured());
    }

    public function test_client_config_reflects_vite_key_presence(): void
    {
        $this->setEnv('VITE_PUSHER_APP_KEY', 'client-key');
        $this->setEnv('VITE_PUSHER_APP_CLUSTER', 'eu');

        $config = new BroadcastingConfiguration;
        $client = $config->clientConfig();

        $this->assertTrue($client['enabled']);
        $this->assertSame('client-key', $client['key']);
        $this->assertSame('eu', $client['cluster']);
    }

    public function test_broadcasting_config_uses_log_driver_when_pusher_incomplete(): void
    {
        $this->setEnv('BROADCAST_DRIVER', 'pusher');
        $this->refreshApplication();

        $this->assertSame('log', config('broadcasting.default'));
        Broadcast::channel('test.bootstrap.channel', fn () => true);
        $this->assertTrue(true);
    }

    /**
     * @param  list<string>  $keys
     */
    private function clearEnv(array $keys): void
    {
        foreach ($keys as $key) {
            putenv($key);
            unset($_ENV[$key], $_SERVER[$key]);
        }
    }

    private function setEnv(string $key, string $value): void
    {
        putenv("{$key}={$value}");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}
