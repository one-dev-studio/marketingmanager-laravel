<?php

namespace Tests;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    public function createApplication(): Application
    {
        $this->forceInMemorySqliteForTests();

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(DatabaseSeeder::class);
        $this->withoutVite();
        Notification::fake();
    }

    /**
     * Create a user with organization membership and Spatie permissions for feature tests.
     *
     * @return array{0: User, 1: Organization}
     */
    protected function createOrganizationAdmin(
        ?User $user = null,
        ?Organization $organization = null,
        string $tenantRoleName = 'admin',
        string $spatieRoleName = 'admin',
    ): array {
        $user = $user ?? User::factory()->create();
        $organization = $organization ?? Organization::factory()->create();

        $tenantRole = Role::query()->where('name', $tenantRoleName)->firstOrFail();

        $user->organizations()->syncWithoutDetaching([
            $organization->id => ['role_id' => $tenantRole->id],
        ]);

        if (! $user->hasRole($spatieRoleName)) {
            $user->assignRole($spatieRoleName);
        }

        return [$user, $organization];
    }

    protected function actingAsOrganizationAdmin(
        ?User $user = null,
        ?Organization $organization = null,
        string $tenantRoleName = 'admin',
        string $spatieRoleName = 'admin',
    ): array {
        [$user, $organization] = $this->createOrganizationAdmin(
            $user,
            $organization,
            $tenantRoleName,
            $spatieRoleName,
        );

        $this->actingAs($user);

        return [$user, $organization];
    }

    private function forceInMemorySqliteForTests(): void
    {
        $overrides = [
            'DB_CONNECTION' => 'sqlite',
            'DB_DATABASE' => ':memory:',
            'DB_HOST' => '',
            'DB_PORT' => '',
            'DB_USERNAME' => '',
            'DB_PASSWORD' => '',
        ];

        foreach ($overrides as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
}
