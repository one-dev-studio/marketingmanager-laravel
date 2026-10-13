<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ConfirmPasswordController extends Controller
{
    /**
     * Show the confirm password page (sensitive action gate).
     */
    public function show(): Response
    {
        return Inertia::render('Auth/ConfirmPassword');
    }

    /**
     * Validate password and store confirmation timestamp in session.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('main.organizations'));
    }
}
