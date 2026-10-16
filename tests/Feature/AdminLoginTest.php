<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_page_renders_inertia_component(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/Auth/Login'));
    }

    public function test_admin_can_login(): void
    {
        $user = User::where('email', 'admin@test.com')->firstOrFail();

        $response = $this->post('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user, 'admin');
    }
}
