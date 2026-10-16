<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Http\RedirectResponse;

class PostAuthenticationRedirect
{
    /**
     * Resolve the post-login redirect for the given user.
     */
    public function redirectFor(User $user): RedirectResponse
    {
        return match ($user->user_type) {
            'admin' => redirect()->route('admin.dashboard'),
            'agency' => $user->primaryAgency()
                ? redirect()->route('agency.dashboard', $user->primaryAgency())
                : redirect()->route('main.organizations'),
            'customer' => redirect()->route('main.organizations'),
            default => redirect()->route('main.organizations'),
        };
    }
}
