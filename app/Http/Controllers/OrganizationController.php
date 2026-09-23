<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
        $organizations = $request->user()->organizations()->get();

        return view('organizations.index', [
            'title' => 'Organizations',
            'organizations' => $organizations,
        ]);
    }
}


