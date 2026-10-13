<?php

declare(strict_types=1);

namespace App\Http\Resources\EmailMarketing;

use App\Models\EmailCampaign;
use Illuminate\Http\Request;

final class EmailCampaignInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(EmailCampaign $campaign, Request $request, string $organizationId): array
    {
        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'subject' => $campaign->subject,
            'status' => $campaign->status,
            'scheduled_at' => $campaign->scheduled_at?->toDayDateTimeString(),
            'sent_at' => $campaign->sent_at?->toDayDateTimeString(),
            'opened_count' => $campaign->opened_count,
            'clicked_count' => $campaign->clicked_count,
            'show_href' => route('main.email-marketing.campaigns.show', [
                'organizationId' => $organizationId,
                'emailCampaign' => $campaign,
            ]),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(EmailCampaign $campaign): array
    {
        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'subject' => $campaign->subject,
            'status' => $campaign->status,
            'from_email' => $campaign->from_email,
            'from_name' => $campaign->from_name,
            'scheduled_at' => $campaign->scheduled_at?->toDayDateTimeString(),
            'sent_at' => $campaign->sent_at?->toDayDateTimeString(),
            'opened_count' => $campaign->opened_count,
            'clicked_count' => $campaign->clicked_count,
            'delivered_count' => $campaign->delivered_count,
            'total_recipients' => $campaign->total_recipients,
            'template_name' => $campaign->emailTemplate?->name,
            'contact_lists' => $campaign->contactLists->map(fn ($list) => [
                'id' => $list->id,
                'name' => $list->name,
            ])->values()->all(),
        ];
    }
}
