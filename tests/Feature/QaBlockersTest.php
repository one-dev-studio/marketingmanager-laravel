<?php

namespace Tests\Feature;

use App\Models\Organization;
use App\Models\User;
use Tests\TestCase;

class QaBlockersTest extends TestCase
{
    public function testOrganizationsIndexViewLoadsAfterLogin(): void
    {
        $user = User::where('email', 'demo@test.com')->first();

        $this->actingAs($user)
            ->get(route('main.organizations'))
            ->assertOk()
            ->assertSee('Demo Company');
    }

    public function testProfileShowViewLoads(): void
    {
        $user = User::where('email', 'demo@test.com')->first();

        $this->actingAs($user)
            ->get(route('profile.show'))
            ->assertOk()
            ->assertSee('Profile');
    }

    public function testOrgAdminCanOpenSettingsTeamAndBilling(): void
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

    public function testPrivacyPageLoads(): void
    {
        $this->get(route('privacy'))->assertOk()->assertSee('Privacy Policy');
    }

    public function testAnalyticsRouteIsNamed(): void
    {
        $this->assertTrue(\Illuminate\Support\Facades\Route::has('main.analytics.index'));
    }

    public function testPublicLandingAndSurveyArePublished(): void
    {
        $this->get('/p/demo-landing-page')->assertOk()->assertSee('Demo Landing Page');

        $survey = \App\Models\Survey::where('title', 'Customer Feedback Survey')->first();
        $this->get('/s/'.$survey->id)->assertOk()->assertSee('Customer Feedback Survey');
    }
}
