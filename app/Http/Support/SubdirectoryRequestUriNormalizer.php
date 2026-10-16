<?php

declare(strict_types=1);

namespace App\Http\Support;

/**
 * Normalizes request paths when the application is served from a URL subdirectory.
 */
final class SubdirectoryRequestUriNormalizer
{
    /**
     * Strip the subdirectory prefix from $_SERVER URI variables before Laravel boots.
     */
    public static function applyToServerGlobals(string $pathPrefix): void
    {
        $prefix = self::normalizePrefix($pathPrefix);
        if ($prefix === null) {
            return;
        }

        foreach (['REQUEST_URI', 'REDIRECT_URL'] as $serverKey) {
            if (! isset($_SERVER[$serverKey]) || ! is_string($_SERVER[$serverKey])) {
                continue;
            }

            $_SERVER[$serverKey] = self::stripPrefixFromRequestUri($_SERVER[$serverKey], $prefix);
        }
    }

    /**
     * Derive the path prefix (e.g. "/marketingmanager") from APP_URL.
     */
    public static function pathPrefixFromAppUrl(?string $appUrl): ?string
    {
        if ($appUrl === null || $appUrl === '') {
            return null;
        }

        $path = parse_url($appUrl, PHP_URL_PATH);
        if (! is_string($path) || $path === '' || $path === '/') {
            return null;
        }

        return rtrim($path, '/');
    }

    /**
     * Root-relative path for a file under /public (same host as the page; supports subdirectory APP_URL).
     */
    public static function rootRelativePublicPath(string $relativePath, ?string $appUrl = null): string
    {
        $relativePath = ltrim($relativePath, '/');
        $prefix = self::pathPrefixFromAppUrl($appUrl);

        return ($prefix ?? '').'/'.$relativePath;
    }

    /**
     * Remove one leading subdirectory prefix and collapse accidental duplicates.
     */
    public static function stripPrefixFromRequestUri(string $requestUri, string $pathPrefix): string
    {
        $prefix = self::normalizePrefix($pathPrefix);
        if ($prefix === null) {
            return $requestUri;
        }

        $path = parse_url($requestUri, PHP_URL_PATH);
        $query = parse_url($requestUri, PHP_URL_QUERY);
        $fragment = parse_url($requestUri, PHP_URL_FRAGMENT);

        if (! is_string($path)) {
            return $requestUri;
        }

        $path = self::collapseDuplicatePrefix($path, $prefix);
        if (str_starts_with($path, $prefix)) {
            $path = substr($path, strlen($prefix)) ?: '/';
        }

        $normalized = $path;
        if (is_string($query) && $query !== '') {
            $normalized .= '?'.$query;
        }
        if (is_string($fragment) && $fragment !== '') {
            $normalized .= '#'.$fragment;
        }

        return $normalized;
    }

    /**
     * Collapse repeated subdirectory segments (e.g. "/marketingmanager/marketingmanager/login").
     */
    public static function collapseDuplicatePrefix(string $path, string $pathPrefix): string
    {
        $prefix = self::normalizePrefix($pathPrefix);
        if ($prefix === null) {
            return $path;
        }

        $doublePrefix = $prefix.$prefix;
        while (str_starts_with($path, $doublePrefix)) {
            $path = substr($path, strlen($prefix));
        }

        return $path === '' ? '/' : $path;
    }

    /**
     * Normalize redirect Location headers to avoid doubled subdirectory paths.
     */
    public static function normalizeRedirectLocation(?string $location, ?string $appUrl): ?string
    {
        if ($location === null || $location === '') {
            return $location;
        }

        $prefix = self::pathPrefixFromAppUrl($appUrl);
        if ($prefix === null) {
            return $location;
        }

        $parts = parse_url($location);
        if ($parts === false || ! isset($parts['path'])) {
            return $location;
        }

        $collapsedPath = self::collapseDuplicatePrefix($parts['path'], $prefix);
        if ($collapsedPath === $parts['path']) {
            return $location;
        }

        $parts['path'] = $collapsedPath;

        return self::buildUrl($parts);
    }

    private static function normalizePrefix(string $pathPrefix): ?string
    {
        $trimmed = trim($pathPrefix);
        if ($trimmed === '' || $trimmed === '/') {
            return null;
        }

        return '/'.trim($trimmed, '/');
    }

    /**
     * @param  array<string, mixed>  $parts
     */
    private static function buildUrl(array $parts): string
    {
        $url = '';

        if (isset($parts['scheme']) && is_string($parts['scheme'])) {
            $url .= $parts['scheme'].'://';
        }

        if (isset($parts['user']) && is_string($parts['user'])) {
            $url .= $parts['user'];
            if (isset($parts['pass']) && is_string($parts['pass'])) {
                $url .= ':'.$parts['pass'];
            }
            $url .= '@';
        }

        if (isset($parts['host']) && is_string($parts['host'])) {
            $url .= $parts['host'];
        }

        if (isset($parts['port']) && is_int($parts['port'])) {
            $url .= ':'.$parts['port'];
        }

        if (isset($parts['path']) && is_string($parts['path'])) {
            $url .= $parts['path'];
        }

        if (isset($parts['query']) && is_string($parts['query']) && $parts['query'] !== '') {
            $url .= '?'.$parts['query'];
        }

        if (isset($parts['fragment']) && is_string($parts['fragment']) && $parts['fragment'] !== '') {
            $url .= '#'.$parts['fragment'];
        }

        return $url;
    }
}
