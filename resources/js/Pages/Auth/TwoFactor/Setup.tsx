import { InputError } from '@/components/InputError';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MinimalAuthenticatedLayout from '@/Layouts/MinimalAuthenticatedLayout';
import { Link, useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface TwoFactorSetupProps {
    secret: string;
    qrCodeUrl: string;
}

function qrCodeImageUrl(otpAuthUrl: string): string {
    return `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(otpAuthUrl)}`;
}

export default function Setup({ secret, qrCodeUrl }: TwoFactorSetupProps) {
    const form = useForm({
        secret,
        code: '',
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('two-factor.enable'));
    };

    return (
        <MinimalAuthenticatedLayout title="Two-factor authentication">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">Enable two-factor authentication</h1>
                    <p className="mt-1 text-sm text-gray-600">
                        Scan the QR code with your authenticator app, then enter the 6-digit code to confirm.
                    </p>
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-6">
                    <img
                        src={qrCodeImageUrl(qrCodeUrl)}
                        alt="Authenticator QR code"
                        width={200}
                        height={200}
                        className="mx-auto rounded border border-gray-100"
                    />
                    <p className="mt-4 text-center text-xs text-gray-500">
                        Manual entry key:{' '}
                        <code className="rounded bg-gray-100 px-1 py-0.5 font-mono text-gray-800">{secret}</code>
                    </p>
                </div>

                <form onSubmit={submit} className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
                    <input type="hidden" name="secret" value={form.data.secret} readOnly />
                    <div>
                        <Label htmlFor="code">Verification code</Label>
                        <Input
                            id="code"
                            inputMode="numeric"
                            autoComplete="one-time-code"
                            value={form.data.code}
                            onChange={(e) => form.setData('code', e.target.value.replace(/\D/g, '').slice(0, 6))}
                            required
                            maxLength={6}
                            className="mt-1 font-mono tracking-widest"
                        />
                        <InputError message={form.errors.code} />
                    </div>
                    <div className="flex flex-wrap gap-3">
                        <Button type="submit" disabled={form.processing}>Enable 2FA</Button>
                        <Link
                            href={route('profile.security')}
                            className="inline-flex items-center text-sm text-primary-600 hover:text-primary-700"
                        >
                            Cancel
                        </Link>
                    </div>
                </form>
            </div>
        </MinimalAuthenticatedLayout>
    );
}
