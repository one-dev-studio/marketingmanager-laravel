<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Organizations/Index', [
            'organizations' => Organization::paginate(),
        ]);
    }

    public function show(Organization $organization): Response
    {
        return Inertia::render('Admin/Organizations/Show', [
            'organization' => [
                'id' => $organization->id,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'status' => $organization->status,
                'created_at' => $organization->created_at?->toDateTimeString(),
            ],
        ]);
    }
}
