import type { CampaignDetail, CampaignScheduledPostItem } from '@/types/campaigns';
import { useMemo } from 'react';

type MilestoneStatus = 'completed' | 'in_progress' | 'pending';

interface TimelineMilestone {
    title: string;
    description: string;
    date: string;
    status: MilestoneStatus;
    type: 'event' | 'goal' | 'posts';
    current_value?: number;
    target_value?: number;
    posts?: CampaignScheduledPostItem[];
}

function toNumber(value: string | number | null | undefined): number {
    if (value === null || value === undefined) {
        return 0;
    }
    const parsed = typeof value === 'number' ? value : Number(value);
    return Number.isFinite(parsed) ? parsed : 0;
}

function formatDateRange(start: string | null, end: string | null): string {
    if (!start || !end) {
        return 'Dates not set';
    }
    const startDate = new Date(start).toLocaleDateString();
    const endDate = new Date(end).toLocaleDateString();
    return `${startDate} – ${endDate}`;
}

function formatDateTime(date: string): string {
    return new Date(date).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit',
    });
}

function statusBadgeClass(status: string): string {
    const normalized = status.toLowerCase();
    const classes: Record<string, string> = {
        active: 'bg-green-100 text-green-800',
        completed: 'bg-blue-100 text-blue-800',
        paused: 'bg-yellow-100 text-yellow-800',
        draft: 'bg-gray-100 text-gray-800',
        pending: 'bg-gray-100 text-gray-800',
        in_progress: 'bg-primary-100 text-primary-800',
    };
    return classes[normalized] ?? 'bg-gray-100 text-gray-800';
}

function milestoneDotClass(status: MilestoneStatus): string {
    const classes: Record<MilestoneStatus, string> = {
        completed: 'border-green-500',
        in_progress: 'border-primary-500',
        pending: 'border-gray-300',
    };
    return classes[status];
}

function truncate(text: string, length: number): string {
    if (!text) {
        return '';
    }
    return text.length > length ? `${text.substring(0, length)}…` : text;
}

function buildTimeline(campaign: CampaignDetail): TimelineMilestone[] {
    const milestones: TimelineMilestone[] = [];
    const status = campaign.status.toLowerCase();

    if (campaign.start_date) {
        milestones.push({
            title: 'Campaign started',
            description: `Campaign "${campaign.name}" was launched`,
            date: campaign.start_date,
            status: status === 'active' || status === 'completed' ? 'completed' : 'pending',
            type: 'event',
        });
    }

    campaign.goals.forEach((goal) => {
        const current = toNumber(goal.current_value);
        const target = toNumber(goal.target_value);
        milestones.push({
            title: goal.name,
            description: goal.description ?? `Target: ${target}`,
            date: goal.target_date ?? campaign.end_date ?? campaign.start_date ?? new Date().toISOString(),
            status:
                current >= target && target > 0
                    ? 'completed'
                    : status === 'active'
                      ? 'in_progress'
                      : 'pending',
            type: 'goal',
            current_value: current,
            target_value: target,
        });
    });

    const postsByDate: Record<string, CampaignScheduledPostItem[]> = {};
    campaign.scheduled_posts.forEach((post) => {
        if (!post.scheduled_at) {
            return;
        }
        const key = new Date(post.scheduled_at).toDateString();
        if (!postsByDate[key]) {
            postsByDate[key] = [];
        }
        postsByDate[key].push(post);
    });

    Object.entries(postsByDate).forEach(([dateKey, posts]) => {
        milestones.push({
            title: `Scheduled posts – ${new Date(dateKey).toLocaleDateString()}`,
            description: `${posts.length} posts scheduled`,
            date: posts[0]?.scheduled_at ?? dateKey,
            status: new Date(dateKey) < new Date() ? 'completed' : 'pending',
            type: 'posts',
            posts,
        });
    });

    if (campaign.end_date) {
        milestones.push({
            title: 'Campaign end',
            description: `Campaign "${campaign.name}" ended`,
            date: campaign.end_date,
            status: status === 'completed' ? 'completed' : 'pending',
            type: 'event',
        });
    }

    return milestones.sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime());
}

