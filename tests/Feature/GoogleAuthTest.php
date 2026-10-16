<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.google', [
            'client_id' => 'test-client-id',
            'client_secret' => 'test-client-secret',
            'redirect' => 'http://localhost/auth/google/callback',
        ]);
    }

    public function test_login_page_hides_google_button_when_not_configured(): void
    {
        Config::set('services.google', [
            'client_id' => '',
            'client_secret' => '',
            'redirect' => '',
        ]);

        $this->get(route('login'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Login')
                ->where('googleAuthEnabled', false));
    }

    public function test_register_page_hides_google_button_when_not_configured(): void
    {
        Config::set('services.google', [
            'client_id' => null,
            'client_secret' => null,
            'redirect' => null,
        ]);

        $this->get(route('register'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Auth/Register')
                ->where('googleAuthEnabled', false));
    }

    public function test_google_redirect_is_not_available_when_not_configured(): void
    {
        Config::set('services.google', [
            'client_id' => '',
            'client_secret' => '',
            'redirect' => '',
        ]);

        $this->get(route('auth.google.redirect'))->assertNotFound();
    }

    public function test_google_callback_creates_new_verified_customer(): void
    {
        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-new-1',
            email: 'new-google@example.com',
            verified: true,
        ));

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('main.organizations'));
        $this->assertDatabaseHas('users', [
            'email' => 'new-google@example.com',
            'google_id' => 'google-new-1',
            'user_type' => 'customer',
        ]);

        $user = User::query()->where('email', 'new-google@example.com')->first();
        $this->assertNotNull($user?->email_verified_at);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_callback_links_existing_user_by_email(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'google_id' => null,
            'avatar' => null,
        ]);

        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-link-1',
            email: 'existing@example.com',
            verified: true,
            avatar: 'https://lh3.googleusercontent.com/a/avatar.jpg',
        ));

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('main.organizations'));

        $user->refresh();
        $this->assertSame('google-link-1', $user->google_id);
        $this->assertSame('https://lh3.googleusercontent.com/a/avatar.jpg', $user->avatar);
        $this->assertAuthenticatedAs($user);
    }

    public function test_google_callback_rejects_unverified_email(): void
    {
        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-unverified',
            email: 'unverified@example.com',
            verified: false,
        ));

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertDatabaseMissing('users', [
            'email' => 'unverified@example.com',
        ]);
        $this->assertGuest();
    }

    public function test_google_callback_rejects_google_id_linked_to_different_email(): void
    {
        User::factory()->create([
            'email' => 'owner@example.com',
            'google_id' => 'google-conflict',
        ]);

        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-conflict',
            email: 'other@example.com',
            verified: true,
        ));

        $this->get(route('auth.google.callback'))
            ->assertRedirect(route('login'))
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_google_callback_does_not_follow_external_intended_redirect(): void
    {
        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-safe-redirect',
            email: 'safe@example.com',
            verified: true,
        ));

        $response = $this->withSession([
            'url.intended' => 'https://evil.example/phish',
        ])->get(route('auth.google.callback'));

        $response->assertRedirect(route('main.organizations'));
        $this->assertAuthenticated();
    }

    public function test_google_callback_allows_relative_intended_redirect(): void
    {
        Socialite::fake('google', $this->fakeGoogleUser(
            id: 'google-internal-redirect',
            email: 'internal@example.com',
            verified: true,
        ));

        $response = $this->withSession([
            'url.intended' => '/profile',
        ])->get(route('auth.google.callback'));

        $response->assertRedirect('/profile');
        $this->assertAuthenticated();
    }

    private function fakeGoogleUser(
        string $id,
        string $email,
        bool $verified,
        ?string $avatar = 'https://lh3.googleusercontent.com/a/default.jpg',
    ): SocialiteUser {
        return SocialiteUser::fake([
            'id' => $id,
            'sub' => $id,
            'email' => $email,
            'name' => 'Google User',
            'avatar' => $avatar,
            'email_verified' => $verified,
            'verified_email' => $verified,
        ]);
    }
}
