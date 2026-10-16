<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AddTeamMemberRequest;
use App\Http\Requests\Organization\InviteTeamMemberRequest;
use App\Services\Organization\TeamService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService
    ) {}

    public function index(Request $request, string $organizationId): Response
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        $teamMembers = $this->teamService->getTeamMembers($organization);
        $availableRoles = $this->teamService->getAvailableRoles();

        return Inertia::render('Organization/Team/Index', [
            'organizationId' => $organizationId,
            'teamMembers' => $teamMembers->map(static function ($member): array {
                return [
                    'id' => $member->id,
                    'name' => $member->name,
                    'email' => $member->email,
                    'role_id' => (int) $member->pivot->role_id,
                ];
            })->values()->all(),
            'availableRoles' => $availableRoles->map(static fn ($role): array => [
                'id' => $role->id,
                'name' => $role->name,
                'label' => in_array($role->name, ['viewer', 'client'], true) ? 'Client' : 'Org Admin',
            ])->values()->all(),
        ]);
    }

    public function addMember(AddTeamMemberRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->teamService->addTeamMember(
            $organization,
            $request->input('user_id'),
            $request->input('role_id')
        );

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Team member added successfully.'], 201);
        }

        return back()->with('success', 'Team member added.');
    }

    public function removeMember(Request $request, string $organizationId, int $userId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);
        $this->teamService->removeTeamMember($organization, $userId);

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Team member removed successfully.']);
        }

        return back()->with('success', 'Team member removed.');
    }

    public function updateRole(Request $request, string $organizationId, int $userId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);
        $request->validate(['role_id' => ['required', 'exists:roles,id']]);
        $this->teamService->updateTeamMemberRole($organization, $userId, $request->input('role_id'));

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Team member role updated successfully.']);
        }

        return back()->with('success', 'Role updated.');
    }

    public function invite(InviteTeamMemberRequest $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->teamService->inviteTeamMember(
            $organization,
            $request->input('email'),
            $request->input('role_id')
        );

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Invitation sent successfully.'], 201);
        }

        return back()->with('success', 'Invitation recorded.');
    }

    public function getRoles(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('view', $organization);

        return response()->json([
            'success' => true,
            'data' => $this->teamService->getAvailableRoles(),
        ]);
    }
}
