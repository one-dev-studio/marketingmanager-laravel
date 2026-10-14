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
}
