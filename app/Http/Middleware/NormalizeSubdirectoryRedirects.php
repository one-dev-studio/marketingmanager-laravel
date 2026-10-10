<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Support\SubdirectoryRequestUriNormalizer;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Prevents doubled subdirectory segments in redirect Location headers.
 */
class NormalizeSubdirectoryRedirects
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! $response instanceof RedirectResponse) {
            return $response;
        }

        $location = $response->headers->get('Location');
        $normalized = SubdirectoryRequestUriNormalizer::normalizeRedirectLocation(
            is_string($location) ? $location : null,
            config('app.url')
        );

        if ($normalized !== null && $normalized !== $location) {
            $response->headers->set('Location', $normalized);
        }

        return $response;
    }
}
