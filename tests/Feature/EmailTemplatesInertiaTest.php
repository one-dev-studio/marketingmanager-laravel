<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\EmailTemplate;
use Tests\TestCase;

class EmailTemplatesInertiaTest extends TestCase
{
    public function test_email_templates_index_renders_inertia_list(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        EmailTemplate::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Template',
            'subject' => 'Hello',
            'html_content' => '<p>Hi</p>',
            'created_by' => $user->id,
        ]);

        $response = $this->get(route('main.email-marketing.templates.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('EmailTemplates/Index')
            ->has('templates.data', 1)
            ->where('templates.data.0.name', 'Inertia Template')
            ->where('can.create', true)
            ->has('sidebar'));
    }

    public function test_email_template_builder_renders_inertia_for_new_template(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.email-marketing.templates.builder', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('EmailTemplates/Builder')
                ->where('template.id', null)
                ->has('sidebar'));
    }

    public function test_email_template_builder_renders_existing_template(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $template = EmailTemplate::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Builder Template',
            'subject' => 'Subject line',
            'html_content' => '<p>Body</p>',
            'created_by' => $user->id,
        ]);

        $this->get(route('main.email-marketing.templates.builder', [
            'organizationId' => $organization->id,
            'emailTemplate' => $template->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('EmailTemplates/Builder')
                ->where('template.name', 'Builder Template')
                ->has('sidebar'));
    }
}
