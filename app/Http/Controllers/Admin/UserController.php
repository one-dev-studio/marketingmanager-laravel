<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignRolesRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Services\Admin\UserManagementService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function __construct(
        private UserManagementService $userService
    ) {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display user list with search and filtering
     */
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'user_type', 'status', 'email_verified']);
        $users = $this->userService->getUsers($filters);

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'filters' => $filters,
        ]);
    }

    /**
     * Show user details
     */
    public function show(User $user): Response
    {
        $activityLogs = $this->userService->getUserActivityLogs($user);

        return Inertia::render('Admin/Users/Show', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'status' => $user->status,
                'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
            ],
            'activityLogs' => $activityLogs->map(fn ($log) => [
                'action' => $log->action ?? null,
                'created_at' => $log->created_at?->diffForHumans(),
            ])->values()->all(),
        ]);
    }

    /**
     * Show edit user form
     */
    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ],
        ]);
    }

    /**
     * Update user details
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user = $this->userService->updateUser($user, $request->validated());

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User updated successfully.');
    }

    /**
     * Assign roles to user
     */
    public function assignRoles(AssignRolesRequest $request, User $user)
    {
        $this->userService->assignRoles(
            $user,
            $request->validated()['role_ids'],
            $request->validated()['organization_id'] ?? null
        );

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'Roles assigned successfully.');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        $this->userService->deactivateUser($user);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User deactivated successfully.');
    }

    /**
     * Reactivate user
     */
    public function reactivate(User $user)
    {
        $this->userService->reactivateUser($user);

        return redirect()->route('admin.users.show', $user)
            ->with('success', 'User reactivated successfully.');
    }
}
