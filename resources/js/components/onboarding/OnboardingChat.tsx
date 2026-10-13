import { useEffect, useRef, useState } from 'react';
import type { OnboardingActionButton, OnboardingMessage } from '@/types/onboarding';

interface OnboardingChatProps {
    messages: OnboardingMessage[];
    isLoading: boolean;
    actionButtons: OnboardingActionButton[];
    onSendMessage: (message: string) => void;
}

export default function OnboardingChat({
    messages,
    isLoading,
    actionButtons,
    onSendMessage,
}: OnboardingChatProps) {
    const [inputMessage, setInputMessage] = useState('');
    const messagesContainerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const container = messagesContainerRef.current;
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }, [messages, isLoading]);

    const handleSubmit = (event: React.FormEvent) => {
        event.preventDefault();
        const trimmed = inputMessage.trim();
        if (!trimmed || isLoading) {
            return;
        }
        onSendMessage(trimmed);
        setInputMessage('');
    };

    const handleActionButton = (value: string) => {
        onSendMessage(value);
    };

    return (
        <div className="flex h-full flex-col">
            <div ref={messagesContainerRef} className="flex-1 space-y-4 overflow-y-auto p-6">
                {messages.map((message, index) => (
                    <div
                        key={`${message.role}-${index}`}
                        className={`flex items-start space-x-3 ${
                            message.role === 'user' ? 'flex-row-reverse space-x-reverse' : ''
                        }`}
                    >
                        <div
                            className={`flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full ${
                                message.role === 'assistant' ? 'bg-primary-100' : 'bg-gray-200'
                            }`}
                        >
                            {message.role === 'assistant' ? (
                                <svg
                                    className="h-6 w-6 text-primary-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                    />
                                </svg>
                            ) : (
                                <svg
                                    className="h-6 w-6 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            )}
                        </div>
                        <div
                            className={`max-w-[70%] flex-1 rounded-lg px-4 py-3 ${
                                message.role === 'assistant'
                                    ? 'bg-gray-100 text-gray-900'
                                    : 'bg-primary-600 text-white'
                            }`}
                        >
                            <p className="whitespace-pre-wrap text-sm">{message.content}</p>
                        </div>
                    </div>
                ))}

                {isLoading ? (
                    <div className="flex items-start space-x-3">
                        <div className="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-primary-100">
                            <svg
                                className="h-6 w-6 text-primary-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"
                                />
                            </svg>
                        </div>
                        <div className="flex-1 rounded-lg bg-gray-100 px-4 py-3">
                            <div className="flex space-x-1">
                                <div
                                    className="h-2 w-2 animate-bounce rounded-full bg-gray-400"
                                    style={{ animationDelay: '0s' }}
                                />
                                <div
                                    className="h-2 w-2 animate-bounce rounded-full bg-gray-400"
                                    style={{ animationDelay: '0.2s' }}
                                />
                                <div
                                    className="h-2 w-2 animate-bounce rounded-full bg-gray-400"
                                    style={{ animationDelay: '0.4s' }}
                                />
                            </div>
                        </div>
                    </div>
                ) : null}
            </div>

            {actionButtons.length > 0 ? (
                <div className="border-t border-gray-200 bg-gray-50 px-6 py-3">
                    <div className="flex flex-wrap gap-2">
                        {actionButtons.map((button) => (
                            <button
                                key={button.value}
                                type="button"
                                onClick={() => handleActionButton(button.value)}
                                className="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50"
                            >
                                {button.label}
                            </button>
                        ))}
                    </div>
                </div>
            ) : null}

            <div className="border-t border-gray-200 bg-white p-4">
                <form onSubmit={handleSubmit} className="flex space-x-3">
                    <input
                        type="text"
                        value={inputMessage}
                        onChange={(event) => setInputMessage(event.target.value)}
                        placeholder="Type your answer..."
                        className="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:outline-none focus:ring-2 focus:ring-primary-500"
                        disabled={isLoading}
                    />
                    <button
                        type="submit"
                        disabled={!inputMessage.trim() || isLoading}
                        className="rounded-lg bg-primary-600 px-6 py-2 font-medium text-white transition-colors hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        Send
                    </button>
                </form>
            </div>
        </div>
    );
}
