<?php

declare(strict_types=1);

namespace App\Http\Resources\Chatbot;

use App\Models\Chatbot;
use Illuminate\Http\Request;

final class ChatbotInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Chatbot $chatbot, Request $request): array
    {
        return [
            'id' => $chatbot->id,
            'name' => $chatbot->name,
            'description' => $chatbot->description,
            'is_active' => (bool) $chatbot->is_active,
            'conversations_count' => (int) ($chatbot->conversations_count ?? 0),
            'leads_count' => (int) ($chatbot->leads_count ?? 0),
            'creator_name' => $chatbot->creator?->name,
            'can' => [
                'update' => $request->user()?->can('update', $chatbot) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(?Chatbot $chatbot, Request $request): array
    {
        if ($chatbot === null) {
            return [
                'id' => null,
                'name' => '',
                'description' => '',
                'welcome_message' => '',
                'training_data' => [],
                'settings' => [],
                'is_active' => true,
                'can' => [
                    'update' => $request->user()?->can('create', Chatbot::class) ?? false,
                ],
            ];
        }

        return [
            'id' => $chatbot->id,
            'name' => $chatbot->name,
            'description' => $chatbot->description ?? '',
            'welcome_message' => $chatbot->welcome_message ?? '',
            'training_data' => $chatbot->training_data ?? [],
            'settings' => $chatbot->settings ?? [],
            'is_active' => (bool) $chatbot->is_active,
            'can' => [
                'update' => $request->user()?->can('update', $chatbot) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function deployment(Chatbot $chatbot, Request $request): array
    {
        return [
            'id' => $chatbot->id,
            'name' => $chatbot->name,
            'embed_code' => $chatbot->embed_code,
            'is_active' => (bool) $chatbot->is_active,
            'can' => [
                'update' => $request->user()?->can('update', $chatbot) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function analyticsSummary(Chatbot $chatbot): array
    {
        return [
            'id' => $chatbot->id,
            'name' => $chatbot->name,
        ];
    }
}
