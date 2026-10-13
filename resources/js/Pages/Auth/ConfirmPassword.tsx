import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export default function ConfirmPassword() {
    const form = useForm({
        password: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('password.confirm.store'));
    };

    return (
        <GuestLayout title="Confirm password" subtitle="This is a secure area">
            <p className="mb-4 text-sm text-gray-600">
                Please confirm your password before continuing.
            </p>

            <form onSubmit={submit} className="space-y-5">
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

                <Button type="submit" className="w-full" disabled={form.processing}>
                    Confirm
                </Button>
            </form>
        </GuestLayout>
    );
}
