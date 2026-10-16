<?php

declare(strict_types=1);

namespace App\Http\Resources\Competitor;

use App\Models\Competitor;

final class CompetitorInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Competitor $competitor): array
    {
        return [
            'id' => $competitor->id,
            'name' => $competitor->name,
            'website' => $competitor->website,
            'analyses_count' => $competitor->analyses_count ?? $competitor->analyses?->count() ?? 0,
        ];
    }
}
