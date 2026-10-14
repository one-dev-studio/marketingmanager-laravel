<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

/**
 * Add security headers to all responses
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request
     */
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCspNonce();

        $response = $next($request);

        $nonce = Vite::cspNonce();
        $nonceDirective = $nonce !== null && $nonce !== '' ? "'nonce-{$nonce}'" : '';

        $scriptSrc = trim("'self' {$nonceDirective} https://cdn.jsdelivr.net https://unpkg.com");
        if (app()->environment('local')) {
            // Vite dev server / React refresh require eval in local only.
            $scriptSrc .= " 'unsafe-eval'";
        }

        $styleSrc = trim("'self' {$nonceDirective} 'unsafe-inline' https://fonts.googleapis.com");

        $csp = "default-src 'self'; script-src {$scriptSrc}; style-src {$styleSrc}; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self' https://api.pusher.com wss://ws.pusher.com".(app()->environment('local') ? ' ws://localhost:* http://localhost:*' : '')."; frame-ancestors 'none';";

        $response->headers->set('Content-Security-Policy', $csp);

        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Frame Options
        $response->headers->set('X-Frame-Options', 'DENY');

        // Content Type Options
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        // HSTS on HTTPS for deployed environments (staging + production)
        if ($request->secure() && app()->environment(['production', 'staging'])) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
