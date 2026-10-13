<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TwoFactorInertiaTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_setup_renders_inertia_component(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'two_factor_enabled' => false,
        ]);

        $this->actingAs($user)
            ->get(route('two-factor.setup'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/TwoFactor/Setup')
                ->has('secret')
                ->has('qrCodeUrl'));
    }

    public function test_two_factor_setup_redirects_when_already_enabled(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
            'two_factor_enabled' => true,
        ]);

        $this->actingAs($user)
            ->get(route('two-factor.setup'))
            ->assertRedirect(route('profile.security'));
    }

    public function test_two_factor_challenge_renders_inertia_component(): void
    {
        $this->get(route('two-factor.challenge'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/TwoFactor/Challenge'));
    }
}
