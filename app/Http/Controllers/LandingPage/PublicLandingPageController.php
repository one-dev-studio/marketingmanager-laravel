<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use App\Models\LandingPage;
use App\Models\LandingPageVariant;
use App\Models\PageAnalytics;
use Illuminate\Http\Request;

class PublicLandingPageController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $page = LandingPage::where('slug', $slug)
            ->where('status', 'published')
            ->where('is_active', true)
            ->with('variants')
            ->firstOrFail();

        $variant = $this->pickVariant($page);

        $row = PageAnalytics::firstOrCreate([
            'landing_page_id' => $page->id,
            'variant_id' => $variant?->id,
            'analytics_date' => now()->toDateString(),
        ], [
            'visits' => 0,
            'unique_visitors' => 0,
            'conversions' => 0,
        ]);
        $row->increment('visits');
        if (! $request->session()->has('lp_seen_'.$page->id)) {
            $row->increment('unique_visitors');
            $request->session()->put('lp_seen_'.$page->id, true);
        }

        return view('landing-pages.public', [
            'page' => $page,
            'variant' => $variant,
        ]);
    }

    private function pickVariant(LandingPage $page): ?LandingPageVariant
    {
        $variants = $page->variants;
        if ($variants->isEmpty()) {
            return null;
        }

        $roll = random_int(1, 100);
        $cursor = 0;
        foreach ($variants as $variant) {
            $cursor += (int) ($variant->traffic_percentage ?: 0);
            if ($roll <= $cursor) {
                return $variant;
            }
        }

        return $variants->first();
    }
}
