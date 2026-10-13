import CampaignTimeline from '@/components/campaigns/CampaignTimeline';
import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import type { CampaignDetail } from '@/types/campaigns';
import { Link, router } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface CampaignsShowProps {
    organizationId: string;
    campaign: CampaignDetail;
}

export default function Show({ organizationId, campaign }: CampaignsShowProps) {
    const deleteCampaign = () => {
        if (!window.confirm('Delete this campaign?')) {
            return;
        }
        router.delete(
            route('main.campaigns.destroy', { organizationId, campaign: campaign.id }),
        );
    };

    return (
        <AppLayout title={campaign.name}>
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <Link
                        href={campaign.index_href}
                        className="text-sm text-primary-600 hover:text-primary-800"
                    >
                        ← Back to campaigns
                    </Link>
                    <div className="flex items-center gap-3">
                        {campaign.can.delete ? (
                            <button
                                type="button"
                                onClick={deleteCampaign}
                                className="text-sm text-red-600 hover:text-red-800"
                            >
                                Delete
                            </button>
                        ) : null}
                    </div>
                </div>

                <CampaignTimeline campaign={campaign} />

                <div className="mx-auto max-w-5xl rounded-lg border border-gray-200 bg-white p-4 text-sm text-gray-600">
                    <div className="grid gap-2 sm:grid-cols-3">
                        <div>
                            <span className="font-medium text-gray-500">Budget</span>
                            <p className="text-gray-900">${campaign.budget}</p>
                        </div>
                        <div>
                            <span className="font-medium text-gray-500">Spent</span>
                            <p className="text-gray-900">${campaign.spent}</p>
                        </div>
                        <div>
                            <span className="font-medium text-gray-500">Channels</span>
                            <p className="text-gray-900">Manage from campaign actions</p>
                        </div>
                    </div>
                    {campaign.can.update ? (
                        <div className="mt-4">
                            <Link
                                href={route('main.campaigns.index', { organizationId })}
                                className="inline-flex"
                            >
                                <Button type="button" variant="outline">Campaign list</Button>
                            </Link>
                        </div>
                    ) : null}
                </div>
            </div>
        </AppLayout>
    );
}
