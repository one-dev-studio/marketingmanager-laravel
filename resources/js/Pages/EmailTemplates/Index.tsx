import { Button } from '@/components/ui/button';
import { paginationLabelText } from '@/lib/paginationLabel';
import AppLayout from '@/Layouts/AppLayout';
import type { EmailTemplateListItem } from '@/types/email-templates';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedTemplates {
    data: EmailTemplateListItem[];
    links: PaginationLink[];
}

export interface EmailTemplatesIndexProps {
    organizationId: string;
    templates: PaginatedTemplates;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, templates, can }: EmailTemplatesIndexProps) {
    const items = templates.data ?? [];

    return (
        <AppLayout title="Email Templates">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">Email Templates</h1>
                    {can.create ? (
                        <Link
                            href={route('main.email-marketing.templates.builder', { organizationId })}
                        >
                            <Button type="button">Builder</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No templates.</div>
                    ) : (
                        items.map((template) => (
                            <Link
                                key={template.id}
                                href={route('main.email-marketing.templates.builder', {
                                    organizationId,
                                    emailTemplate: template.id,
                                })}
                                className="block border-b border-gray-100 p-4 last:border-b-0 hover:bg-gray-50"
                            >
                                {template.name} · {template.subject}
                            </Link>
                        ))
                    )}
                </div>

                {templates.links?.length ? (
                    <div className="flex flex-wrap gap-2 text-sm">
                        {templates.links.map((link) =>
                            link.url ? (
                                <Link
                                    key={link.label}
                                    href={link.url}
                                    className={
                                        link.active
                                            ? 'font-medium text-primary-700'
                                            : 'text-gray-600 hover:text-gray-900'
                                    }
                                    preserveScroll
                                >
                                    <span>{paginationLabelText(link.label)}</span>
                                </Link>
                            ) : (
                                <span
                                    key={link.label}
                                    className="text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }} />
                            ),
                        )}
                    </div>
                ) : null}
            </div>
        </AppLayout>
    );
}
