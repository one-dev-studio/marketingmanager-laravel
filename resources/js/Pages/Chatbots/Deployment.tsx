import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface ChatbotDeploymentData {
    id: number;
    name: string;
    embed_code: string | null;
    is_active: boolean;
}

export interface ChatbotsDeploymentProps {
    organizationId: string;
    chatbot: ChatbotDeploymentData;
}

export default function Deployment({ organizationId, chatbot }: ChatbotsDeploymentProps) {
    return (
        <AppLayout title={`Deploy — ${chatbot.name}`}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-semibold text-gray-900">{chatbot.name}</h1>
                        <p className="mt-1 text-sm text-gray-600">Deploy your chatbot to websites</p>
                    </div>
                    <Link
                        href={route('main.chatbots.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>

                <div className="rounded-lg border border-gray-200 bg-white p-6 space-y-4">
                    <p className="text-sm text-gray-600">
                        Status: {chatbot.is_active ? 'Active' : 'Inactive'}
                    </p>
                    <div>
                        <h2 className="text-sm font-medium text-gray-900">Embed code</h2>
                        {chatbot.embed_code ? (
                            <pre className="mt-2 whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-xs">
                                {chatbot.embed_code}
                            </pre>
                        ) : (
                            <p className="mt-2 text-sm text-gray-500">
                                No embed code yet. Save the chatbot in the builder to generate one.
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
