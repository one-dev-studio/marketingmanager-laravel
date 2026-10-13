<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Chatbot;
use App\Models\Workflow;
use Tests\TestCase;

class WorkflowsChatbotsInertiaTest extends TestCase
{
    public function test_workflows_index_renders_inertia(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Workflow::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Workflow',
            'type' => 'automation',
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->get(route('main.workflows.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Workflows/Index')
                ->has('workflows.data', 1)
                ->where('workflows.data.0.name', 'Inertia Workflow')
                ->where('can.create', true)
                ->has('sidebar'));
    }

    public function test_workflow_builder_renders_inertia(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $workflow = Workflow::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Builder Workflow',
            'type' => 'custom',
            'steps' => [['action' => 'notify']],
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->get(route('main.workflows.builder', [
            'organizationId' => $organization->id,
            'id' => $workflow->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Workflows/Builder')
                ->where('workflow.name', 'Builder Workflow')
                ->has('sidebar'));
    }

    public function test_chatbots_index_renders_inertia(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        Chatbot::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Chatbot',
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->get(route('main.chatbots.index', ['organizationId' => $organization->id]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Chatbots/Index')
                ->has('chatbots.data', 1)
                ->where('chatbots.data.0.name', 'Inertia Chatbot')
                ->has('sidebar'));
    }

    public function test_chatbot_analytics_renders_inertia(): void
    {
        [$user, $organization] = $this->actingAsOrganizationAdmin();

        $chatbot = Chatbot::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Analytics Bot',
            'is_active' => true,
            'created_by' => $user->id,
        ]);

        $this->get(route('main.chatbots.analytics', [
            'organizationId' => $organization->id,
            'id' => $chatbot->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Chatbots/Analytics')
                ->where('chatbot.name', 'Analytics Bot')
                ->has('analytics')
                ->has('sidebar'));
    }
}
