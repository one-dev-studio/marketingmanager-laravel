<?php

declare(strict_types=1);

namespace App\Http\Resources\Campaign;

use App\Models\Campaign;
use Illuminate\Http\Request;

final class CampaignInertiaResource
{
    /**
     * @return array{
     *     id: int,
     *     name: string,
     *     status: string,
     *     brand_name: string|null,
     *     show_href: string
     * }
     */
    public static function listItem(Campaign $campaign, Request $request): array
    {
        $organizationId = $request->route('organizationId');

        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'status' => (string) $campaign->status,
            'brand_name' => $campaign->brand?->name,
            'show_href' => route('main.campaigns.show', [
                'organizationId' => $organizationId,
                'campaign' => $campaign->id,
            ]),
        ];
    }
}
