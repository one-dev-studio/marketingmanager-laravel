<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use Tests\TestCase;

class AgencyRemainingInertiaTest extends TestCase
{
    private function agencyContext(): array
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        return [$user, $agency];
    }

    public function test_agency_team_renders_inertia(): void
    {
        [$user, $agency] = $this->agencyContext();

        $this->actingAs($user)
            ->get(route('agency.team.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Team/Index')
                ->has('teamMembers')
                ->has('agencySidebar'));
    }

    public function test_agency_reports_renders_inertia(): void
    {
        [$user, $agency] = $this->agencyContext();

        $this->actingAs($user)
            ->get(route('agency.reports.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Reports/Index')
                ->has('clients')
                ->has('reports')
                ->has('agencySidebar'));
    }

    public function test_agency_settings_renders_inertia(): void
    {
        [$user, $agency] = $this->agencyContext();

        $this->actingAs($user)
            ->get(route('agency.settings.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Settings/Index')
                ->has('branding')
                ->has('defaults')
                ->has('agencySidebar'));
    }

    public function test_agency_client_create_renders_inertia(): void
    {
        [$user, $agency] = $this->agencyContext();

        $this->actingAs($user)
            ->get(route('agency.clients.create', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Clients/Create')
                ->has('agencySidebar'));
    }
}