export interface CampaignTimelineProps {
    campaign: CampaignDetail;
}

export default function CampaignTimeline({ campaign }: CampaignTimelineProps) {
    const timeline = useMemo(() => buildTimeline(campaign), [campaign]);

    return (
        <div className="mx-auto max-w-5xl">
            <div className="mb-8">
                <h2 className="mb-2 text-3xl font-bold text-gray-900">{campaign.name}</h2>
                <div className="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                    <span className={`rounded-full px-2 py-1 text-xs font-medium ${statusBadgeClass(campaign.status)}`}>
                        {campaign.status}
                    </span>
                    <span>{formatDateRange(campaign.start_date, campaign.end_date)}</span>
                    {campaign.brand_name ? <span>Brand: {campaign.brand_name}</span> : null}
                </div>
                {campaign.description ? (
                    <p className="mt-3 text-sm text-gray-600">{campaign.description}</p>
                ) : null}
            </div>

            <div className="relative">
                <div className="absolute bottom-0 left-4 top-0 w-0.5 bg-gray-200" aria-hidden />
                {timeline.length === 0 ? (
                    <p className="py-8 text-center text-gray-500">No timeline milestones yet.</p>
                ) : (
                    timeline.map((milestone, index) => (
                        <div key={`${milestone.title}-${index}`} className="relative mb-8 flex items-start">
                            <div
                                className={`relative z-10 flex h-8 w-8 items-center justify-center rounded-full border-2 bg-white ${milestoneDotClass(milestone.status)}`}
                            >
                                {milestone.status === 'completed' ? (
                                    <div className="h-3 w-3 rounded-full bg-green-500" />
                                ) : milestone.status === 'in_progress' ? (
                                    <div className="h-3 w-3 animate-pulse rounded-full bg-primary-500" />
                                ) : null}
                            </div>
                            <div className="ml-6 flex-1">
                                <div className="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
                                    <div className="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <div className="flex-1">
                                            <h3 className="text-lg font-semibold text-gray-900">{milestone.title}</h3>
                                            <p className="mb-3 text-sm text-gray-600">{milestone.description}</p>
                                            {milestone.type === 'goal' &&
                                            milestone.target_value !== undefined &&
                                            milestone.target_value > 0 ? (
                                                <div className="mt-4">
                                                    <div className="mb-2 flex items-center justify-between text-sm text-gray-600">
                                                        <span>Progress</span>
                                                        <span className="font-medium">
                                                            {milestone.current_value} / {milestone.target_value}
                                                        </span>
                                                    </div>
                                                    <div className="h-2 w-full rounded-full bg-gray-200">
                                                        <div
                                                            className="h-2 rounded-full bg-primary-600 transition-all"
                                                            style={{
                                                                width: `${Math.min(
                                                                    100,
                                                                    ((milestone.current_value ?? 0) /
                                                                        milestone.target_value) *
                                                                        100,
                                                                )}%`,
                                                            }}
                                                        />
                                                    </div>
                                                </div>
                                            ) : null}
                                            {milestone.posts && milestone.posts.length > 0 ? (
                                                <div className="mt-4 space-y-2">
                                                    <p className="text-sm font-medium text-gray-700">
                                                        Scheduled posts ({milestone.posts.length})
                                                    </p>
                                                    {milestone.posts.map((post) => (
                                                        <div
                                                            key={post.id}
                                                            className="flex items-center justify-between rounded bg-gray-50 p-2"
                                                        >
                                                            <span className="text-sm text-gray-900">
                                                                {truncate(post.content, 50)}
                                                            </span>
                                                            <span className="text-xs text-gray-500">
                                                                {post.scheduled_at
                                                                    ? formatDateTime(post.scheduled_at)
                                                                    : '—'}
                                                            </span>
                                                        </div>
                                                    ))}
                                                </div>
                                            ) : null}
                                        </div>
                                        <div className="text-right text-xs text-gray-500 sm:ml-4">
                                            <div>{formatDateTime(milestone.date)}</div>
                                            <span
                                                className={`mt-1 inline-block rounded-full px-2 py-1 text-xs font-medium ${statusBadgeClass(milestone.status)}`}
                                            >
                                                {milestone.status}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );
}
