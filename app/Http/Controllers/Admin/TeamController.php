<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function index(): Response
    {
        $admins = User::query()
            ->where(function ($q) {
                $q->where('user_type', 'admin')
                    ->orWhereHas('roles', fn ($r) => $r->whereIn('name', ['super-admin', 'platform-admin', 'admin']));
            })
            ->orderBy('name')
            ->get();

        return Inertia::render('Admin/Team/Index', [
            'admins' => $admins,
        ]);
    }
}
