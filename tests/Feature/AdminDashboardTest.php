<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_can_view_dashboard(): void
    {
        $user = User::where('email', 'admin@test.com')->firstOrFail();

        $response = $this->actingAs($user, 'admin')->get(route('admin.dashboard'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page->component('Admin/Dashboard/Index'));
    }

    public function test_guest_is_redirected_from_dashboard(): void
    {
        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('admin.login'));
    }
}
