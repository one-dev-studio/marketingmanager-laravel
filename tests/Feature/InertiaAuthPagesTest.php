<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class InertiaAuthPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_renders_inertia_component(): void
    {
        $this->get(route('login'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Login')
                ->where('canResetPassword', true));
    }

    public function test_register_page_renders_inertia_component(): void
    {
        $this->get(route('register'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/Register'));
    }

    public function test_forgot_password_page_renders_inertia_component(): void
    {
        $this->get(route('password.request'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/ForgotPassword'));
    }

    public function test_reset_password_page_renders_inertia_component(): void
    {
        $this->get(route('password.reset', ['token' => 'test-token', 'email' => 'user@example.com']))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/ResetPassword')
                ->where('token', 'test-token')
                ->where('email', 'user@example.com'));
    }

    public function test_verify_email_notice_renders_inertia_component(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/VerifyEmail'));
    }

    public function test_confirm_password_page_renders_inertia_component(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('password.confirm'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Auth/ConfirmPassword'));
    }

    public function test_profile_show_renders_inertia_component(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Profile/Show')
                ->has('user', fn ($userPage) => $userPage
                    ->where('email', $user->email)
                    ->etc())
                ->has('sessions'));
    }

    public function test_profile_security_renders_inertia_component(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('profile.security'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Profile/Security')
                ->where('twoFactorEnabled', false)
                ->has('sessions'));
    }

    public function test_user_can_confirm_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $this->actingAs($user)
            ->post('/user/confirm-password', [
                'password' => 'password123',
            ])
            ->assertRedirect(route('main.organizations'));

        $this->assertNotNull(session('auth.password_confirmed_at'));
    }
}
