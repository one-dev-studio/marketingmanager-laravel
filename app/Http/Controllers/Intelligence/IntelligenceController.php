<?php

namespace App\Http\Controllers\Intelligence;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Competitor;
use Illuminate\Http\Request;

class IntelligenceController extends Controller
{
    public function sentiment(Request $request, string $organizationId)
    {
        return view('intelligence.sentiment', [
            'title' => 'Sentiment',
            'organizationId' => $organizationId,
        ]);
    }

    public function predictive(Request $request, string $organizationId)
    {
        return view('intelligence.predictive', [
            'title' => 'Predictive Analytics',
            'organizationId' => $organizationId,
            'campaigns' => Campaign::where('organization_id', $organizationId)->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
