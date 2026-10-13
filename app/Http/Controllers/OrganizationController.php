<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrganizationController extends Controller
{
    public function index(Request $request): Response
    {
        $organizations = $request->user()->organizations()->get();

        return Inertia::render('Organizations/Index', [
            'organizations' => $organizations->map(static fn ($organization) => [
                'id' => $organization->id,
                'name' => $organization->name,
                'status' => $organization->status,
            ])->values()->all(),
        ]);
    }
}
