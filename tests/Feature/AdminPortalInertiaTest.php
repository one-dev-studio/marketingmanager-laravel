<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class AdminPortalInertiaTest extends TestCase
{
    private function adminUser(): User
    {
        return User::where('email', 'admin@test.com')->firstOrFail();
    }

    public function test_admin_dashboard_renders_inertia_with_admin_shell(): void
    {
        $this->actingAs($this->adminUser(), 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Dashboard/Index')
                ->has('stats')
                ->has('adminSidebar')
                ->has('adminShell'));
    }

    public function test_admin_users_index_renders_inertia(): void
    {
        $this->actingAs($this->adminUser(), 'admin')
            ->get(route('admin.users.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/Users/Index')->has('users'));
    }

    public function test_admin_organizations_index_renders_inertia(): void
    {
        $this->actingAs($this->adminUser(), 'admin')
            ->get(route('admin.organizations.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/Organizations/Index')->has('organizations'));
    }

    public function test_admin_settings_index_renders_inertia(): void
    {
        $this->actingAs($this->adminUser(), 'admin')
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Admin/Settings/Index')->has('maintenanceMode'));
    }
}
