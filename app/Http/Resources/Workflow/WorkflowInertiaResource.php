<?php

declare(strict_types=1);

namespace App\Http\Resources\Workflow;

use App\Models\Workflow;
use Illuminate\Http\Request;

final class WorkflowInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Workflow $workflow, Request $request): array
    {
        return [
            'id' => $workflow->id,
            'name' => $workflow->name,
            'description' => $workflow->description,
            'type' => $workflow->type,
            'is_active' => (bool) $workflow->is_active,
            'executions_count' => (int) ($workflow->executions_count ?? 0),
            'creator_name' => $workflow->creator?->name,
            'can' => [
                'update' => $request->user()?->can('update', $workflow) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(?Workflow $workflow, Request $request): array
    {
        if ($workflow === null) {
            return [
                'id' => null,
                'name' => '',
                'description' => '',
                'type' => 'automation',
                'steps' => [],
                'is_active' => true,
                'can' => [
                    'update' => $request->user()?->can('create', Workflow::class) ?? false,
                ],
            ];
        }

        return [
            'id' => $workflow->id,
            'name' => $workflow->name,
            'description' => $workflow->description ?? '',
            'type' => $workflow->type,
            'steps' => $workflow->steps ?? [],
            'is_active' => (bool) $workflow->is_active,
            'can' => [
                'update' => $request->user()?->can('update', $workflow) ?? false,
            ],
        ];
    }
}
