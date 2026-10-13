<?php

declare(strict_types=1);

namespace App\Http\Resources\PaidCampaign;

use App\Models\PaidCampaign;

final class PaidCampaignInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(PaidCampaign $campaign): array
    {
        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'platform' => $campaign->platform,
            'status' => $campaign->status,
            'budget' => $campaign->budget,
            'budget_type' => $campaign->budget_type,
            'currency' => $campaign->currency ?? 'USD',
            'spent' => $campaign->spent ?? 0,
            'start_date' => $campaign->start_date?->toDateString(),
            'end_date' => $campaign->end_date?->toDateString(),
        ];
    }
}
