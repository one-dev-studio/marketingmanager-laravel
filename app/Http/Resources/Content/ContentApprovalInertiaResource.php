<?php

declare(strict_types=1);

namespace App\Http\Resources\Content;

use App\Models\ContentApproval;
use Illuminate\Http\Request;

final class ContentApprovalInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(ContentApproval $approval, Request $request): array
    {
        $organizationId = $request->route('organizationId');
        $scheduledPost = $approval->scheduledPost;
        $content = $scheduledPost?->content ?? '';

        return [
            'id' => $approval->id,
            'status' => $approval->status,
            'requested_at' => $approval->requested_at?->toIso8601String(),
            'campaign_name' => $scheduledPost?->campaign?->name,
            'channel_name' => $scheduledPost?->channel?->display_name,
            'content_preview' => mb_strlen($content) > 120 ? mb_substr($content, 0, 120).'…' : $content,
            'requested_by_name' => $approval->requestedBy?->name,
            'show_href' => route('main.content-approvals.show', [
                'organizationId' => $organizationId,
                'approval' => $approval->id,
            ]),
        ];
    }
}
