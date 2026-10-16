import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export default function AdminLogin() {
    const form = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('admin.login'));
    };

    return (
        <GuestLayout title="Admin Sign In" subtitle="Platform administrator access">
            <form onSubmit={submit} className="space-y-5">
                <div>
                    <Label htmlFor="email">Admin email</Label>
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

                <div className="flex items-center">
                    <label className="flex items-center text-sm text-gray-600">
                        <input
                            type="checkbox"
                            checked={form.data.remember}
                            onChange={(e) => form.setData('remember', e.target.checked)}
                            className="rounded border-gray-300 text-primary-600"
                        />
                        <span className="ml-2">Remember me</span>
                    </label>
                </div>

                <Button
                    type="submit"
                    className="w-full bg-gray-900 hover:bg-gray-800"
                    disabled={form.processing}
                >
                    Admin sign in
                </Button>
            </form>
        </GuestLayout>
    );
}
