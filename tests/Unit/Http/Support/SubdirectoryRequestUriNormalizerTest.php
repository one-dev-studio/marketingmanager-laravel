<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Support;

use App\Http\Support\SubdirectoryRequestUriNormalizer;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SubdirectoryRequestUriNormalizerTest extends TestCase
{
    public function test_path_prefix_from_app_url(): void
    {
        $this->assertNull(SubdirectoryRequestUriNormalizer::pathPrefixFromAppUrl('https://example.com'));
        $this->assertSame(
            '/marketingmanager',
            SubdirectoryRequestUriNormalizer::pathPrefixFromAppUrl('https://oneinternetmedia.com/marketingmanager')
        );
    }

    #[DataProvider('stripPrefixProvider')]
    public function test_strip_prefix_from_request_uri(string $requestUri, string $expected): void
    {
        $result = SubdirectoryRequestUriNormalizer::stripPrefixFromRequestUri(
            $requestUri,
            '/marketingmanager'
        );

        $this->assertSame($expected, $result);
    }

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function stripPrefixProvider(): array
    {
        return [
            'root' => ['/marketingmanager', '/'],
            'login' => ['/marketingmanager/login', '/login'],
            'query string' => ['/marketingmanager/login?foo=bar', '/login?foo=bar'],
            'already normalized' => ['/login', '/login'],
            'double prefix collapsed' => ['/marketingmanager/marketingmanager/login', '/login'],
        ];
    }

    public function test_collapse_duplicate_prefix(): void
    {
        $this->assertSame(
            '/marketingmanager/login',
            SubdirectoryRequestUriNormalizer::collapseDuplicatePrefix(
                '/marketingmanager/marketingmanager/login',
                '/marketingmanager'
            )
        );
    }

    public function test_normalize_redirect_location_collapses_double_prefix(): void
    {
        $location = 'https://oneinternetmedia.com/marketingmanager/marketingmanager/login';
        $normalized = SubdirectoryRequestUriNormalizer::normalizeRedirectLocation(
            $location,
            'https://oneinternetmedia.com/marketingmanager'
        );

        $this->assertSame('https://oneinternetmedia.com/marketingmanager/login', $normalized);
    }

    public function test_apply_to_server_globals(): void
    {
        $_SERVER['REQUEST_URI'] = '/marketingmanager/dashboard';
        $_SERVER['REDIRECT_URL'] = '/marketingmanager/admin';

        SubdirectoryRequestUriNormalizer::applyToServerGlobals('/marketingmanager');

        $this->assertSame('/dashboard', $_SERVER['REQUEST_URI']);
        $this->assertSame('/admin', $_SERVER['REDIRECT_URL']);

        unset($_SERVER['REQUEST_URI'], $_SERVER['REDIRECT_URL']);
    }
}
