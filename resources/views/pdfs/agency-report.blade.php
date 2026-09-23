<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>{{ $report['title'] ?? 'Agency report' }}</title>
<style>body{font-family:sans-serif;font-size:12px} .brand{color:{{ $report['brand_color'] ?? '#2563eb' }}}</style>
</head>
<body>
@if(!empty($report['logo']))<img src="{{ $report['logo'] }}" height="40">@endif
<h1 class="brand">{{ $report['title'] ?? 'Client report' }}</h1>
<p>{{ $report['client_name'] ?? '' }} · {{ $report['period'] ?? '' }}</p>
<h2>Executive summary</h2>
<p>{{ $report['executive_summary'] ?? '' }}</p>
<h2>Highlights</h2>
<ul>@foreach($report['highlights'] ?? [] as $h)<li>{{ $h }}</li>@endforeach</ul>
<h2>Recommendations</h2>
<ul>@foreach($report['recommendations'] ?? [] as $r)<li>{{ $r }}</li>@endforeach</ul>
</body>
</html>
