<?php

namespace App\Http\Controllers\Campaign;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Contest;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function index(Request $request, string $organizationId)
    {
        $contests = Contest::where('organization_id', $organizationId)
            ->with('campaign')
            ->withCount('entries')
            ->latest()
            ->paginate();

        return view('competitions.index', [
            'title' => 'Competitions',
            'organizationId' => $organizationId,
            'contests' => $contests,
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        return view('competitions.create', [
            'title' => 'Create Competition',
            'organizationId' => $organizationId,
            'campaigns' => Campaign::where('organization_id', $organizationId)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $organizationId)
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

    public function show(Request $request, string $organizationId, Contest $contest)
    {
        $contest->load('entries', 'campaign');

        return view('competitions.show', [
            'title' => $contest->name,
            'organizationId' => $organizationId,
            'contest' => $contest,
        ]);
    }

    public function storeEntry(Request $request, string $organizationId, Contest $contest)
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

    public function close(Request $request, string $organizationId, Contest $contest)
    {
        $winner = $contest->entries()->inRandomOrder()->first();
        if ($winner) {
            $winner->update(['is_winner' => true, 'status' => 'winner']);
        }
        $contest->update(['status' => 'closed']);

        return back()->with('success', $winner ? 'Winner selected.' : 'Competition closed with no entries.');
    }
}
