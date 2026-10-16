<?php

declare(strict_types=1);

namespace App\Http\Resources\Agency;

use App\Models\Agency;
use App\Models\Organization;

final class AgencyClientInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Organization $client, Agency $agency): array
    {
        return [
            'id' => $client->id,
            'name' => $client->name,
            'users_count' => (int) ($client->users_count ?? 0),
            'show_href' => route('agency.clients.show', [
                'agency' => $agency,
                'organizationId' => $client->id,
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(Organization $client): array
    {
        return [
            'id' => $client->id,
            'name' => $client->name,
            'users_count' => (int) ($client->users_count ?? 0),
            'status' => $client->status ?? 'active',
            'workspace_href' => route('main.dashboard', ['organizationId' => $client->id]),
        ];
    }
}
