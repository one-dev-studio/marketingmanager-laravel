<?php

declare(strict_types=1);

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Contest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContestController extends Controller
{
    public function index(Request $request, string $organizationId): Response
    {
        $contests = Contest::where('organization_id', $organizationId)
            ->with('campaign')
            ->withCount('entries')
            ->latest()
            ->paginate()
            ->through(fn (Contest $contest) => [
                'id' => $contest->id,
                'name' => $contest->name,
                'status' => $contest->status,
                'entries_count' => $contest->entries_count,
                'campaign_name' => $contest->campaign?->name,
            ]);

        return Inertia::render('Competitions/Index', [
            'organizationId' => (string) $organizationId,
            'contests' => $contests,
        ]);
    }

    public function create(Request $request, string $organizationId): Response
    {
        $campaigns = Campaign::where('organization_id', $organizationId)->orderBy('name')->get();

        return Inertia::render('Competitions/Create', [
            'organizationId' => (string) $organizationId,
            'campaigns' => $campaigns->map(fn (Campaign $campaign) => [
                'id' => $campaign->id,
                'name' => $campaign->name,
            ])->values()->all(),
        ]);
    }

    public function store(Request $request, string $organizationId): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prize' => 'nullable|string|max:255',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after:starts_at',
        ]);

        $contest = Contest::create([
            ...$validated,
            'organization_id' => $organizationId,
            'status' => 'open',
        ]);

        return redirect()
            ->route('main.competitions.show', ['organizationId' => $organizationId, 'contest' => $contest])
            ->with('success', 'Competition created.');
    }

    public function show(Request $request, string $organizationId, Contest $contest): Response
    {
        $contest->load('entries', 'campaign');

        return Inertia::render('Competitions/Show', [
            'organizationId' => (string) $organizationId,
            'contest' => [
                'id' => $contest->id,
                'name' => $contest->name,
                'status' => $contest->status,
                'prize' => $contest->prize,
                'entries' => $contest->entries->map(fn ($entry) => [
                    'id' => $entry->id,
                    'entrant_name' => $entry->entrant_name,
                    'entrant_email' => $entry->entrant_email,
                    'answer' => $entry->answer,
                    'is_winner' => (bool) $entry->is_winner,
                ])->values()->all(),
            ],
        ]);
    }

    public function storeEntry(Request $request, string $organizationId, Contest $contest): RedirectResponse
    {
        $validated = $request->validate([
            'entrant_name' => 'required|string|max:255',
            'entrant_email' => 'required|email',
            'answer' => 'nullable|string',
        ]);

        $contest->entries()->create($validated);
        $contest->increment('entry_count');

        return back()->with('success', 'Entry recorded.');
    }

    public function close(Request $request, string $organizationId, Contest $contest): RedirectResponse
    {
        $winner = $contest->entries()->inRandomOrder()->first();
        if ($winner) {
            $winner->update(['is_winner' => true, 'status' => 'winner']);
        }
        $contest->update(['status' => 'closed']);

        return back()->with('success', $winner ? 'Winner selected.' : 'Competition closed with no entries.');
    }
}
