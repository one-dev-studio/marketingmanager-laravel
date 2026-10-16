<?php

declare(strict_types=1);

namespace App\Http\Resources\EmailMarketing;

use App\Models\Contact;
use Illuminate\Http\Request;

final class ContactInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Contact $contact, Request $request): array
    {
        return [
            'id' => $contact->id,
            'email' => $contact->email,
            'full_name' => $contact->full_name,
            'status' => $contact->status,
            'company' => $contact->company,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function detail(Contact $contact, Request $request): array
    {
        $tags = $contact->relationLoaded('tags')
            ? $contact->tags->pluck('tag')->values()->all()
            : [];

        $contactListIds = $contact->relationLoaded('contactLists')
            ? $contact->contactLists->pluck('id')->values()->all()
            : [];

        return [
            'id' => $contact->id,
            'email' => $contact->email,
            'first_name' => $contact->first_name,
            'last_name' => $contact->last_name,
            'full_name' => $contact->full_name,
            'phone' => $contact->phone,
            'company' => $contact->company,
            'job_title' => $contact->job_title,
            'status' => $contact->status,
            'source' => $contact->source,
            'tags' => $tags,
            'contact_list_ids' => $contactListIds,
        ];
    }
}
