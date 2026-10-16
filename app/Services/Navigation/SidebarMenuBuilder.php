<?php

declare(strict_types=1);

namespace App\Services\Navigation;

use App\Models\Brand;
use App\Models\ChatMessage;
use App\Models\ChatTopic;
use App\Models\Organization;
use App\Models\User;
use App\Services\ReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

/**
 * Builds sidebar navigation structure for Blade and Inertia (single source of menu logic).
 */
class SidebarMenuBuilder
{
    public function __construct(
        private readonly ReviewService $reviewService,
    ) {}

    /**
     * @return array<string, mixed>|null Null when organization context is missing.
     */
    public function buildForRequest(Request $request): ?array
    {
        $user = $request->user();
        if ($user === null) {
            return null;
        }

        $organizationId = $request->route('organizationId');
        if ($organizationId === null) {
            return null;
        }

        $organization = Organization::find($organizationId);
        if ($organization === null) {
            return null;
        }

        return $this->build($user, $organization, $request);
    }

    /**
     * @return array<string, mixed>
     */
    public function build(User $user, Organization $organization, Request $request): array
    {
        $organizationId = (int) $organization->id;
        $organizationIdString = (string) $organizationId;
        $brandId = $request->query('brandId');
        $brandIdString = is_string($brandId) || is_numeric($brandId) ? (string) $brandId : null;

        $brands = $organization->brands;
        $hasBrands = $brands->count() > 0;
        $hasBrandSelected = $brandIdString !== null && $brandIdString !== '';

        $isClientRole = $user->hasRole(['client', 'viewer'], $organization);
        $isOrgAdmin = $user->hasRole(['admin', 'super-admin'], $organization);

        $reviewCount = $this->resolveReviewCount($organizationId);
        $mentionCount = $this->resolveMentionCount($organizationId, $user);

        $brandQuery = $hasBrandSelected ? '?brandId='.$brandIdString : '';

        return [
            'appName' => (string) config('app.name'),
            'organizationId' => $organizationIdString,
            'brandId' => $brandIdString,
            'hasBrands' => $hasBrands,
            'hasBrandSelected' => $hasBrandSelected,
            'dashboardHref' => route('main.dashboard', ['organizationId' => $organizationIdString]),
            'brands' => $this->mapBrands($brands, $organizationIdString),
            'mentionCount' => $mentionCount,
            'reviewCount' => $reviewCount,
            'items' => $this->buildMenuItems(
                $organizationIdString,
                $brandIdString,
                $brandQuery,
                $hasBrandSelected,
                $isClientRole,
                $isOrgAdmin,
                $mentionCount,
                $reviewCount,
                $request,
            ),
        ];
    }

    /**
     * @param  Collection<int, Brand>  $brands
     * @return list<array{id: int|string, name: string, href: string}>
     */
    private function mapBrands(Collection $brands, string $organizationId): array
    {
        return $brands->map(static function ($brand) use ($organizationId): array {
            return [
                'id' => $brand->id,
                'name' => $brand->name,
                'href' => route('main.dashboard', ['organizationId' => $organizationId]).'?brandId='.$brand->id,
            ];
        })->values()->all();
    }

    private function resolveReviewCount(int $organizationId): int
    {
        $reviewStats = $this->reviewService->getReviewStats($organizationId);

        return (int) ($reviewStats['pending'] ?? 0);
    }

