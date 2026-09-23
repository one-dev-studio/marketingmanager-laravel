<?php

namespace App\Http\Requests\Review;

use Illuminate\Foundation\Http\FormRequest;

class ImportReviewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('reviews_json') && ! $this->filled('reviews')) {
            $decoded = json_decode((string) $this->input('reviews_json'), true);
            $this->merge(['reviews' => is_array($decoded) ? $decoded : []]);
        }
    }

    public function rules(): array
    {
        return [
            'source_slug' => ['required', 'string', 'exists:review_sources,slug'],
            'brand_id' => ['sometimes', 'nullable', 'exists:brands,id'],
            'reviews' => ['required', 'array', 'min:1'],
            'reviews.*.content' => ['required', 'string'],
            'reviews.*.rating' => ['required', 'integer', 'min:1', 'max:5'],
            'reviews.*.author' => ['sometimes', 'nullable', 'string', 'max:255'],
            'reviews.*.author_email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'reviews.*.date' => ['sometimes', 'nullable', 'date'],
            'reviews.*.sentiment' => ['sometimes', 'nullable', 'in:positive,negative,neutral'],
            'reviews.*.status' => ['sometimes', 'nullable', 'in:active,inactive'],
        ];
    }
}

