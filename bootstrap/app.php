<?php

use App\Http\Middleware\ApiRateLimit;
use App\Http\Middleware\EnsureAgencyAccess;
use App\Http\Middleware\EnsureAgencyAdmin;
use App\Http\Middleware\EnsureBrandContext;
use App\Http\Middleware\EnsureClientAccess;
use App\Http\Middleware\EnsureClientPortal;
use App\Http\Middleware\EnsureNotClientRole;
use App\Http\Middleware\EnsureOrganizationAccess;
use App\Http\Middleware\EnsureOrganizationAdmin;
use App\Http\Middleware\HandleInertiaRequests;
use App\Http\Middleware\NormalizeSubdirectoryRedirects;
use App\Http\Middleware\PermissionMiddleware;
use App\Http\Middleware\RequireConfirmation;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\SetTenantContext;
use App\Providers\AppServiceProvider;
use App\Providers\LocalizationServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders([
        AppServiceProvider::class,
        LocalizationServiceProvider::class,
    ])
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Payment and social webhooks authenticate via platform signatures, not session CSRF.
        $middleware->validateCsrfTokens(except: [
            'webhooks/*',
        ]);

        $middleware->redirectGuestsTo(function (Request $request) {
            return $request->is('admin', 'admin/*') ? route('admin.login') : route('login');
        });

        $middleware->alias([
            'organization' => EnsureOrganizationAccess::class,
            'organization.admin' => EnsureOrganizationAdmin::class,
            'not.client' => EnsureNotClientRole::class,
            'agency' => EnsureAgencyAccess::class,
            'agency.admin' => EnsureAgencyAdmin::class,
            'locale' => SetLocale::class,
            'tenant' => SetTenantContext::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'brand.context' => EnsureBrandContext::class,
            'require.confirmation' => RequireConfirmation::class,
            'agency.client' => EnsureClientAccess::class,
            'client.portal' => EnsureClientPortal::class,
            'api.rate_limit' => ApiRateLimit::class,
        ]);

        // Add SetLocale middleware to web group by default
        $middleware->web(append: [
            SetLocale::class,
            HandleInertiaRequests::class,
        ]);

        // Add security headers to all responses
        $middleware->web(append: [
            SecurityHeaders::class,
        ]);

        $middleware->web(append: [
            NormalizeSubdirectoryRedirects::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
