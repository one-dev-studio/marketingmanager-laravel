<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function testAdminCanViewDashboard(): void
    {
        $user = User::where('email', 'admin@test.com')->firstOrFail();

        $response = $this->actingAs($user, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertSee('Admin Dashboard');
    }

    public function testGuestIsRedirectedFromDashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }
}
