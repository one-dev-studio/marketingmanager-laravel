import { usePage } from '@inertiajs/react';

interface FlashProps {
    success?: string | null;
    error?: string | null;
    status?: string | null;
}

export function FlashStatus() {
    const { flash } = usePage().props as { flash?: FlashProps };
    const message = flash?.success ?? flash?.status;

    if (!message && !flash?.error) {
        return null;
    }

    if (flash?.error) {
        return (
            <div className="mb-4 rounded-md bg-red-50 p-4 text-sm text-red-700">{flash.error}</div>
        );
    }

    return (
        <div className="mb-4 rounded-md bg-green-50 p-4 text-sm text-green-700">{message}</div>
    );
}
