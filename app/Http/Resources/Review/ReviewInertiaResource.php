<?php

declare(strict_types=1);

namespace App\Http\Resources\Review;

use App\Models\Review;

final class ReviewInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function listItem(Review $review): array
    {
        return [
            'id' => $review->id,
            'author' => $review->author,
            'rating' => $review->rating,
            'platform' => $review->platform,
            'sentiment' => $review->sentiment,
            'content' => $review->content,
        ];
    }

    /**
     * @param  iterable<int, Review>  $reviews
     * @return list<array<string, mixed>>
     */
    public static function collection(iterable $reviews): array
    {
        $items = [];
        foreach ($reviews as $review) {
            $items[] = self::listItem($review);
        }

        return $items;
    }
}
