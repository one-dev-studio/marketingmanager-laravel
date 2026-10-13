<?php

declare(strict_types=1);

namespace App\Http\Resources\LandingPage;

use App\Models\LandingPage;
use Illuminate\Http\Request;

final class LandingPageInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(LandingPage $page, Request $request): array
    {
        return [
            'id' => $page->id,
            'name' => $page->name,
            'slug' => $page->slug,
            'status' => $page->status,
            'is_active' => $page->is_active,
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($page->organization_id) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(LandingPage $page, Request $request): array
    {
        return [
            'id' => $page->id,
            'name' => $page->name,
            'slug' => $page->slug,
            'custom_domain' => $page->custom_domain,
            'html_content' => $page->html_content,
            'page_data' => $page->page_data ?? ['blocks' => []],
            'seo_settings' => $page->seo_settings ?? ['title' => '', 'description' => ''],
            'status' => $page->status,
            'variants' => $page->relationLoaded('variants')
                ? $page->variants->map(fn ($variant) => [
                    'id' => $variant->id,
                    'name' => $variant->name,
                    'traffic_percentage' => $variant->traffic_percentage,
                ])->values()->all()
                : [],
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($page->organization_id) ?? false,
            ],
        ];
    }
}