    private function resolveMentionCount(int $organizationId, User $user): int
    {
        $userNameParts = explode(' ', $user->name);
        $firstName = $userNameParts[0] ?? '';
        $lastName = $userNameParts[1] ?? '';

        $userTopics = ChatTopic::query()
            ->where('organization_id', $organizationId)
            ->whereHas('participants', function ($q) use ($user): void {
                $q->where('user_id', $user->id);
            })
            ->pluck('id');

        if ($userTopics->isEmpty()) {
            return 0;
        }

        return ChatMessage::query()
            ->whereIn('chat_topic_id', $userTopics)
            ->where('user_id', '!=', $user->id)
            ->where(function ($query) use ($user, $firstName, $lastName): void {
                $query->where('message', 'like', '%@'.str_replace(' ', '', $user->name).'%')
                    ->orWhere('message', 'like', '%@'.$firstName.'%')
                    ->orWhere('message', 'like', '%@'.$lastName.'%');
            })
            ->whereDoesntHave('topic.participants', function ($q) use ($user): void {
                $q->where('user_id', $user->id)
                    ->whereNotNull('last_read_at')
                    ->whereColumn('last_read_at', '>=', 'chat_messages.created_at');
            })
            ->count();
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildMenuItems(
        string $organizationId,
        ?string $brandId,
        string $brandQuery,
        bool $hasBrandSelected,
        bool $isClientRole,
        bool $isOrgAdmin,
        int $mentionCount,
        int $reviewCount,
        Request $request,
    ): array {
        $items = [];

        $items[] = $this->linkItem(
            'Home',
            route('main.collaboration.index', ['organizationId' => $organizationId]),
            'home',
            $mentionCount > 0 ? $mentionCount : null,
            'danger',
            $request->routeIs('main.collaboration.*'),
        );

        if ($hasBrandSelected) {
            $items = array_merge($items, $this->brandContextItems($organizationId, $brandQuery, $reviewCount, $request));
        }

        if (! $isClientRole) {
            $items = array_merge($items, $this->nonClientItems($organizationId, $request));
        }

        if (! $hasBrandSelected) {
            $items = array_merge($items, $this->noBrandContextItems($organizationId, $request));
        }

        if ($isOrgAdmin) {
            $items[] = $this->organizationGroup($organizationId, $request);
        }

        return $items;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function brandContextItems(
        string $organizationId,
        string $brandQuery,
        int $reviewCount,
        Request $request,
    ): array {
        return [
            $this->linkItem(
                'Brand Assets',
                route('main.brand-assets', ['organizationId' => $organizationId]).$brandQuery,
                'brand-assets',
                null,
                'secondary',
                $request->routeIs('main.brand-assets'),
            ),
            $this->linkItem(
                'Review',
                route('main.content-approvals.index', ['organizationId' => $organizationId]).$brandQuery,
                'review',
                $reviewCount > 0 ? $reviewCount : null,
                'danger',
                $request->routeIs('main.content-approvals.*'),
            ),
            $this->linkItem(
                'Files',
                route('main.files.index', ['organizationId' => $organizationId]).$brandQuery,
                'files',
                null,
                'secondary',
                $request->routeIs('main.files'),
            ),
            $this->linkItem(
                'Analytics',
                route('main.analytics.index', ['organizationId' => $organizationId]).$brandQuery,
                'analytics',
                null,
                'secondary',
                $request->routeIs('main.analytics.*'),
            ),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function nonClientItems(string $organizationId, Request $request): array
    {
        return [
            [
                'type' => 'group',
                'label' => 'Campaigns',
                'icon' => 'campaigns',
                'defaultOpen' => $request->routeIs('main.campaigns.*'),
                'children' => [
                    $this->childLink(
                        'Campaigns',
                        route('main.campaigns.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.campaigns.index') || $request->routeIs('main.campaigns.show'),
                    ),
                    $this->childLink(
                        'Competitions',
                        route('main.campaigns.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.campaigns.competitions.*'),
                    ),
                ],
            ],
            $this->linkItem(
                'Projects',
                route('main.projects.index', ['organizationId' => $organizationId]),
                'projects',
                null,
                'secondary',
                $request->routeIs('main.projects.*'),
            ),
            $this->linkItem(
                'Tasks',
                route('main.tasks.index', ['organizationId' => $organizationId]),
                'tasks',
                null,
                'secondary',
                $request->routeIs('main.tasks.*'),
            ),
            $this->linkItem(
                'Chatbots',
                route('main.chatbots.index', ['organizationId' => $organizationId]),
                'chatbots',
                null,
                'secondary',
                $request->routeIs('main.chatbots.*'),
            ),
            $this->linkItem(
                'Landing Pages',
                route('main.landing-pages.index', ['organizationId' => $organizationId]),
                'landing-pages',
                null,
                'secondary',
                $request->routeIs('main.landing-pages.*'),
            ),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function noBrandContextItems(string $organizationId, Request $request): array
    {
        return [
            $this->linkItem(
                'Brands',
                route('main.brands.index', ['organizationId' => $organizationId]),
                'brands',
                null,
                'secondary',
                $request->routeIs('main.brands.*'),
            ),
            $this->linkItem(
                'Channels',
                route('main.social.channels.index', ['organizationId' => $organizationId]),
                'channels',
                null,
                'secondary',
                $request->routeIs('main.social.channels.*'),
            ),
            $this->linkItem(
                'Products',
                route('main.products.index', ['organizationId' => $organizationId]),
                'products',
                null,
                'secondary',
                $request->routeIs('main.products.*'),
            ),
            $this->linkItem(
                'Contacts',
                route('main.email-marketing.contacts.index', ['organizationId' => $organizationId]),
                'contacts',
                null,
                'secondary',
                $request->routeIs('main.email-marketing.contacts.*'),
            ),
            [
                'type' => 'group',
                'label' => 'Email Marketing',
                'icon' => 'email-marketing',
                'defaultOpen' => $request->routeIs('main.email-marketing.*'),
                'children' => [
                    $this->childLink(
                        'Email Campaigns',
                        route('main.email-marketing.campaigns.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.email-marketing.campaigns.*'),
                    ),
                    $this->childLink(
                        'Surveys',
                        route('main.surveys.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.surveys.*'),
                    ),
                ],
            ],
            [
                'type' => 'group',
                'label' => 'Paid Ads',
                'icon' => 'paid-ads',
                'defaultOpen' => $request->routeIs('main.paid-campaigns.*')
                    || $request->routeIs('main.ai.ad-copy')
                    || $request->routeIs('main.ai.keyword-research'),
                'children' => [
                    $this->childLink(
                        'Ad Campaigns',
                        route('main.paid-campaigns.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.paid-campaigns.*'),
                    ),
                    $this->childLink(
                        'Ad Copy Gen',
                        $this->safeRoute('main.ai.ad-copy', ['organizationId' => $organizationId]),
                        $request->routeIs('main.ai.ad-copy'),
                    ),
                    $this->childLink(
                        'Keyword Res',
                        $this->safeRoute('main.ai.keyword-research', ['organizationId' => $organizationId]),
                        $request->routeIs('main.ai.keyword-research'),
                    ),
                ],
            ],
            [
                'type' => 'group',
                'label' => 'Content Ideation',
                'icon' => 'content-ideation',
                'defaultOpen' => $request->routeIs('main.tools.*'),
                'children' => [
                    $this->childLink(
                        'SEO Analysis',
                        $this->safeRoute('main.tools.seo-analysis', ['organizationId' => $organizationId]),
                        $request->routeIs('main.tools.seo-analysis'),
                    ),
                    $this->childLink(
                        'Email Template',
                        $this->safeRoute('main.tools.email-template', ['organizationId' => $organizationId]),
                        $request->routeIs('main.tools.email-template'),
                    ),
                    $this->childLink(
                        'Label Insp',
                        route('main.tools.label-inspiration', ['organizationId' => $organizationId]),
                        $request->routeIs('main.tools.label-inspiration'),
                    ),
                    $this->childLink(
                        'Image Gen',
                        $this->safeRoute('main.tools.image-generator', ['organizationId' => $organizationId]),
                        $request->routeIs('main.tools.image-generator'),
                    ),
                    $this->childLink(
                        'Product Cat',
                        route('main.tools.product-catalog', ['organizationId' => $organizationId]),
                        $request->routeIs('main.tools.product-catalog'),
                    ),
                ],
            ],
            [
                'type' => 'group',
                'label' => 'Intelligence',
                'icon' => 'intelligence',
                'defaultOpen' => $request->routeIs('main.intelligence.*') || $request->routeIs('main.competitors.*'),
                'children' => [
                    $this->childLink(
                        'Sentiment',
                        $this->safeRoute('main.intelligence.sentiment', ['organizationId' => $organizationId]),
                        $request->routeIs('main.intelligence.sentiment'),
                    ),
                    $this->childLink(
                        'Predictive',
                        $this->safeRoute('main.intelligence.predictive', ['organizationId' => $organizationId]),
                        $request->routeIs('main.intelligence.predictive'),
                    ),
                    $this->childLink(
                        'Competitor',
                        route('main.competitors.index', ['organizationId' => $organizationId]),
                        $request->routeIs('main.competitors.*'),
                    ),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function organizationGroup(string $organizationId, Request $request): array
    {
        return [
            'type' => 'group',
            'label' => 'Organization',
            'icon' => 'organization',
            'defaultOpen' => $request->routeIs('main.settings')
                || $request->routeIs('main.billing')
                || $request->routeIs('main.team.*')
                || $request->routeIs('main.storage-sources.*')
                || $request->routeIs('main.automations.*'),
            'children' => [
                $this->childLink(
                    'Settings',
                    route('main.settings', ['organizationId' => $organizationId]),
                    $request->routeIs('main.settings'),
                ),
                $this->childLink(
                    'Billing',
                    route('main.billing', ['organizationId' => $organizationId]),
                    $request->routeIs('main.billing'),
                ),
                $this->childLink(
                    'Team Members',
                    route('main.team', ['organizationId' => $organizationId]),
                    $request->routeIs('main.team.*'),
                ),
                $this->childLink(
                    'Storage Sources',
                    route('main.storage-sources', ['organizationId' => $organizationId]),
                    $request->routeIs('main.storage-sources.*'),
                ),
                $this->childLink(
                    'Automations',
                    route('main.automations', ['organizationId' => $organizationId]),
                    $request->routeIs('main.automations.*'),
                ),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function linkItem(
        string $label,
        string $href,
        string $icon,
        ?int $badge,
        string $badgeVariant,
        bool $isActive,
    ): array {
        return [
            'type' => 'link',
            'label' => $label,
            'href' => $href,
            'icon' => $icon,
            'badge' => $badge,
            'badgeVariant' => $badgeVariant,
            'isActive' => $isActive,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function childLink(string $label, string $href, bool $isActive): array
    {
        return [
            'type' => 'link',
            'label' => $label,
            'href' => $href,
            'icon' => null,
            'badge' => null,
            'badgeVariant' => 'secondary',
            'isActive' => $isActive,
        ];
    }

    /**
     * @param  array<string, mixed>  $params
     */
    private function safeRoute(string $name, array $params = [], string $fallback = '#'): string
    {
        return Route::has($name) ? route($name, $params) : $fallback;
    }
}
