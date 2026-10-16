<?php

declare(strict_types=1);

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Http\Resources\Agency\AgencyClientInertiaResource;
use App\Models\Agency;
use App\Models\Organization;
use App\Services\AgencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Agency Client Controller
 * Handles client organization management for agencies
 */
class ClientController extends Controller
{
    public function __construct(
        private AgencyService $agencyService
    ) {}

    /**
     * Display list of client organizations
     */
    public function index(Request $request, Agency $agency): Response
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'per_page' => $request->get('per_page', 15),
        ];

        $clients = $this->agencyService->getClientOrganizations($agency, $filters);

        $user = $request->user();
        $isAgencyAdmin = $user !== null
            && ($user->hasRole(['agency_admin', 'agency-admin', 'admin'], $agency) || $user->isAdmin());

        return Inertia::render('Agency/Clients/Index', [
            'agencyId' => (string) $agency->id,
            'clients' => $clients->through(
                fn (Organization $client) => AgencyClientInertiaResource::listItem($client, $agency)
            ),
            'filters' => [
                'search' => $filters['search'],
                'status' => $filters['status'],
            ],
            'can' => [
                'create' => $isAgencyAdmin,
            ],
        ]);
    }

    public function create(Agency $agency): Response
    {
        return Inertia::render('Agency/Clients/Create', [
            'agencyId' => (string) $agency->id,
        ]);
    }

    public function store(Request $request, Agency $agency): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);

        $organization = $this->agencyService->createClientOrganization($agency, $validated);

        return redirect()->route('agency.clients.show', [$agency, $organization->id])
            ->with('success', 'Client organization created.');
    }

    /**
     * Display details of a specific client organization
     */
    public function show(Request $request, Agency $agency, int $organizationId): Response
    {
        $client = $this->agencyService->getClientOrganization($agency, $organizationId);

        if (! $client) {
            abort(404, 'Client organization not found or not associated with this agency.');
        }

        return Inertia::render('Agency/Clients/Show', [
            'agencyId' => (string) $agency->id,
            'client' => AgencyClientInertiaResource::detail($client),
        ]);
    }
}
