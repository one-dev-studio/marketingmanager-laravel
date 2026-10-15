<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restricts routes to platform super-admins (super_admin, super-admin, platform-admin).
 */
class EnsurePlatformSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('admin') ?? $request->user();

        if (! $user instanceof User || ! $user->isPlatformSuperAdmin()) {
            abort(403, 'You do not have permission to manage payment and webhook settings.');
        }

        return $next($request);
    }
}
