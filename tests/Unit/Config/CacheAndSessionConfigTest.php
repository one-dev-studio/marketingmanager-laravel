<?php

declare(strict_types=1);

namespace Tests\Unit\Config;

use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class CacheAndSessionConfigTest extends TestCase
{
    public function test_cache_default_uses_testing_cache_driver(): void
    {
        $this->assertSame('array', config('cache.default'));
    }

    public function test_cache_config_declares_store_with_driver_fallback(): void
    {
        $contents = file_get_contents(config_path('cache.php'));

        $this->assertStringContainsString("env('CACHE_STORE', env('CACHE_DRIVER'", $contents);
    }

    public function test_session_driver_is_env_driven(): void
    {
        $this->assertSame('array', config('session.driver'));
        $this->assertSame('sessions', config('session.table'));
    }

    public function test_cache_and_session_migrations_create_expected_tables(): void
    {
        $this->artisan('migrate', [
            '--path' => 'database/migrations/2026_10_13_080611_create_cache_table.php',
            '--realpath' => false,
        ])->assertSuccessful();

        $this->artisan('migrate', [
            '--path' => 'database/migrations/2026_10_13_080611_create_sessions_table.php',
            '--realpath' => false,
        ])->assertSuccessful();

        $this->assertTrue(Schema::hasTable('cache'));
        $this->assertTrue(Schema::hasTable('cache_locks'));
        $this->assertTrue(Schema::hasTable('sessions'));
    }
}
