<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\AddTeamMemberRequest;
use App\Http\Requests\Organization\InviteTeamMemberRequest;
use App\Services\Organization\TeamService;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function __construct(
        private TeamService $teamService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $organization = $this->resolveOrganization($request, $organizationId);
        $this->authorize('update', $organization);

        return view('organization.team.index', [
            'title' => 'Team',
            'organizationId' => $organizationId,
            'organization' => $organization,
            'teamMembers' => $this->teamService->getTeamMembers($organization),
            'availableRoles' => $this->teamService->getAvailableRoles(),
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
