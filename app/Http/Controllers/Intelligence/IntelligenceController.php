<?php

namespace App\Http\Controllers\Intelligence;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class IntelligenceController extends Controller
{
    public function sentiment(Request $request, string $organizationId): Response
    {
        return Inertia::render('Intelligence/Sentiment', [
            'organizationId' => $organizationId,
        ]);
    }

    public function predictive(Request $request, string $organizationId): Response
    {
        $campaigns = Campaign::query()
            ->where('organization_id', $organizationId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Intelligence/Predictive', [
            'organizationId' => $organizationId,
            'campaigns' => $campaigns->map(fn (Campaign $campaign) => [
                'id' => $campaign->id,
                'name' => $campaign->name,
            ])->values()->all(),
        ]);
    }
}
