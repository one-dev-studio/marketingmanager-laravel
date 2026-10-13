import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MinimalAuthenticatedLayout from '@/Layouts/MinimalAuthenticatedLayout';
import { Link, router, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface SecuritySession {
    session_id: string;
    browser: string | null;
    ip_address: string | null;
    last_activity: string | null;
    is_current: boolean;
}

export interface ProfileSecurityProps {
    twoFactorEnabled: boolean;
    sessions: SecuritySession[];
}

export default function Security({ twoFactorEnabled, sessions }: ProfileSecurityProps) {
    const disableForm = useForm({
        password: '',
    });

    const submitDisable = (event: FormEvent) => {
        event.preventDefault();
        disableForm.post(route('two-factor.disable'));
    };

    const revokeSession = (sessionId: string) => {
        router.delete(route('profile.sessions.revoke', { sessionId }));
    };

    const revokeOtherSessions = () => {
        router.post(route('profile.sessions.revoke-other'));
    };

    return (
        <MinimalAuthenticatedLayout title="Security">
            <div className="space-y-6">
                <div className="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Security</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Two-factor authentication and active sessions.
                        </p>
                    </div>
                    <Link
                        href={route('profile.show')}
                        className="text-sm text-primary-600 hover:text-primary-700"
                    >
                        Back to profile
                    </Link>
                </div>

                <section className="rounded-lg border border-gray-200 bg-white p-6">
                    <h2 className="font-medium text-gray-900">Two-factor authentication</h2>
                    <p className="mt-1 text-sm text-gray-600">
                        {twoFactorEnabled
                            ? 'Two-factor authentication is enabled on your account.'
                            : 'Add an extra layer of security with an authenticator app.'}
                    </p>
                    {twoFactorEnabled ? (
                        <form onSubmit={submitDisable} className="mt-4 space-y-4">
                            <div>
                                <Label htmlFor="disable_password">Confirm password to disable</Label>
                                <Input
                                    id="disable_password"
                                    type="password"
                                    value={disableForm.data.password}
                                    onChange={(e) => disableForm.setData('password', e.target.value)}
                                    required
                                    className="mt-1 max-w-md"
                                />
                                <InputError message={disableForm.errors.password} />
                            </div>
                            <Button type="submit" variant="outline" disabled={disableForm.processing}>
                                Disable 2FA
                            </Button>
                        </form>
                    ) : (
                        <Link
                            href={route('two-factor.setup')}
                            className="mt-4 inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700"
                        >
                            Set up two-factor authentication
                        </Link>
                    )}
                </section>

                <section className="rounded-lg border border-gray-200 bg-white p-6">
                    <div className="flex flex-wrap items-center justify-between gap-3">
                        <h2 className="font-medium text-gray-900">Active sessions</h2>
                        {sessions.length > 1 ? (
                            <Button type="button" variant="outline" onClick={revokeOtherSessions}>
                                Sign out other sessions
                            </Button>
                        ) : null}
                    </div>
                    {sessions.length === 0 ? (
                        <p className="mt-3 text-sm text-gray-600">No active sessions recorded.</p>
                    ) : (
                        <ul className="mt-4 divide-y divide-gray-100">
                            {sessions.map((session) => (
                                <li
                                    key={session.session_id}
                                    className="flex flex-wrap items-center justify-between gap-3 py-3 text-sm"
                                >
                                    <div className="text-gray-700">
                                        <span className="font-medium">
                                            {session.browser ?? 'Unknown browser'}
                                        </span>
                                        {session.is_current ? (
                                            <span className="ml-2 rounded bg-green-100 px-2 py-0.5 text-xs text-green-800">
                                                This device
                                            </span>
                                        ) : null}
                                        <p className="text-gray-500">
                                            {session.ip_address ?? '—'} · {session.last_activity ?? '—'}
                                        </p>
                                    </div>
                                    {!session.is_current ? (
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => revokeSession(session.session_id)}
                                        >
                                            Revoke
                                        </Button>
                                    ) : null}
                                </li>
                            ))}
                        </ul>
                    )}
                </section>
            </div>
        </MinimalAuthenticatedLayout>
    );
}
