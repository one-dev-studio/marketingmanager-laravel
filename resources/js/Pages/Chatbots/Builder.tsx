import { ChatbotBuilderPanel, type ChatbotBuilderData } from '@/components/chatbots/ChatbotBuilderPanel';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';

export interface ChatbotsBuilderProps {
    organizationId: string;
    chatbot: ChatbotBuilderData;
}

export default function Builder({ organizationId, chatbot }: ChatbotsBuilderProps) {
    const title = chatbot.id ? `Edit — ${chatbot.name}` : 'Chatbot builder';

    return (
        <AppLayout title={title}>
            <div className="space-y-6">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                    <Link
                        href={route('main.chatbots.index', { organizationId })}
                        className="text-sm text-gray-600 hover:text-gray-900"
                    >
                        Back to list
                    </Link>
                </div>
                <ChatbotBuilderPanel organizationId={organizationId} chatbot={chatbot} />
            </div>
        </AppLayout>
    );
}
