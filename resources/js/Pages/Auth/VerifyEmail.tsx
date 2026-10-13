import { Button } from '@/components/ui/button';
import GuestLayout from '@/Layouts/GuestLayout';
import { useForm } from '@inertiajs/react';
import type { FormEvent } from 'react';
import { route } from 'ziggy-js';

export interface VerifyEmailProps {
    status: string | null;
}

export default function VerifyEmail({ status }: VerifyEmailProps) {
    const form = useForm({});

    const logoutForm = useForm({});

    const resend = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('verification.send'));
    };

    return (
        <GuestLayout title="Verify email" subtitle="Check your inbox">
            <p className="text-sm text-gray-600">
                Thanks for signing up. Before getting started, please verify your email address by
                clicking the link we sent you. If you did not receive the email, we can send another.
            </p>

            {status === 'verification-link-sent' ? (
                <p className="mt-4 text-sm font-medium text-green-700">
                    A new verification link has been sent to your email address.
                </p>
            ) : null}

            <form onSubmit={resend} className="mt-6">
                <Button type="submit" disabled={form.processing}>
                    Resend verification email
                </Button>
            </form>

            <p className="mt-6 text-center text-sm text-gray-600">
                <button
                    type="button"
                    onClick={() => logoutForm.post(route('logout'))}
                    className="text-primary-600 hover:text-primary-500"
                    disabled={logoutForm.processing}
                >
                    Log out
                </button>
            </p>
        </GuestLayout>
    );
}
