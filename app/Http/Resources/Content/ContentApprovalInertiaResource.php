<?php

declare(strict_types=1);

namespace App\Http\Resources\Content;

use App\Models\ContentApproval;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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

    /**
     * @param  Collection<int, ContentApproval>  $history
     * @return array<string, mixed>
     */
    public static function detail(
        ContentApproval $approval,
        Request $request,
        iterable $history,
    ): array {
        $scheduledPost = $approval->scheduledPost;
        $user = $request->user();

        return [
            'id' => $approval->id,
            'status' => $approval->status,
            'comments' => $approval->comments,
            'campaign_name' => $scheduledPost?->campaign?->name,
            'channel_name' => $scheduledPost?->channel?->display_name,
            'content' => $scheduledPost?->content ?? '',
            'scheduled_at' => $scheduledPost?->scheduled_at?->toIso8601String(),
            'can' => [
                'approve' => $approval->status === ContentApproval::STATUS_PENDING
                    && $user !== null,
            ],
            'history' => collect($history)->map(fn (ContentApproval $item) => [
                'id' => $item->id,
                'status' => $item->status,
                'comments' => $item->comments,
                'reviewed_at' => $item->reviewed_at?->toIso8601String(),
                'approver_name' => $item->approvedBy?->name ?? $item->requestedBy?->name,
            ])->values()->all(),
        ];
    }
}
