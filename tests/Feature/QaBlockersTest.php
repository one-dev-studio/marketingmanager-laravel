<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class QaBlockersTest extends TestCase
{
    public function test_organizations_index_view_loads_after_login(): void
    {
        $user = User::where('email', 'demo@test.com')->first();

        $this->actingAs($user)
            ->get(route('main.organizations'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Organizations/Index')
                ->has('organizations', 1)
                ->where('organizations.0.name', 'Brightpath Marketing (demo)'));
    }

    public function test_profile_show_view_loads(): void
    {
        $user = User::where('email', 'demo@test.com')->first();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Profile/Show'));
    }

    public function test_org_admin_can_open_settings_team_and_billing(): void
    {
        $user = User::where('email', 'demo@test.com')->first();
        $org = Organization::where('slug', 'demo-company')->first();

        $this->assertTrue($user->hasTenantRole(['admin', 'super_admin'], $org));

        $this->actingAs($user)
            ->get(route('main.settings', ['organizationId' => $org->id]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('main.team', ['organizationId' => $org->id]))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('main.billing', ['organizationId' => $org->id]))
            ->assertOk();
    }

    public function test_privacy_page_loads(): void
    {
        $this->get(route('privacy'))->assertOk()->assertSee('Privacy Policy');
    }

    public function test_analytics_route_is_named(): void
    {
        $this->assertTrue(Route::has('main.analytics.index'));
    }

    public function test_public_landing_and_survey_are_published(): void
    {
        $this->get('/p/demo-landing-page')->assertOk()->assertSee('Demo Landing Page');

        $survey = Survey::where('title', 'Customer Feedback Survey')->first();
        $this->get('/s/'.$survey->id)->assertOk()->assertSee('Customer Feedback Survey');
    }
}
