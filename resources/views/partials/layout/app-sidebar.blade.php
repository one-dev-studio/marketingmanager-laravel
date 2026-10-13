@php
    use App\Services\Navigation\SidebarIconRegistry;
    use App\Services\Navigation\SidebarMenuBuilder;

    $sidebarNavigation = app(SidebarMenuBuilder::class)->buildForRequest(request());

    if ($sidebarNavigation === null) {
        return;
    }

    $organizationId = $sidebarNavigation['organizationId'];
    $brandId = $sidebarNavigation['brandId'];
    $hasBrands = $sidebarNavigation['hasBrands'];
    $organization = \App\Models\Organization::find($organizationId);
    $brands = $organization ? $organization->brands : collect();
    $menuItems = $sidebarNavigation['items'];

    $icon = static fn (string $name): string => SidebarIconRegistry::svg($name);
@endphp

<x-partials.layout.sidebar variant="default">
    <x-partials.layout.sidebar-header variant="default">
        <div class="flex items-center space-x-2 mb-4">
            <a href="{{ $sidebarNavigation['dashboardHref'] }}" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span class="text-xl font-bold text-gray-900">{{ $sidebarNavigation['appName'] }}</span>
            </a>
        </div>
        @if($hasBrands)
            <x-partials.layout.brand-switcher 
                :brands="$brands" 
                :selectedBrandId="$brandId"
                :organizationId="$organizationId"
            />
        @endif
    </x-partials.layout.sidebar-header>

    <x-partials.layout.sidebar-content>
        <x-partials.layout.sidebar-menu>
            @foreach($menuItems as $item)
                @if($item['type'] === 'link')
                    <x-partials.layout.sidebar-menu-item 
                        href="{{ $item['href'] }}"
                        :icon="$item['icon'] ? $icon($item['icon']) : null"
                        :badge="$item['badge']"
                        :badge-variant="$item['badgeVariant']"
                        :is-active="$item['isActive']"
                    >
                        {{ $item['label'] }}
                    </x-partials.layout.sidebar-menu-item>
                @elseif($item['type'] === 'group')
                    <x-partials.layout.sidebar-collapsible 
                        :label="$item['label']"
                        :icon="$item['icon'] ? $icon($item['icon']) : null"
                        :default-open="$item['defaultOpen']"
                    >
                        @foreach($item['children'] as $child)
                            <x-partials.layout.sidebar-menu-item 
                                href="{{ $child['href'] }}"
                                :is-active="$child['isActive']"
                            >
                                {{ $child['label'] }}
                            </x-partials.layout.sidebar-menu-item>
                        @endforeach
                    </x-partials.layout.sidebar-collapsible>
                @endif
            @endforeach
        </x-partials.layout.sidebar-menu>
    </x-partials.layout.sidebar-content>

    <x-partials.layout.sidebar-footer variant="default">
        <div class="flex items-center justify-between mb-2">
            <button
                x-on:click="$parent.collapsed = !$parent.collapsed"
                class="p-2 rounded-lg hover:bg-gray-100 text-gray-600"
                aria-label="Toggle sidebar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
        </div>
        <x-partials.layout.user-menu variant="default" />
    </x-partials.layout.sidebar-footer>
</x-partials.layout.sidebar>
