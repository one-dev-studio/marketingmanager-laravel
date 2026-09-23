<?php

namespace App\Services\Brand;

use App\Models\Brand;
use App\Models\BrandAsset;
use App\Models\Organization;
use App\Models\User;
use App\Services\AI\ContentGenerationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use RuntimeException;

class BrandService
{
    public function __construct(
        private ContentGenerationService $contentGenerationService
    ) {}

    public function createBrand(array $data, User $user, int $organizationId): Brand
    {
        return DB::transaction(function () use ($data, $organizationId) {
            $logoPath = null;
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                $logoPath = $this->storeLogo($data['logo'], $organizationId);
            }

            $brand = Brand::create([
                'organization_id' => $organizationId,
                'name' => $data['name'],
                'summary' => $data['summary'] ?? null,
                'audience' => $data['audience'] ?? null,
                'guidelines' => $data['guidelines'] ?? null,
                'tone_of_voice' => $data['tone_of_voice'] ?? null,
                'keywords' => $data['keywords'] ?? [],
                'avoid_keywords' => $data['avoid_keywords'] ?? [],
                'logo' => $logoPath,
                'status' => $data['status'] ?? 'active',
                'business_model' => $data['business_model'] ?? null,
            ]);

            return $brand->load('organization');
        });
    }

    public function updateBrand(Brand $brand, array $data): Brand
    {
        return DB::transaction(function () use ($brand, $data) {
            if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
                if ($brand->logo) {
                    Storage::disk('public')->delete($brand->logo);
                }
                $data['logo'] = $this->storeLogo($data['logo'], $brand->organization_id);
            }

            $brand->update($data);

            return $brand->load('organization');
        });
    }

    public function deleteBrand(Brand $brand): bool
    {
        return DB::transaction(function () use ($brand) {
            if ($brand->logo) {
                Storage::disk('public')->delete($brand->logo);
            }

            $brand->assets()->each(function ($asset) {
                Storage::disk('public')->delete($asset->url);
            });

            return $brand->delete();
        });
    }

    public function addAsset(Brand $brand, array $data, ?UploadedFile $file = null): BrandAsset
    {
        $url = null;
        if ($file) {
            $url = $this->storeAsset($file, $brand->organization_id, $data['type'] ?? 'other');
        } elseif (isset($data['url'])) {
            $url = $data['url'];
        }

        return BrandAsset::create([
            'brand_id' => $brand->id,
            'name' => $data['name'],
            'type' => $data['type'] ?? 'other',
            'url' => $url,
            'tags' => $data['tags'] ?? [],
        ]);
    }

    public function updateAsset(BrandAsset $asset, array $data, ?UploadedFile $file = null): BrandAsset
    {
        if ($file) {
            if ($asset->url && Storage::disk('public')->exists($asset->url)) {
                Storage::disk('public')->delete($asset->url);
            }
            $data['url'] = $this->storeAsset($file, $asset->brand->organization_id, $data['type'] ?? $asset->type);
        }

        $asset->update($data);

        return $asset->fresh();
    }

    public function removeAsset(BrandAsset $asset): bool
    {
        if ($asset->url && Storage::disk('public')->exists($asset->url)) {
            Storage::disk('public')->delete($asset->url);
        }

        return $asset->delete();
    }

    /**
     * Get brand assets grouped by type
     */
    public function getAssetsGroupedByType(Brand $brand): array
    {
        $assets = $brand->assets()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('type')
            ->map(function ($group) {
                return $group->map(function ($asset) {
                    return [
                        'id' => $asset->id,
                        'name' => $asset->name,
                        'type' => $asset->type,
                        'url' => $asset->url ? Storage::url($asset->url) : null,
                        'tags' => $asset->tags,
                        'created_at' => $asset->created_at->toIso8601String(),
                    ];
                })->values();
            })
            ->toArray();

        return [
            'logo' => $assets['logo'] ?? [],
            'image' => $assets['image'] ?? [],
            'font' => $assets['font'] ?? [],
            'color' => $assets['color'] ?? [],
            'other' => $assets['other'] ?? [],
        ];
    }

    /**
     * Generate a brand concept (summary, audience, guidelines, keywords) via AI.
     */
    public function generateConcept(Organization $organization, User $user, array $input): array
    {
        $name = trim((string) ($input['name'] ?? ''));
        $industry = trim((string) ($input['industry'] ?? ''));
        $keywords = array_values(array_filter($input['keywords'] ?? []));

        $prompt = "Create a marketing brand concept.\n";
        if ($name !== '') {
            $prompt .= "Working name: {$name}\n";
        }
        if ($industry !== '') {
            $prompt .= "Industry or category: {$industry}\n";
        }
        if ($keywords !== []) {
            $prompt .= 'Seed keywords: '.implode(', ', $keywords)."\n";
        }
        $prompt .= <<<'PROMPT'

Return ONLY valid JSON with these keys:
- summary: string (1-3 sentences)
- audience: string (who the brand is for)
- guidelines: string (voice, visual, and messaging rules)
- tone_of_voice: string (short label, max 255 characters)
- keywords: array of strings (words to use)
- avoid_keywords: array of strings (words to avoid)
PROMPT;

        $generation = $this->contentGenerationService->generateContent(
            $organization,
            $user,
            'other',
            $prompt,
            [
                'system_prompt' => 'You are a brand strategist. Reply with JSON only. No markdown.',
                'max_tokens' => 1200,
                'temperature' => 0.7,
            ],
            [
                'name' => $name,
                'industry' => $industry,
                'keywords' => $keywords,
            ]
        );

        return $this->parseConceptJson((string) $generation->generated_content);
    }

    private function parseConceptJson(string $content): array
    {
        $json = $content;
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $json = $matches[0];
        }

        $decoded = json_decode($json, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('AI did not return a valid brand concept.');
        }

        $keywords = $decoded['keywords'] ?? [];
        $avoid = $decoded['avoid_keywords'] ?? [];

        return [
            'summary' => (string) ($decoded['summary'] ?? ''),
            'audience' => (string) ($decoded['audience'] ?? ''),
            'guidelines' => (string) ($decoded['guidelines'] ?? ''),
            'tone_of_voice' => substr((string) ($decoded['tone_of_voice'] ?? ''), 0, 255),
            'keywords' => array_values(array_filter(array_map('strval', is_array($keywords) ? $keywords : []))),
            'avoid_keywords' => array_values(array_filter(array_map('strval', is_array($avoid) ? $avoid : []))),
        ];
    }

    /**
     * Get brand guidelines with formatted data
     */
    public function getBrandGuidelines(Brand $brand): array
    {
        return [
            'guidelines' => $brand->guidelines,
            'tone_of_voice' => $brand->tone_of_voice,
            'keywords' => $brand->keywords ?? [],
            'avoid_keywords' => $brand->avoid_keywords ?? [],
            'audience' => $brand->audience,
            'summary' => $brand->summary,
            'logo_url' => $brand->logo ? Storage::url($brand->logo) : null,
        ];
    }

    private function storeLogo(UploadedFile $file, int $organizationId): string
    {
        return $file->store("brands/{$organizationId}/logos", 'public');
    }

    private function storeAsset(UploadedFile $file, int $organizationId, string $type): string
    {
        $folder = match($type) {
            'logo' => 'logos',
            'image' => 'images',
            'font' => 'fonts',
            default => 'other',
        };

        return $file->store("brands/{$organizationId}/{$folder}", 'public');
    }
}

