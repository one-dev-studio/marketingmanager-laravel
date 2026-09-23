@extends('layouts.app')

@section('page-title', 'Brand name generator')

@section('content')
<div class="space-y-6"
     x-data="chooseName({
        generateUrl: @js(route('main.brands.name-generator.generate', ['organizationId' => $organizationId])),
        domainUrl: @js(route('main.brands.name-generator.check-domain', ['organizationId' => $organizationId])),
        handlesUrl: @js(route('main.brands.name-generator.check-handles', ['organizationId' => $organizationId])),
        createUrl: @js(route('main.brands.create', ['organizationId' => $organizationId])),
        csrf: @js(csrf_token()),
        existing: @js($suggestions->map(fn ($s) => [
            'id' => $s->id,
            'suggested_name' => $s->suggested_name,
            'description' => $s->description,
            'domain_available' => $s->domain_available,
            'social_handles' => $s->social_handles,
        ])->values()),
     })">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Brand name generator</h1>
            <p class="mt-1 text-sm text-gray-600">
                Suggest names from keywords, then check .com and social handles.
                Availability is a heuristic HTTP check, not a registrar or official platform API.
            </p>
        </div>
        <a href="{{ route('main.brands.index', ['organizationId' => $organizationId]) }}" class="text-sm text-gray-600 hover:text-gray-900">Back to brands</a>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 p-6 space-y-4 max-w-xl">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keywords</label>
            <input type="text" x-model="keywordInput" placeholder="eco, coffee, roast"
                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">How many names</label>
            <input type="number" min="1" max="20" x-model="count"
                   class="w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
        </div>
        <button type="button" @click="generate()" :disabled="generating"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 disabled:opacity-50">
            <span x-text="generating ? 'Generating…' : 'Generate names'"></span>
        </button>
        <p x-show="error" x-text="error" class="text-sm text-red-600"></p>
    </div>

    <div class="grid gap-4 md:grid-cols-2">
        <template x-for="item in suggestions" :key="item.id || item.suggested_name">
            <div class="bg-white rounded-lg border border-gray-200 p-4 space-y-3">
                <h2 class="font-medium text-gray-900" x-text="item.suggested_name"></h2>
                <p class="text-sm text-gray-600" x-text="item.description"></p>
                <p class="text-sm text-gray-500">
                    Domain:
                    <span x-text="item.domain_available === true || item.domain_available === 'yes' ? 'likely available' : (item.domain_available === false || item.domain_available === 'no' ? 'likely taken' : (item.domain_check || 'not checked'))"></span>
                </p>
                <div class="flex flex-wrap gap-2">
                    <button type="button" class="text-sm text-blue-600 hover:text-blue-800" @click="checkDomain(item)">Check domain</button>
                    <button type="button" class="text-sm text-blue-600 hover:text-blue-800" @click="checkHandles(item)">Check handles</button>
                    <a class="text-sm text-blue-600 hover:text-blue-800"
                       :href="createUrl + '?name=' + encodeURIComponent(item.suggested_name)">Use this name</a>
                </div>
                <ul x-show="item.handles" class="text-sm text-gray-600 space-y-1">
                    <template x-for="(info, platform) in item.handles" :key="platform">
                        <li>
                            <span class="capitalize" x-text="platform"></span>:
                            <span x-text="info.handle"></span>
                            —
                            <span x-text="info.available ? 'likely available' : 'likely taken'"></span>
                        </li>
                    </template>
                </ul>
            </div>
        </template>
    </div>
</div>
<script>
function chooseName(config) {
    return {
        keywordInput: '',
        count: 10,
        generating: false,
        error: '',
        createUrl: config.createUrl,
        suggestions: config.existing || [],
        async generate() {
            this.generating = true;
            this.error = '';
            try {
                const keywords = this.keywordInput.split(',').map((k) => k.trim()).filter(Boolean);
                if (!keywords.length) {
                    throw new Error('Enter at least one keyword.');
                }
                const response = await fetch(config.generateUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': config.csrf,
                    },
                    body: JSON.stringify({ keywords, count: Number(this.count) || 10 }),
                });
                const payload = await response.json();
                if (!response.ok) {
                    throw new Error(payload.message || 'Could not generate names.');
                }
                this.suggestions = payload.data || [];
            } catch (e) {
                this.error = e.message;
            } finally {
                this.generating = false;
            }
        },
        async checkDomain(item) {
            const response = await fetch(config.domainUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': config.csrf,
                },
                body: JSON.stringify({ domain: item.suggested_name + '.com' }),
            });
            const payload = await response.json();
            item.domain_available = payload.data?.available;
        },
        async checkHandles(item) {
            const response = await fetch(config.handlesUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': config.csrf,
                },
                body: JSON.stringify({ name: item.suggested_name }),
            });
            const payload = await response.json();
            item.handles = payload.data || {};
        },
    };
}
</script>
@endsection
