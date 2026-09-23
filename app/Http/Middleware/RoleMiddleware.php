<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unauthenticated.');
        }

        if ($user->hasAnyRole($roles)) {
            return $next($request);
        }

        foreach ($roles as $role) {
            if ($role === 'agency' && $user->isAgency()) {
                return $next($request);
            }
            if ($role === 'admin' && $user->isAdmin()) {
                return $next($request);
            }
            if ($role === 'customer' && $user->isCustomer()) {
                return $next($request);
            }
        }

        abort(403, 'You do not have the required role to access this resource.');
    }
}


