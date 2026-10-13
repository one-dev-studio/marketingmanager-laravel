import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export default function ForgotPassword() {
    const form = useForm({
        email: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('password.email'));
    };

    return (
        <GuestLayout title="Forgot password" subtitle="We will email you a reset link">
            <p className="mb-4 text-sm text-gray-600">
                Enter your email address and we will send you a link to reset your password.
            </p>

            <form onSubmit={submit} className="space-y-5">
                <div>
                    <Label htmlFor="email">Email</Label>
                    <Input
                        id="email"
                        type="email"
                        value={form.data.email}
                        onChange={(e) => form.setData('email', e.target.value)}
                        required
                        autoFocus
                        className="mt-1"
                    />
                    <InputError message={form.errors.email} />
                </div>

                <Button type="submit" className="w-full" disabled={form.processing}>
                    Email reset link
                </Button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-600">
                <Link href={route('login')} className="text-primary-600 hover:text-primary-500">
                    Back to sign in
                </Link>
            </p>
        </GuestLayout>
    );
}
