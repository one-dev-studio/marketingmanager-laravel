<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use Tests\TestCase;

class AgencyTasksCalendarBillingInertiaTest extends TestCase
{
    public function test_agency_tasks_renders_inertia_with_shell(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.tasks', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Tasks/Index')
                ->has('agencySidebar')
                ->has('tasks')
                ->has('clients'));
    }

    public function test_agency_calendar_renders_inertia(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.calendar', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Calendar/Index')
                ->has('scheduledPosts')
                ->has('campaigns'));
    }

    public function test_agency_billing_renders_inertia(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.billing.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Billing/Index')
                ->has('summary')
                ->has('invoices.data'));
    }
}
