import { Head, Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';

export interface DemoIndexProps {
    appName: string;
    environment: string;
    laravelVersion: string;
    requestedAt: string;
    healthRoute: string;
}

export default function DemoIndex({
    appName,
    environment,
    laravelVersion,
    requestedAt,
    healthRoute,
}: DemoIndexProps) {
    return (
        <>
            <Head title="Inertia demo" />
            <div className="mx-auto flex min-h-screen max-w-2xl flex-col justify-center gap-6 p-8">
                <div>
                    <p className="text-sm font-medium uppercase tracking-wide text-primary-600">
                        Phase 0 scaffold
                    </p>
                    <h1 className="mt-2 text-3xl font-semibold text-gray-900">
                        {appName} · Inertia + React
                    </h1>
                    <p className="mt-2 text-gray-600">
                        This page proves the new stack coexists with existing Blade and Vue
                        islands. It is not linked from product navigation.
                    </p>
                </div>

                <dl className="grid gap-3 rounded-lg border border-gray-200 bg-white p-4 text-sm">
                    <div className="flex justify-between gap-4">
                        <dt className="text-gray-500">Environment</dt>
                        <dd className="font-mono text-gray-900">{environment}</dd>
                    </div>
                    <div className="flex justify-between gap-4">
                        <dt className="text-gray-500">Laravel</dt>
                        <dd className="font-mono text-gray-900">{laravelVersion}</dd>
                    </div>
                    <div className="flex justify-between gap-4">
                        <dt className="text-gray-500">Requested at</dt>
                        <dd className="font-mono text-gray-900">{requestedAt}</dd>
                    </div>
                </dl>

                <div className="flex flex-wrap gap-3">
                    <Button asChild>
                        <Link href={route('home')}>Back to marketing (Blade)</Link>
                    </Button>
                    <Button variant="outline" asChild>
                        <a href={healthRoute} target="_blank" rel="noreferrer">
                            Laravel health ({healthRoute})
                        </a>
                    </Button>
                </div>
            </div>
        </>
    );
}
