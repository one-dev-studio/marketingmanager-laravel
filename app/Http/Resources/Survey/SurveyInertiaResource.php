<?php

declare(strict_types=1);

namespace App\Http\Resources\Survey;

use App\Models\Survey;
use Illuminate\Http\Request;

final class SurveyInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Survey $survey, Request $request): array
    {
        return [
            'id' => $survey->id,
            'title' => $survey->title,
            'status' => $survey->status,
            'response_count' => $survey->response_count ?? 0,
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($survey->organization_id) ?? false,
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function builder(Survey $survey, Request $request): array
    {
        return [
            'id' => $survey->id,
            'title' => $survey->title,
            'description' => $survey->description,
            'status' => $survey->status,
            'questions' => $survey->relationLoaded('questions')
                ? $survey->questions->map(fn ($question) => [
                    'id' => $question->id,
                    'question' => $question->question,
                    'type' => $question->type,
                    'options' => $question->options ?? [],
                    'is_required' => (bool) $question->is_required,
                    'order' => $question->order,
                ])->values()->all()
                : [],
            'public_url' => url('/s/'.$survey->id),
            'can' => [
                'update' => $request->user()?->hasAccessToOrganization($survey->organization_id) ?? false,
            ],
        ];
    }
}
