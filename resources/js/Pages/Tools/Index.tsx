import AppLayout from '@/Layouts/AppLayout';
import type { ToolHubItem } from '@/types/tools';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ToolsIndexProps {
    organizationId: string;
    tools: ToolHubItem[];
}

export default function Index({ organizationId, tools }: ToolsIndexProps) {
    return (
        <AppLayout title="Content Ideation">
            <div className="space-y-6">
                <h1 className="text-2xl font-semibold text-gray-900">Content Ideation</h1>
                <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                    {tools.map((tool) => (
                        <Link
                            key={tool.routeName}
                            href={route(tool.routeName, { organizationId })}
                            className="rounded-lg border border-gray-200 bg-white p-4 transition hover:border-indigo-400"
                        >
                            <h2 className="font-medium text-gray-900">{tool.title}</h2>
                            <p className="mt-1 text-sm text-gray-500">{tool.description}</p>
                        </Link>
                    ))}
                </div>
            </div>
        </AppLayout>
    );
}
