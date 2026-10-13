import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { useState } from 'react';
import { route } from 'ziggy-js';

export default function Challenge() {
    const [useRecoveryCode, setUseRecoveryCode] = useState(false);

    const form = useForm({
        code: '',
        recovery_code: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('two-factor.verify'));
    };

    return (
        <GuestLayout title="Two-factor challenge" subtitle="Confirm your identity to continue">
            <form onSubmit={submit} className="space-y-5">
                {useRecoveryCode ? (
                    <div>
                        <Label htmlFor="recovery_code">Recovery code</Label>
                        <Input
                            id="recovery_code"
                            value={form.data.recovery_code}
                            onChange={(e) => form.setData('recovery_code', e.target.value)}
                            required
                            autoFocus
                            className="mt-1 font-mono uppercase"
                        />
                        <InputError message={form.errors.code} />
                    </div>
                ) : (
                    <div>
                        <Label htmlFor="code">Authentication code</Label>
                        <Input
                            id="code"
                            inputMode="numeric"
                            autoComplete="one-time-code"
                            value={form.data.code}
                            onChange={(e) => form.setData('code', e.target.value)}
                            required
                            autoFocus
                            className="mt-1 font-mono tracking-widest"
                        />
                        <InputError message={form.errors.code} />
                    </div>
                )}

                <Button type="submit" className="w-full" disabled={form.processing}>
                    Verify
                </Button>

                <button
                    type="button"
                    className="w-full text-center text-sm text-primary-600 hover:text-primary-500"
                    onClick={() => setUseRecoveryCode((value) => !value)}
                >
                    {useRecoveryCode ? 'Use authenticator code instead' : 'Use a recovery code instead'}
                </button>
            </form>
        </GuestLayout>
    );
}
