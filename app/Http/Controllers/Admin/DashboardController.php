<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(Request $request): Response
    {
        $admin = $request->user('admin');

        return Inertia::render('Admin/Dashboard/Index', [
            'adminName' => $admin?->name ?? 'Admin',
            'stats' => [
                'organizations' => Organization::count(),
                'users' => User::count(),
                'campaigns' => Campaign::count(),
            ],
        ]);
    }
}
