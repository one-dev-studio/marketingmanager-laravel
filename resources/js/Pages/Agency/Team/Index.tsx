import { Button } from '@/components/ui/button';
import AgencyLayout from '@/Layouts/AgencyLayout';
import { router, useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface TeamMemberItem {
    id: number;
    name: string;
    role: string;
}

export interface AgencyTeamIndexProps {
    agencyId: string;
    teamMembers: TeamMemberItem[];
}

export default function Index({ agencyId, teamMembers }: AgencyTeamIndexProps) {
    const form = useForm({ user_id: '', role: 'member' });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('agency.team.store', { agency: agencyId }), {
            preserveScroll: true,
            onSuccess: () => form.reset('user_id'),
        });
    };

    const removeMember = (userId: number) => {
        router.delete(route('agency.team.destroy', { agency: agencyId, user: userId }), {
            preserveScroll: true,
        });
    };

    return (
        <AgencyLayout title="Team">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Agency team</h1>
                <form
                    onSubmit={submit}
                    className="flex flex-wrap gap-3 rounded-lg border border-gray-200 bg-white p-4"
                >
                    <input
                        name="user_id"
                        required
                        placeholder="User ID"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.user_id}
                        onChange={(e) => form.setData('user_id', e.target.value)}
                    />
                    <select
                        name="role"
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm"
                        value={form.data.role}
                        onChange={(e) => form.setData('role', e.target.value)}
                    >
                        <option value="member">Agency Member</option>
                        <option value="admin">Agency Admin</option>
                    </select>
                    <Button type="submit" disabled={form.processing}>Invite / add</Button>
                </form>
                <div className="divide-y divide-gray-100 rounded-lg border border-gray-200 bg-white">
                    {teamMembers.length === 0 ? (
                        <p className="p-8 text-center text-gray-500">No team members yet.</p>
                    ) : (
                        teamMembers.map((member) => (
                            <div key={member.id} className="flex items-start justify-between p-4">
                                <div>
                                    <p className="font-medium text-gray-900">{member.name}</p>
                                    <p className="text-sm text-gray-500">{member.role}</p>
                                </div>
                                <button
                                    type="button"
                                    className="text-sm text-red-600"
                                    onClick={() => removeMember(member.id)}
                                >
                                    Remove
                                </button>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AgencyLayout>
    );
}
