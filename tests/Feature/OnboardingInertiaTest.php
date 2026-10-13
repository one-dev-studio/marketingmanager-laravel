<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class OnboardingInertiaTest extends TestCase
{
    public function test_onboarding_page_renders_inertia_wizard(): void
    {
        $user = User::where('email', 'demo@test.com')->first();

        $this->actingAs($user)
            ->get(route('main.onboarding'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Onboarding/Index'));
    }
}
