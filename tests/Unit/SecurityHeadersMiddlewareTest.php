<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Http\Middleware\SecurityHeaders;
use Illuminate\Http\Request;
use Tests\TestCase;

class SecurityHeadersMiddlewareTest extends TestCase
{
    public function test_adds_hsts_on_staging_https_responses(): void
    {
        $this->app['env'] = 'staging';

        $middleware = new SecurityHeaders;
        $request = Request::create('https://marketing-manager.staging.example.com/up');

        $response = $middleware->handle($request, fn () => response('ok'));

        $this->assertSame(
            'max-age=31536000; includeSubDomains; preload',
            $response->headers->get('Strict-Transport-Security'),
        );
    }

    public function test_csp_omits_unsafe_inline_and_unsafe_eval_for_scripts_outside_local(): void
    {
        $this->app['env'] = 'staging';

        $middleware = new SecurityHeaders;
        $request = Request::create('https://example.com/login');

        $response = $middleware->handle($request, fn () => response('ok'));

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString('script-src', $csp);
        $this->assertStringNotContainsString('unsafe-inline', explode(';', explode('script-src', $csp)[1])[0]);
        $this->assertStringNotContainsString('unsafe-eval', explode(';', explode('script-src', $csp)[1])[0]);
        $this->assertMatchesRegularExpression("/'nonce-[^']+'/", $csp);
    }

    public function test_csp_omits_unsafe_inline_for_styles_outside_local(): void
    {
        $this->app['env'] = 'staging';

        $middleware = new SecurityHeaders;
        $request = Request::create('https://example.com/login');

        $response = $middleware->handle($request, fn () => response('ok'));

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $styleSegment = explode(';', explode('style-src', $csp)[1])[0];

        $this->assertStringNotContainsString('unsafe-inline', $styleSegment);
        $this->assertMatchesRegularExpression("/'nonce-[^']+'/", $styleSegment);
    }

    public function test_csp_allows_unsafe_inline_styles_in_local_environment(): void
    {
        $this->app['env'] = 'local';

        $middleware = new SecurityHeaders;
        $request = Request::create('http://localhost/login');

        $response = $middleware->handle($request, fn () => response('ok'));

        $csp = (string) $response->headers->get('Content-Security-Policy');
        $styleSegment = explode(';', explode('style-src', $csp)[1])[0];

        $this->assertStringContainsString('unsafe-inline', $styleSegment);
    }
}
