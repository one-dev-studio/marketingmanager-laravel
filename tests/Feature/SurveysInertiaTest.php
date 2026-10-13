<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Survey;
use App\Models\SurveyQuestion;
use Tests\TestCase;

class SurveysInertiaTest extends TestCase
{
    public function test_surveys_index_renders_inertia_list(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Survey::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Inertia Survey',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.surveys.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Surveys/Index')
            ->has('surveys.data', 1)
            ->where('surveys.data.0.title', 'Inertia Survey')
            ->where('can.create', true)
            ->has('sidebar'));
    }

    public function test_surveys_create_renders_inertia_form(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.surveys.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Surveys/Create')->has('sidebar'));
    }

    public function test_survey_builder_renders_inertia_with_questions(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $survey = Survey::query()->create([
            'organization_id' => $organization->id,
            'title' => 'Builder Survey',
            'status' => 'draft',
            'created_by' => $user->id,
        ]);

        SurveyQuestion::query()->create([
            'survey_id' => $survey->id,
            'question' => 'How satisfied are you?',
            'type' => 'rating',
            'is_required' => true,
            'order' => 0,
        ]);

        $response = $this->get(route('main.surveys.builder', [
            'organizationId' => $organization->id,
            'survey' => $survey->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Surveys/Builder')
            ->where('survey.title', 'Builder Survey')
            ->has('survey.questions', 1)
            ->has('sidebar'));
    }
}
