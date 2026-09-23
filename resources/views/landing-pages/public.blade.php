<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $page->seo_settings['title'] ?? $page->name }}</title>
    <meta name="description" content="{{ $page->seo_settings['description'] ?? $page->description }}">
</head>
<body>
{!! $variant->html_content ?? $page->html_content !!}
</body>
</html>
