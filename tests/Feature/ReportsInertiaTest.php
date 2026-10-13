<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Report;
use Tests\TestCase;

class ReportsInertiaTest extends TestCase
{
    public function test_reports_index_renders_inertia_list(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Report::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Report',
            'type' => 'campaign',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.reports.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Reports/Index')
            ->has('reports.data', 1)
            ->where('reports.data.0.name', 'Inertia Report')
            ->where('can.create', true)
            ->has('sidebar'));
    }

    public function test_reports_create_renders_inertia_builder(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.reports.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Reports/Builder')
                ->where('report.id', null)
                ->has('sidebar'));
    }

    public function test_report_show_renders_inertia_builder_with_report(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $report = Report::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Existing Report',
            'type' => 'campaign',
            'config' => ['widgets' => [['type' => 'bar']]],
            'created_by' => $user->id,
        ]);

        $this->get(route('main.reports.show', [
            'organizationId' => $organization->id,
            'reportId' => $report->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Reports/Builder')
                ->where('report.name', 'Existing Report')
                ->has('report.config.widgets', 1)
                ->has('sidebar'));
    }
}
