@extends('layouts.app')

@section('page-title', $title)

@section('content')
<div class="max-w-3xl space-y-4" x-data="aiTool()">
    <h1 class="text-2xl font-semibold">{{ $title }}</h1>
    <form @submit.prevent="run" class="bg-white border rounded-lg p-6 space-y-4">
        @if(!empty($brands))
        <select x-model="brandId" class="w-full rounded-md border-gray-300">
            <option value="">Brand context</option>
            @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
            @endforeach
        </select>
        @endif
        {{ $slot ?? '' }}
        @yield('fields')
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md" :disabled="loading">Generate</button>
    </form>
    <pre class="bg-gray-50 border rounded-lg p-4 text-sm whitespace-pre-wrap" x-text="output"></pre>
</div>
@endsection

@push('scripts')
<script>
function aiTool() {
    return {
        brandId: '',
        extra: {},
        output: '',
        loading: false,
        endpoint: @json($endpoint ?? ''),
        payload() { return { brand_id: this.brandId, ...this.extra }; },
        async run() {
            this.loading = true;
            try {
                const { data } = await window.axios.post(this.endpoint, this.payload());
                this.output = JSON.stringify(data.data ?? data, null, 2);
            } catch (e) {
                this.output = e.response?.data?.message || e.message;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endpush
