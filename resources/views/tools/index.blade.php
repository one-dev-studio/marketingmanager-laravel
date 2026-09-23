@extends('layouts.app')

@section('page-title', 'Content Ideation')

@section('content')
<div class="space-y-6">
    <h1 class="text-2xl font-semibold">Content Ideation</h1>
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach([
            ['SEO Analysis', 'tools.seo-analysis', 'Technical and content report for a URL'],
            ['Email Template', 'tools.email-template', 'Generate a branded email'],
            ['Label Inspiration', 'tools.label-inspiration', 'Name and tagline variations'],
            ['Image Generator', 'tools.image-generator', 'Prompt-based image generation'],
            ['Product Catalog', 'tools.product-catalog', 'Catalog copy from products'],
            ['Blog Post', 'tools.blog', 'Long-form blog draft'],
            ['Press Release', 'tools.press-release', 'AI press release draft'],
        ] as $tool)
            <a href="{{ route('main.'.$tool[1], ['organizationId' => $organizationId]) }}" class="bg-white border rounded-lg p-4 hover:border-blue-400">
                <h2 class="font-medium">{{ $tool[0] }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $tool[2] }}</p>
            </a>
        @endforeach
    </div>
</div>
@endsection
