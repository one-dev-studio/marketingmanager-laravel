import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MinimalAuthenticatedLayout from '@/Layouts/MinimalAuthenticatedLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ProfileUser {
    name: string;
    email: string;
    timezone: string;
    locale: string | null;
    country_code: string | null;
}

export interface ProfileSession {
    browser: string | null;
    ip_address: string | null;
    last_activity: string | null;
}

export interface ProfileShowProps {
    user: ProfileUser;
    sessions: ProfileSession[];
}

export default function Show({ user, sessions }: ProfileShowProps) {
    const profileForm = useForm({
        name: user.name,
        email: user.email,
        timezone: user.timezone,
        locale: user.locale ?? '',
        country_code: user.country_code ?? '',
    });

    const passwordForm = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submitProfile = (event: FormEvent) => {
        event.preventDefault();
        profileForm.put(route('profile.update'));
    };

    const submitPassword = (event: FormEvent) => {
        event.preventDefault();
        passwordForm.put(route('profile.password.update'));
    };

    return (
        <MinimalAuthenticatedLayout title="Profile">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Profile</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Update your name, email, and password.
                    </p>
                </div>

                <form
                    onSubmit={submitProfile}
                    className="space-y-4 rounded-lg border border-gray-200 bg-white p-6"
                >
                    <div>
                        <Label htmlFor="name">Name</Label>
                        <Input
                            id="name"
                            value={profileForm.data.name}
                            onChange={(e) => profileForm.setData('name', e.target.value)}
                            required
                            className="mt-1"
                        />
                        <InputError message={profileForm.errors.name} />
                    </div>
                    <div>
                        <Label htmlFor="email">Email</Label>
                        <Input
                            id="email"
                            type="email"
                            value={profileForm.data.email}
                            onChange={(e) => profileForm.setData('email', e.target.value)}
                            required
                            className="mt-1"
                        />
                        <InputError message={profileForm.errors.email} />
                    </div>
                    <div>
                        <Label htmlFor="timezone">Timezone</Label>
                        <Input
                            id="timezone"
                            value={profileForm.data.timezone}
                            onChange={(e) => profileForm.setData('timezone', e.target.value)}
                            required
                            className="mt-1"
                        />
                        <InputError message={profileForm.errors.timezone} />
                    </div>
                    <Button type="submit" disabled={profileForm.processing}>Save profile</Button>
                </form>

                <form
                    onSubmit={submitPassword}
                    className="space-y-4 rounded-lg border border-gray-200 bg-white p-6"
                >
                    <h2 className="font-medium text-gray-900">Password</h2>
                    <div>
                        <Label htmlFor="current_password">Current password</Label>
                        <Input
                            id="current_password"
                            type="password"
                            value={passwordForm.data.current_password}
                            onChange={(e) =>
                                passwordForm.setData('current_password', e.target.value)
                            }
                            required
                            className="mt-1"
                        />
                        <InputError message={passwordForm.errors.current_password} />
                    </div>
                    <div>
                        <Label htmlFor="password">New password</Label>
                        <Input
                            id="password"
                            type="password"
                            value={passwordForm.data.password}
                            onChange={(e) => passwordForm.setData('password', e.target.value)}
                            required
                            className="mt-1"
                        />
                        <InputError message={passwordForm.errors.password} />
                    </div>
                    <div>
                        <Label htmlFor="password_confirmation">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            value={passwordForm.data.password_confirmation}
                            onChange={(e) =>
                                passwordForm.setData('password_confirmation', e.target.value)
                            }
                            required
                            className="mt-1"
                        />
                    </div>
                    <Button type="submit" disabled={passwordForm.processing}>Update password</Button>
                </form>

                {sessions.length > 0 ? (
                    <div className="rounded-lg border border-gray-200 bg-white p-6">
                        <h2 className="mb-3 font-medium text-gray-900">Active sessions</h2>
                        <ul className="space-y-1 text-sm text-gray-600">
                            {sessions.map((session, index) => (
                                <li key={`${session.ip_address}-${index}`}>
                                    {session.browser ?? 'Browser'} · {session.ip_address} ·{' '}
                                    {session.last_activity ?? '—'}
                                </li>
                            ))}
                        </ul>
                    </div>
                ) : null}
            </div>
        </MinimalAuthenticatedLayout>
    );
}
