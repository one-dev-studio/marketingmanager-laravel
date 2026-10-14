<?php

declare(strict_types=1);

namespace App\Http\Resources\Project;

use App\Models\Project;
use App\Models\ProjectTemplate;

final class ProjectInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Project $project): array
    {
        $memberNames = $project->relationLoaded('members')
            ? $project->members->pluck('name')->filter()->values()->all()
            : [];

        return [
            'id' => $project->id,
            'name' => $project->name,
            'status' => $project->status,
            'progress' => (float) ($project->progress ?? 0),
            'member_names' => $memberNames,
            'client_name' => $project->relationLoaded('client') ? $project->client?->name : null,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function templateOption(ProjectTemplate $template): array
    {
        return [
            'id' => $template->id,
            'name' => $template->name,
        ];
    }
}
