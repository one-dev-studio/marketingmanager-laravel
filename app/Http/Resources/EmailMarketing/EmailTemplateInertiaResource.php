<?php

declare(strict_types=1);

namespace App\Http\Resources\EmailMarketing;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

final class EmailTemplateInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(EmailTemplate $template, Request $request): array
    {
        $organizationId = (int) $request->route('organizationId');

        return [
            'id' => $template->id,
            'name' => $template->name,
            'subject' => $template->subject,
            'category' => $template->category,
            'is_public' => $template->is_public,
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($organizationId) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(?EmailTemplate $template, Request $request): array
    {
        $organizationId = (int) $request->route('organizationId');

        if ($template === null) {
            return [
                'id' => null,
                'name' => '',
                'subject' => '',
                'html_content' => '',
                'text_content' => '',
                'can' => [
                    'update' => $request->user()?->hasAccessToOrganization($organizationId) ?? false,
                ],
            ];
        }

        return [
            'id' => $template->id,
            'name' => $template->name,
            'subject' => $template->subject,
            'html_content' => $template->html_content ?? '',
            'text_content' => $template->text_content ?? '',
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($organizationId) ?? false,
            ],
        ];
    }
}
