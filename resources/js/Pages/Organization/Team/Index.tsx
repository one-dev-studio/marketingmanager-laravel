import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/Layouts/AppLayout';
import type { TeamMemberRow, TeamRoleOption } from '@/types/organization-team';
import { router, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface OrganizationTeamIndexProps {
    organizationId: string;
    teamMembers: TeamMemberRow[];
    availableRoles: TeamRoleOption[];
}

export default function Index({
    organizationId,
    teamMembers,
    availableRoles,
}: OrganizationTeamIndexProps) {
    const inviteForm = useForm({
        email: '',
        role_id: availableRoles[0]?.id ?? '',
    });

    const submitInvite = (event: FormEvent) => {
        event.preventDefault();
        inviteForm.post(route('main.team.invite', { organizationId }), {
            preserveScroll: true,
            onSuccess: () => inviteForm.reset('email'),
        });
    };

    const updateRole = (userId: number, roleId: number) => {
        router.put(
            route('main.team.members.update-role', { organizationId, userId }),
            { role_id: roleId },
            { preserveScroll: true },
        );
    };

    const removeMember = (userId: number) => {
        if (!window.confirm('Remove this team member?')) {
            return;
        }
        router.delete(route('main.team.members.remove', { organizationId, userId }), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout title="Team">
            <div className="mx-auto max-w-3xl space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Team</h1>
                    <p className="mt-1 text-sm text-gray-600">Invite members and manage roles</p>
                </div>

                <form
                    onSubmit={submitInvite}
                    className="flex flex-col gap-3 rounded-lg border border-gray-200 bg-white p-4 sm:flex-row sm:items-end"
                >
                    <div className="flex-1 space-y-1">
                        <Label htmlFor="invite-email">Invite email</Label>
                        <Input
                            id="invite-email"
                            type="email"
                            required
                            value={inviteForm.data.email}
                            onChange={(e) => inviteForm.setData('email', e.target.value)}
                            placeholder="colleague@example.com"
                        />
                        <InputError message={inviteForm.errors.email} />
                    </div>
                    <div className="space-y-1">
                        <Label htmlFor="invite-role">Role</Label>
                        <select
                            id="invite-role"
                            className="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                            value={inviteForm.data.role_id}
                            onChange={(e) => inviteForm.setData('role_id', Number(e.target.value))}
                        >
                            {availableRoles.map((role) => (
                                <option key={role.id} value={role.id}>
                                    {role.label} ({role.name})
                                </option>
                            ))}
                        </select>
                        <InputError message={inviteForm.errors.role_id} />
                    </div>
                    <Button type="submit" disabled={inviteForm.processing}>Invite</Button>
                </form>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {teamMembers.length === 0 ? (
                        <p className="p-4 text-sm text-gray-600">No team members yet.</p>
                    ) : (
                        teamMembers.map((member) => (
                            <div
                                key={member.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <p className="font-medium text-gray-900">{member.name}</p>
                                    <p className="text-sm text-gray-500">{member.email}</p>
                                </div>
                                <div className="flex flex-wrap items-center gap-2">
                                    <select
                                        className="rounded-md border border-gray-300 text-sm"
                                        value={member.role_id}
                                        onChange={(e) =>
                                            updateRole(member.id, Number(e.target.value))
                                        }
                                    >
                                        {availableRoles.map((role) => (
                                            <option key={role.id} value={role.id}>
                                                {role.name}
                                            </option>
                                        ))}
                                    </select>
                                    <button
                                        type="button"
                                        className="text-sm text-red-600 hover:text-red-800"
                                        onClick={() => removeMember(member.id)}
                                    >
                                        Remove
                                    </button>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
