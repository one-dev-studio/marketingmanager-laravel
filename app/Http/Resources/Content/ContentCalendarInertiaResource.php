<?php

declare(strict_types=1);

namespace App\Http\Resources\Content;

final class ContentCalendarInertiaResource
{
    /**
     * @param  array<string, mixed>  $event
     * @return array<string, mixed>
     */
    public static function event(array $event): array
    {
        return [
            'id' => $event['id'],
            'title' => $event['title'],
            'description' => $event['description'] ?? null,
            'type' => $event['type'],
            'start' => $event['start'],
            'end' => $event['end'] ?? null,
            'all_day' => (bool) ($event['allDay'] ?? false),
            'status' => $event['status'] ?? null,
        ];
    }
}
