<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\User;
use Illuminate\Http\Request;

/**
 * Shared header context for admin portal Inertia pages.
 */
class AdminShellContextBuilder
{
    /**
     * @return array<string, mixed>|null
     */
    public function buildForRequest(Request $request): ?array
    {
        $user = $request->user('admin') ?? $request->user();
        if ($user === null) {
            return null;
        }

        if (! $request->routeIs('admin.*') || $request->routeIs('admin.login')) {
            return null;
        }

        return $this->build($user);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user): array
    {
        return [
            'returnToAppHref' => route('main.organizations'),
            'profileHref' => route('profile.show'),
            'userName' => $user->name,
            'userEmail' => $user->email,
        ];
    }
}
