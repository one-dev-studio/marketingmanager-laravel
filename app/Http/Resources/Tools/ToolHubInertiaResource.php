<?php

declare(strict_types=1);

namespace App\Http\Resources\Tools;

final class ToolHubInertiaResource
{
    /**
     * @return list<array{title: string, routeName: string, description: string}>
     */
    public static function hubLinks(): array
    {
        return [
            [
                'title' => 'SEO Analysis',
                'routeName' => 'main.tools.seo-analysis',
                'description' => 'Technical and content report for a URL',
            ],
            [
                'title' => 'Email Template',
                'routeName' => 'main.tools.email-template',
                'description' => 'Generate a branded email',
            ],
            [
                'title' => 'Label Inspiration',
                'routeName' => 'main.tools.label-inspiration',
                'description' => 'Name and tagline variations',
            ],
            [
                'title' => 'Image Generator',
                'routeName' => 'main.tools.image-generator',
                'description' => 'Prompt-based image generation',
            ],
            [
                'title' => 'Product Catalog',
                'routeName' => 'main.tools.product-catalog',
                'description' => 'Catalog copy from products',
            ],
            [
                'title' => 'Blog Post',
                'routeName' => 'main.tools.blog',
                'description' => 'Long-form blog draft',
            ],
            [
                'title' => 'Press Release',
                'routeName' => 'main.tools.press-release',
                'description' => 'AI press release draft',
            ],
        ];
    }
}
