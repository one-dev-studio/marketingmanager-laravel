import { Button } from '@/components/ui/button';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

export interface ChatbotBuilderData {
    id: number | null;
    name: string;
    description: string;
    welcome_message: string;
    training_data: unknown[];
    settings: Record<string, unknown>;
    is_active: boolean;
    can: {
        update: boolean;
    };
}

export interface ChatbotBuilderPanelProps {
    organizationId: string;
    chatbot: ChatbotBuilderData;
}

export function ChatbotBuilderPanel({ organizationId, chatbot }: ChatbotBuilderPanelProps) {
    const [name, setName] = useState(chatbot.name);
    const [description, setDescription] = useState(chatbot.description);
    const [welcomeMessage, setWelcomeMessage] = useState(chatbot.welcome_message);
    const [isActive, setIsActive] = useState(chatbot.is_active);
    const [chatbotId, setChatbotId] = useState<number | null>(chatbot.id);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState<string | null>(null);

    const save = async () => {
        if (!chatbot.can.update) {
            return;
        }
        setSaving(true);
        setMessage(null);
        const payload = {
            name,
            description,
            welcome_message: welcomeMessage,
            training_data: chatbot.training_data,
            settings: chatbot.settings,
            is_active: isActive,
        };

        try {
            if (chatbotId) {
                await axios.put(
                    route('main.chatbots.update', { organizationId, chatbot: chatbotId }),
                    payload,
                );
                setMessage('Chatbot saved.');
            } else {
                const { data } = await axios.post(
                    route('main.chatbots.store', { organizationId }),
                    payload,
                );
                const id = data?.data?.id as number | undefined;
                if (id) {
                    setChatbotId(id);
                }
                setMessage('Chatbot created.');
            }
        } catch (error: unknown) {
            const errMessage =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Save failed.';
            setMessage(errMessage);
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="space-y-4 rounded-lg border border-gray-200 bg-white p-6">
            <label className="block text-sm">
                <span className="font-medium text-gray-700">Name</span>
                <input
                    type="text"
                    value={name}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setName(event.target.value)}
                />
            </label>
            <label className="block text-sm">
                <span className="font-medium text-gray-700">Description</span>
                <textarea
                    value={description}
                    rows={2}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setDescription(event.target.value)}
                />
            </label>
            <label className="block text-sm">
                <span className="font-medium text-gray-700">Welcome message</span>
                <textarea
                    value={welcomeMessage}
                    rows={3}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    onChange={(event) => setWelcomeMessage(event.target.value)}
                />
            </label>
            <label className="flex items-center gap-2 text-sm text-gray-700">
                <input
                    type="checkbox"
                    checked={isActive}
                    onChange={(event) => setIsActive(event.target.checked)}
                />
                Active
            </label>
            <Button type="button" disabled={saving || !chatbot.can.update} onClick={() => void save()}>
                {saving ? 'Saving…' : 'Save chatbot'}
            </Button>
            {message ? <p className="text-sm text-gray-600">{message}</p> : null}
        </div>
    );
}
