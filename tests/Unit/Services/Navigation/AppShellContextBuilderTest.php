<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Navigation;

use App\Services\Navigation\AppShellContextBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AppShellContextBuilderTest extends TestCase
{
    private AppShellContextBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = app(AppShellContextBuilder::class);
    }

    public function test_build_includes_current_organization_and_locales(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();

        $request = Request::create(
            route('main.dashboard', ['organizationId' => $organization->id]),
            'GET',
        );
        $request->setUserResolver(static fn () => $user);
        Route::getRoutes()->match($request);

        $context = $this->builder->build($user, $organization, $request);

        $this->assertSame((string) $organization->id, $context['currentOrganization']['id']);
        $this->assertNotEmpty($context['organizations']);
        $this->assertNotEmpty($context['locales']);
        $this->assertArrayHasKey('reviewHref', $context);
    }
}
