<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\AutomationRule;
use Illuminate\Http\Request;

class AutomationController extends Controller
{
    public function index(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $rules = AutomationRule::where('organization_id', $organization->id)->latest()->get();

        return view('organization.automations.index', [
            'title' => 'Automations',
            'organizationId' => $organizationId,
            'organization' => $organization,
            'rules' => $rules,
        ]);
    }

    public function store(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'trigger_conditions' => 'nullable|array',
            'actions' => 'nullable|array',
            'trigger' => 'nullable|string',
            'action' => 'nullable|string',
        ]);

        AutomationRule::create([
            'organization_id' => $organization->id,
            'name' => $validated['name'],
            'trigger_conditions' => $validated['trigger_conditions'] ?? ['event' => $validated['trigger'] ?? 'campaign.published'],
            'actions' => $validated['actions'] ?? ['type' => $validated['action'] ?? 'notify'],
            'is_active' => false,
            'created_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Rule created.');
    }

    public function toggle(Request $request, string $organizationId, AutomationRule $automationRule)
    {
        $automationRule->update(['is_active' => ! $automationRule->is_active]);
        return back()->with('success', $automationRule->is_active ? 'Activated' : 'Paused');
    }

    public function test(Request $request, string $organizationId, AutomationRule $automationRule)
    {
        return back()->with('success', 'Test run queued for "'.$automationRule->name.'".');
    }
}
