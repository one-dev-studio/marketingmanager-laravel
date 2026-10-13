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

    /**
     * @return array<string, mixed>
     */
    public static function detail(Campaign $campaign, Request $request): array
    {
        $organizationId = $request->route('organizationId');

        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'description' => $campaign->description,
            'status' => (string) $campaign->status,
            'start_date' => $campaign->start_date?->toIso8601String(),
            'end_date' => $campaign->end_date?->toIso8601String(),
            'budget' => $campaign->budget,
            'spent' => $campaign->spent,
            'brand_name' => $campaign->brand?->name,
            'goals' => $campaign->relationLoaded('goals')
                ? $campaign->goals->map(static fn ($goal) => [
                    'id' => $goal->id,
                    'name' => $goal->name,
                    'description' => $goal->description,
                    'target_value' => $goal->target_value,
                    'current_value' => $goal->current_value,
                    'target_date' => $goal->target_date?->toDateString(),
                ])->values()->all()
                : [],
            'scheduled_posts' => $campaign->relationLoaded('scheduledPosts')
                ? $campaign->scheduledPosts->map(static fn ($post) => [
                    'id' => $post->id,
                    'content' => $post->content,
                    'scheduled_at' => $post->scheduled_at?->toIso8601String(),
                    'status' => $post->status,
                ])->values()->all()
                : [],
            'index_href' => route('main.campaigns.index', ['organizationId' => $organizationId]),
            'can' => [
                'update' => $request->user()?->can('update', $campaign) ?? false,
                'delete' => $request->user()?->can('delete', $campaign) ?? false,
            ],
        ];
    }
}
