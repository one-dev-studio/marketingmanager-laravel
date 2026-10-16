import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ChatbotListItem {
    id: number;
    name: string;
    description: string | null;
    is_active: boolean;
    conversations_count: number;
    leads_count: number;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedChatbots {
    data: ChatbotListItem[];
    links: PaginationLink[];
}

export interface ChatbotsIndexProps {
    organizationId: string;
    chatbots: PaginatedChatbots;
    can: {
        create: boolean;
    };
}

export default function Index({ organizationId, chatbots, can }: ChatbotsIndexProps) {
    const items = chatbots.data ?? [];

    return (
        <AppLayout title="Chatbots">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">Chatbots</h1>
                        <p className="mt-1 text-sm text-gray-600">
                            Create and manage AI-powered chatbots for your website
                        </p>
                    </div>
                    {can.create ? (
                        <Link href={route('main.chatbots.builder', { organizationId })}>
                            <Button type="button">Create chatbot</Button>
                        </Link>
                    ) : null}
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <div className="p-8 text-center text-gray-500">No chatbots yet.</div>
                    ) : (
                        items.map((chatbot) => (
                            <div
                                key={chatbot.id}
                                className="flex flex-col gap-3 border-b border-gray-100 p-4 last:border-b-0 sm:flex-row sm:items-center sm:justify-between"
                            >
                                <div>
                                    <h2 className="font-medium text-gray-900">{chatbot.name}</h2>
                                    <p className="text-sm text-gray-500">
                                        {chatbot.is_active ? 'Active' : 'Inactive'} ·{' '}
                                        {chatbot.conversations_count} conversations · {chatbot.leads_count} leads
                                    </p>
                                </div>
                                <div className="flex flex-wrap gap-3 text-sm">
                                    <Link
                                        href={route('main.chatbots.builder', {
                                            organizationId,
                                            id: chatbot.id,
                                        })}
                                        className="font-medium text-primary-700 hover:text-primary-800"
                                    >
                                        Builder
                                    </Link>
                                    <Link
                                        href={route('main.chatbots.deployment', {
                                            organizationId,
                                            id: chatbot.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Deployment
                                    </Link>
                                    <Link
                                        href={route('main.chatbots.analytics', {
                                            organizationId,
                                            id: chatbot.id,
                                        })}
                                        className="text-gray-700 hover:text-gray-900"
                                    >
                                        Analytics
                                    </Link>
                                </div>
                            </div>
                        ))
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
