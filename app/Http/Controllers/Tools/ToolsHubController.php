<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tools;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tools\ToolAiInertiaResource;
use App\Http\Resources\Tools\ToolHubInertiaResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ToolsHubController extends Controller
{
    public function index(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/Index', [
            'organizationId' => $organizationId,
            'tools' => ToolHubInertiaResource::hubLinks(),
        ]);
    }

    public function seoAnalysis(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/SeoAnalysis', [
            'organizationId' => $organizationId,
        ]);
    }

    public function emailTemplate(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'email-template'));
    }

    public function imageGenerator(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'image-generator'));
    }

    public function blog(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'blog'));
    }

    public function pressRelease(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'press-release'));
    }

    public function adCopy(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'ad-copy'));
    }

    public function keywordResearch(Request $request, string $organizationId): Response
    {
        return Inertia::render('Tools/AiTool', ToolAiInertiaResource::page($organizationId, 'keyword-research'));
    }
}
