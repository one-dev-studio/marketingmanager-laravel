import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { AuthDivider } from '@/components/AuthDivider';
import { GoogleSignInButton } from '@/components/GoogleSignInButton';
import GuestLayout from '@/Layouts/GuestLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface RegisterProps {
    googleAuthEnabled: boolean;
}

export default function Register({ googleAuthEnabled }: RegisterProps) {
    const form = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('register'));
    };

    return (
        <GuestLayout title="Create Account" subtitle="Start your free trial">
            {googleAuthEnabled ? (
                <>
                    <GoogleSignInButton />
                    <AuthDivider />
                </>
            ) : null}
            <form onSubmit={submit} className="space-y-5">
                <div>
                    <Label htmlFor="name">Name</Label>
                    <Input
                        id="name"
                        value={form.data.name}
                        onChange={(e) => form.setData('name', e.target.value)}
                        required
                        autoFocus
                        className="mt-1"
                    />
                    <InputError message={form.errors.name} />
                </div>

                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={form.data.email}
                        onChange={(e) => form.setData('email', e.target.value)}
                        required
                        className="mt-1"
                    />
                    <InputError message={form.errors.email} />
                </div>

                <div>
                    <Label htmlFor="password">Password</Label>
                    <Input
                        id="password"
                        type="password"
                        value={form.data.password}
                        onChange={(e) => form.setData('password', e.target.value)}
                        required
                        className="mt-1"
                    />
                    <InputError message={form.errors.password} />
                </div>

                <div>
                    <Label htmlFor="password_confirmation">Confirm Password</Label>
                    <Input
                        id="password_confirmation"
                        type="password"
                        value={form.data.password_confirmation}
                        onChange={(e) => form.setData('password_confirmation', e.target.value)}
                        required
                        className="mt-1"
                    />
                </div>

                <Button type="submit" className="w-full" disabled={form.processing}>
                    Create Account
                </Button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-600">
                Already have an account?{' '}
                <Link href={route('login')} className="text-primary-600 hover:text-primary-500">
                    Sign in
                </Link>
            </p>
        </GuestLayout>
    );
}
