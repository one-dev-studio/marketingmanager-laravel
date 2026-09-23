<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class TeamController extends Controller
{
    public function index()
    {
        $admins = User::query()
            ->where(function ($q) {
                $q->where('user_type', 'admin')
                    ->orWhereHas('roles', fn ($r) => $r->whereIn('name', ['super-admin', 'platform-admin', 'admin']));
            })
            ->orderBy('name')
            ->get();

        return view('admin.team.index', compact('admins'));
    }
}
