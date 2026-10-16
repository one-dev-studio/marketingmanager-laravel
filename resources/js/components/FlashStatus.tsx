import { usePage } from '@inertiajs/react';

interface FlashProps {
    success?: string | null;
    error?: string | null;
    status?: string | null;
    info?: string | null;
    recoveryCodes?: string[] | null;
}

export function FlashStatus() {
    const { flash } = usePage().props as { flash?: FlashProps };
    const message = flash?.success ?? flash?.status ?? flash?.info;
    const messageTone =
        flash?.error ? 'error' : flash?.info ? 'info' : message ? 'success' : null;

    if (!messageTone && !flash?.recoveryCodes?.length) {
        return null;
    }

    return (
        <div className="mb-4 space-y-3">
            {flash?.error ? (
                <div className="rounded-md bg-red-50 p-4 text-sm text-red-700">{flash.error}</div>
            ) : null}
            {message && messageTone !== 'error' ? (
                <div
                    className={
                        messageTone === 'info'
                            ? 'rounded-md bg-blue-50 p-4 text-sm text-blue-800'
                            : 'rounded-md bg-green-50 p-4 text-sm text-green-700'
                    }
                >
                    {message}
                </div>
            ) : null}
            {flash?.recoveryCodes?.length ? (
                <div className="rounded-md border border-amber-200 bg-amber-50 p-4 text-sm text-amber-900">
                    <p className="font-medium">Save your recovery codes</p>
                    <ul className="mt-2 grid gap-1 font-mono text-xs sm:grid-cols-2">
                        {flash.recoveryCodes.map((code) => (
                            <li key={code}>{code}</li>
                        ))}
                    </ul>
                </div>
            ) : null}
        </div>
    );
}
