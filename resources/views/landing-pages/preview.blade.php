<!DOCTYPE html>
<html><head><title>{{ $page->seo_settings['title'] ?? $page->name }}</title>
<meta name="description" content="{{ $page->seo_settings['description'] ?? '' }}">
</head>
<body>{!! $page->html_content !!}</body></html>
