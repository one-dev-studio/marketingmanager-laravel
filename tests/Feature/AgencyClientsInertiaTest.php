<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Agency;
use App\Models\User;
use Tests\TestCase;

class AgencyClientsInertiaTest extends TestCase
{
    public function test_agency_clients_index_renders_inertia_with_agency_shell(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.clients.index', ['agency' => $agency]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Clients/Index')
                ->has('agencySidebar')
                ->where('agencySidebar.agencyId', (string) $agency->id)
                ->has('agencySidebar.items')
                ->has('agencyShell')
                ->where('agencyShell.agency.id', (string) $agency->id)
                ->has('clients.data'));
    }

    public function test_agency_client_show_renders_inertia(): void
    {
        $user = User::where('email', 'agency@test.com')->firstOrFail();
        $agency = Agency::where('name', 'Demo Agency')->firstOrFail();
        $client = $agency->clientOrganizations()->firstOrFail();

        $this->actingAs($user)
            ->get(route('agency.clients.show', ['agency' => $agency, 'organizationId' => $client->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Agency/Clients/Show')
                ->where('client.name', $client->name)
                ->has('agencySidebar')
                ->has('agencyShell'));
    }
}
