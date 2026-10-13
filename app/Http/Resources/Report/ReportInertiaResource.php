<?php

declare(strict_types=1);

namespace App\Http\Resources\Report;

use App\Models\Report;
use Illuminate\Http\Request;

final class ReportInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Report $report, Request $request): array
    {
        return [
            'id' => $report->id,
            'name' => $report->name,
            'type' => $report->type,
            'last_generated_at' => $report->last_generated_at?->toIso8601String(),
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($report->organization_id) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(?Report $report, Request $request): array
    {
        $organizationId = (int) $request->route('organizationId');

        if ($report === null) {
            return [
                'id' => null,
                'name' => '',
                'type' => 'overall',
                'config' => ['widgets' => []],
                'can' => [
                    'update' => $request->user()?->hasAccessToOrganization($organizationId) ?? false,
                ],
            ];
        }

        return [
            'id' => $report->id,
            'name' => $report->name,
            'type' => $report->type,
            'config' => $report->config ?? ['widgets' => []],
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($report->organization_id) ?? false,
            ],
        ];
    }
}
