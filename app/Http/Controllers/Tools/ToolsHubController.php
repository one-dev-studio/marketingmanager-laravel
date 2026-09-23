<?php

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Campaign;
use Illuminate\Http\Request;

class ToolsHubController extends Controller
{
    public function index(Request $request, string $organizationId)
    {
        return view('tools.index', [
            'title' => 'Content Ideation',
            'organizationId' => $organizationId,
        ]);
    }

    public function seoAnalysis(Request $request, string $organizationId)
    {
        return view('tools.seo-analysis', $this->toolPayload($organizationId, 'SEO Analysis'));
    }

    public function emailTemplate(Request $request, string $organizationId)
    {
        return view('tools.email-template', $this->toolPayload($organizationId, 'Email Template'));
    }

    public function imageGenerator(Request $request, string $organizationId)
    {
        return view('tools.image-generator', $this->toolPayload($organizationId, 'Image Generator'));
    }

    public function blog(Request $request, string $organizationId)
    {
        return view('tools.blog', $this->toolPayload($organizationId, 'Blog Post'));
    }

    public function pressRelease(Request $request, string $organizationId)
    {
        return view('tools.press-release-ai', $this->toolPayload($organizationId, 'Press Release'));
    }

    public function adCopy(Request $request, string $organizationId)
    {
        return view('tools.ad-copy', $this->toolPayload($organizationId, 'Ad Copy'));
    }

    public function keywordResearch(Request $request, string $organizationId)
    {
        return view('tools.keyword-research', $this->toolPayload($organizationId, 'Keyword Research'));
    }

    private function toolPayload(string $organizationId, string $title): array
    {
        return [
            'title' => $title,
            'organizationId' => $organizationId,
            'brands' => Brand::where('organization_id', $organizationId)->orderBy('name')->get(),
            'campaigns' => Campaign::where('organization_id', $organizationId)->orderBy('name')->get(['id', 'name']),
        ];
    }
}
