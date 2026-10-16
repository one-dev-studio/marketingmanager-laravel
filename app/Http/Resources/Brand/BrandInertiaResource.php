<?php

declare(strict_types=1);

namespace App\Http\Resources\Brand;

use App\Models\Brand;
use Illuminate\Http\Request;

final class BrandInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Brand $brand, Request $request): array
    {
        return [
            'id' => $brand->id,
            'name' => $brand->name,
            'status' => $brand->status,
            'summary' => $brand->summary,
            'guidelines' => $brand->guidelines,
            'can' => [
                'update' => $request->user()?->can('update', $brand) ?? false,
                'delete' => $request->user()?->can('delete', $brand) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(Brand $brand, Request $request): array
    {
        return [
            'id' => $brand->id,
            'name' => $brand->name,
            'summary' => $brand->summary,
            'audience' => $brand->audience,
            'guidelines' => $brand->guidelines,
            'tone_of_voice' => $brand->tone_of_voice,
            'keywords' => $brand->keywords ?? [],
            'avoid_keywords' => $brand->avoid_keywords ?? [],
            'status' => $brand->status,
            'business_model' => $brand->business_model,
            'can' => [
                'update' => $request->user()?->can('update', $brand) ?? false,
                'delete' => $request->user()?->can('delete', $brand) ?? false,
            ],
        ];
    }
}
