<?php

namespace App\Http\Controllers\Agency;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Services\AgencyService;
use Illuminate\Http\Request;

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
    public function index(Request $request, Agency $agency)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
            'per_page' => $request->get('per_page', 15),
        ];

        $clients = $this->agencyService->getClientOrganizations($agency, $filters);

        return view('agency.clients.index', [
            'agency' => $agency,
            'clients' => $clients,
            'filters' => $filters,
        ]);
    }

    public function create(Agency $agency)
    {
        return view('agency.clients.create', compact('agency'));
    }

    public function store(Request $request, Agency $agency)
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
    public function show(Agency $agency, int $organizationId)
    {
        $client = $this->agencyService->getClientOrganization($agency, $organizationId);

        if (!$client) {
            abort(404, 'Client organization not found or not associated with this agency.');
        }

        return view('agency.clients.show', [
            'agency' => $agency,
            'client' => $client,
        ]);
    }
}


