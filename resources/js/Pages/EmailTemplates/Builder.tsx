import { EmailTemplateBuilderPanel } from '@/components/email-templates/EmailTemplateBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import type { EmailTemplateBuilderData } from '@/types/email-templates';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface EmailTemplatesBuilderProps {
    organizationId: string;
    template: EmailTemplateBuilderData;
}

export default function Builder({ organizationId, template }: EmailTemplatesBuilderProps) {
    const title = template.id ? `Edit — ${template.name}` : 'Template builder';

    return (
        <AppLayout title={title}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                    <Link
                        href={route('main.email-marketing.templates.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>
                <EmailTemplateBuilderPanel organizationId={organizationId} template={template} />
            </div>
        </AppLayout>
    );
}
