<?php

declare(strict_types=1);

namespace App\Http\Resources\Tools;

final class ToolAiInertiaResource
{
    /**
     * @return array<string, mixed>
     */
    public static function page(string $organizationId, string $toolKey): array
    {
        $configs = [
            'email-template' => [
                'title' => 'AI Email Template',
                'submitRoute' => 'main.ai.content.email',
                'fields' => [
                    ['name' => 'purpose', 'label' => 'Purpose', 'type' => 'textarea', 'rows' => 3],
                    ['name' => 'audience', 'label' => 'Audience', 'type' => 'text'],
                ],
                'footerLink' => [
                    'label' => 'Open builder',
                    'routeName' => 'main.email-marketing.templates.builder',
                ],
            ],
            'image-generator' => [
                'title' => 'Image Generator',
                'submitRoute' => 'main.ai.images.generate',
                'fields' => [
                    ['name' => 'prompt', 'label' => 'Prompt', 'type' => 'textarea', 'rows' => 3],
                    [
                        'name' => 'style',
                        'label' => 'Style',
                        'type' => 'select',
                        'options' => ['realistic', 'artistic', 'minimalist', 'vintage', 'modern'],
                    ],
                    [
                        'name' => 'size',
                        'label' => 'Size',
                        'type' => 'select',
                        'options' => ['1024x1024', '1792x1024', '1024x1792'],
                    ],
                ],
            ],
            'blog' => [
                'title' => 'Blog Post',
                'submitRoute' => 'main.ai.content.blog',
                'fields' => [
                    ['name' => 'topic', 'label' => 'Topic', 'type' => 'text'],
                    ['name' => 'target_audience', 'label' => 'Audience', 'type' => 'text', 'default' => 'marketers'],
                ],
            ],
            'press-release' => [
                'title' => 'AI Press Release',
                'submitRoute' => 'main.ai.content.press-release',
                'fields' => [
                    ['name' => 'topic', 'label' => 'Announcement', 'type' => 'text'],
                ],
                'footerLink' => [
                    'label' => 'Press release library',
                    'routeName' => 'main.press-releases.index',
                ],
            ],
            'ad-copy' => [
                'title' => 'Ad Copy',
                'submitRoute' => 'main.ai.content.ad-copy',
                'submitLabel' => 'Generate variations',
                'fields' => [
                    [
                        'name' => 'platform',
                        'label' => 'Platform',
                        'type' => 'select',
                        'options' => ['facebook', 'google', 'linkedin', 'tiktok'],
                        'default' => 'facebook',
                    ],
                    ['name' => 'product', 'label' => 'Product', 'type' => 'text'],
                    ['name' => 'objective', 'label' => 'Objective', 'type' => 'text'],
                ],
            ],
            'keyword-research' => [
                'title' => 'Keyword Research',
                'submitRoute' => 'main.ai.seo.keyword-research',
                'submitLabel' => 'Search',
                'fields' => [
                    ['name' => 'keyword', 'label' => 'Seed keyword', 'type' => 'text'],
                ],
            ],
        ];

        if (! isset($configs[$toolKey])) {
            abort(404, 'Unknown AI tool.');
        }

        return [
            'organizationId' => $organizationId,
            'toolKey' => $toolKey,
            ...$configs[$toolKey],
        ];
    }
}
