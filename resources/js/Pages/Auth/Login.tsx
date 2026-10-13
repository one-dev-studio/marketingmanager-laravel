import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface LoginProps {
    canResetPassword: boolean;
}

export default function Login({ canResetPassword }: LoginProps) {
    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('login'));
    };

    return (
        <GuestLayout title="Sign In" subtitle="Sign in to your account">
            <form onSubmit={submit} className="space-y-5">
                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        autoComplete="username"
                        value={form.data.email}
                        onChange={(e) => form.setData('email', e.target.value)}
                        required
                        autoFocus
                        className="mt-1"
                    />
                    <InputError message={form.errors.email} />
                </div>

                <div>
                    <Label htmlFor="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        autoComplete="current-password"
                        value={form.data.password}
                        onChange={(e) => form.setData('password', e.target.value)}
                        required
                        className="mt-1"
                    />
                    <InputError message={form.errors.password} />
                </div>

                <div className="flex items-center justify-between">
                    <label className="flex items-center text-sm text-gray-600">
                        <input
                            type="checkbox"
                            checked={form.data.remember}
                            onChange={(e) => form.setData('remember', e.target.checked)}
                            className="rounded border-gray-300 text-primary-600"
                        />
                        <span className="ml-2">Remember me</span>
                    </label>
                    {canResetPassword ? (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-primary-600 hover:text-primary-500"
                        >
                            Forgot password?
                        </Link>
                    ) : null}
                </div>

                <Button type="submit" className="w-full" disabled={form.processing}>
                    Sign In
                </Button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-600">
                Don&apos;t have an account?{' '}
                <Link href={route('register')} className="text-primary-600 hover:text-primary-500">
                    Create one
                </Link>
            </p>
        </GuestLayout>
    );
}
