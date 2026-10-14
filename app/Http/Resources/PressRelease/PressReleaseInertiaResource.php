<?php

declare(strict_types=1);

namespace App\Http\Resources\PressRelease;

use App\Models\PressRelease;

final class PressReleaseInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(PressRelease $pressRelease): array
    {
        return [
            'id' => $pressRelease->id,
            'title' => $pressRelease->title,
            'status' => $pressRelease->status,
            'release_date' => $pressRelease->release_date?->toDayDateTimeString(),
        ];
    }
}
