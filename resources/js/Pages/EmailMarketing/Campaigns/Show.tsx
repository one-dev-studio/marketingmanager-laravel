import AppLayout from '@/Layouts/AppLayout';

export interface EmailCampaignDetail {
    id: number;
    name: string;
    subject: string;
    status: string;
    from_email: string;
    from_name: string | null;
    scheduled_at: string | null;
    sent_at: string | null;
    opened_count: number;
    clicked_count: number;
    delivered_count: number;
    total_recipients: number;
    template_name: string | null;
    contact_lists: Array<{ id: number; name: string }>;
}

export interface EmailCampaignsShowProps {
    organizationId: string;
    campaign: EmailCampaignDetail;
}

export default function Show({ campaign }: EmailCampaignsShowProps) {
    return (
        <AppLayout title={campaign.name}>
            <div className="space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">{campaign.name}</h1>
                <p className="text-sm text-gray-600">{campaign.subject}</p>
                <dl className="grid gap-3 rounded-lg border border-gray-200 bg-white p-6 text-sm md:grid-cols-2">
                    <div>
                        <dt className="text-gray-500">Status</dt>
                        <dd className="font-medium text-gray-900">{campaign.status}</dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">From</dt>
                        <dd className="font-medium text-gray-900">
                            {campaign.from_name ? `${campaign.from_name} ` : ''}
                            &lt;{campaign.from_email}&gt;
                        </dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Scheduled</dt>
                        <dd className="font-medium text-gray-900">{campaign.scheduled_at ?? '—'}</dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Sent</dt>
                        <dd className="font-medium text-gray-900">{campaign.sent_at ?? '—'}</dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Recipients</dt>
                        <dd className="font-medium text-gray-900">{campaign.total_recipients}</dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Opens / clicks</dt>
                        <dd className="font-medium text-gray-900">
                            {campaign.opened_count} / {campaign.clicked_count}
                        </dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Template</dt>
                        <dd className="font-medium text-gray-900">{campaign.template_name ?? '—'}</dd>
                    </div>
                    <div>
                        <dt className="text-gray-500">Lists</dt>
                        <dd className="font-medium text-gray-900">
                            {campaign.contact_lists.map((list) => list.name).join(', ') || '—'}
                        </dd>
                    </div>
                </dl>
            </div>
        </AppLayout>
    );
}
