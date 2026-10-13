import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface ResetPasswordProps {
    token: string;
    email: string;
}

export default function ResetPassword({ token, email }: ResetPasswordProps) {
    const form = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('password.update'));
    };

    return (
        <GuestLayout title="Reset password" subtitle="Choose a new password">
            <form onSubmit={submit} className="space-y-5">
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
                        autoFocus
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
                    Reset password
                </Button>
            </form>
        </GuestLayout>
    );
}
