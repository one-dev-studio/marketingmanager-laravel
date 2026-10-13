<?php

declare(strict_types=1);

namespace App\Http\Resources\Channel;

use App\Models\Channel;
use Illuminate\Http\Request;

/**
 * Serializes a channel for Inertia pages (no secrets in settings).
 */
final class ChannelInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function toArray(Channel $channel, Request $request): array
    {
        $settings = $channel->settings?->settings_json ?? [];
        $connection = $channel->socialConnection;
        $connected = $connection?->isConnected() ?? false;

        return [
            'id' => $channel->id,
            'display_name' => $channel->display_name,
            'type' => $channel->type,
            'platform' => $channel->platform,
            'status' => $channel->status,
            'connected' => $connected,
            'connection_status_label' => $connected ? 'Connected' : ($channel->status ?? 'disconnected'),
            'has_social_connection' => $connection !== null,
            'social_connection_id' => $connection?->id,
            'follower_count' => $settings['follower_count'] ?? null,
            'engagement_rate' => $settings['engagement_rate'] ?? null,
            'handle' => $settings['handle'] ?? null,
            'api_key' => isset($settings['api_key']) ? (string) $settings['api_key'] : '',
            'can' => [
                'update' => $request->user()?->can('update', $channel) ?? false,
                'delete' => $request->user()?->can('delete', $channel) ?? false,
            ],
        ];
    }
}
