<?php

declare(strict_types=1);

namespace App\Http\Controllers\Inertia;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Phase 0 proof page for the Inertia + React stack (does not replace product routes).
 */
class DemoController extends Controller
{
    /**
     * Render the authenticated app shell proof (organization sidebar via shared props).
     */
    public function shell(Request $request): Response
    {
        return Inertia::render('Demo/Shell');
    }

    /**
     * Render the Inertia scaffold demo (stack verification only).
     */
    public function show(Request $request): Response
    {
        return Inertia::render('Demo/Index', [
            'appName' => config('app.name'),
            'environment' => config('app.env'),
            'laravelVersion' => app()->version(),
            'requestedAt' => now()->toIso8601String(),
            'healthRoute' => url('/up'),
        ]);
    }
}
