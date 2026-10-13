<?php

declare(strict_types=1);

namespace Tests\Unit\Services\Navigation;

use App\Models\Organization;
use App\Models\Role;
use App\Models\User;
use App\Services\Navigation\SidebarMenuBuilder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class SidebarMenuBuilderTest extends TestCase
{
    use RefreshDatabase;

    private SidebarMenuBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = app(SidebarMenuBuilder::class);
    }

    public function test_build_includes_home_link_for_organization_admin(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();

        $request = $this->makeOrgRequest($user, $organization);

        $navigation = $this->builder->build($user, $organization, $request);

        $this->assertSame((string) $organization->id, $navigation['organizationId']);
        $home = $navigation['items'][0];
        $this->assertSame('link', $home['type']);
        $this->assertSame('Home', $home['label']);
        $this->assertStringContainsString('collaboration', $home['href']);
    }

    public function test_client_role_hides_campaign_and_brand_catalog_items(): void
    {
        $user = User::factory()->create();
        $organization = Organization::factory()->create();
        $clientRole = Role::query()->where('name', 'client')->firstOrFail();

        $user->organizations()->syncWithoutDetaching([
            $organization->id => ['role_id' => $clientRole->id],
        ]);

        $request = $this->makeOrgRequest($user, $organization);

        $navigation = $this->builder->build($user, $organization, $request);

        $labels = $this->flattenLabels($navigation['items']);

        $this->assertContains('Home', $labels);
        $this->assertNotContains('Projects', $labels);
        $this->assertContains('Brands', $labels);
    }

    public function test_org_admin_sees_organization_settings_group(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();

        $request = $this->makeOrgRequest($user, $organization);

        $navigation = $this->builder->build($user, $organization, $request);

        $groupLabels = array_map(
            static fn (array $item): ?string => $item['type'] === 'group' ? $item['label'] : null,
            $navigation['items'],
        );

        $this->assertContains('Organization', array_filter($groupLabels));
    }

    public function test_brand_selected_includes_review_link(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();

        $request = $this->makeOrgRequest($user, $organization, ['brandId' => '42']);

        $navigation = $this->builder->build($user, $organization, $request);

        $labels = $this->flattenLabels($navigation['items']);

        $this->assertContains('Review', $labels);
        $this->assertNotContains('Brands', $labels);
    }

    public function test_build_for_request_returns_null_without_organization_route(): void
    {
        [$user, $organization] = $this->createOrganizationAdmin();

        $request = Request::create('/main/organizations', 'GET');
        $request->setUserResolver(static fn () => $user);

        $this->assertNull($this->builder->buildForRequest($request));
    }

    /**
     * @param  array<string, string>  $query
     */
    private function makeOrgRequest(User $user, Organization $organization, array $query = []): Request
    {
        $url = route('main.dashboard', ['organizationId' => $organization->id]);
        if ($query !== []) {
            $url .= '?'.http_build_query($query);
        }

        $request = Request::create($url, 'GET', $query);
        $request->setUserResolver(static fn () => $user);

        Route::getRoutes()->match($request);

        return $request;
    }

    /**
     * @param  list<array<string, mixed>>  $items
     * @return list<string>
     */
    private function flattenLabels(array $items): array
    {
        $labels = [];
        foreach ($items as $item) {
            if ($item['type'] === 'link') {
                $labels[] = $item['label'];
            }
            if ($item['type'] === 'group') {
                $labels[] = $item['label'];
                foreach ($item['children'] as $child) {
                    $labels[] = $child['label'];
                }
            }
        }

        return $labels;
    }
}
