import { useState, type FormEvent, type ReactElement } from 'react';

/**
 * Floating AI assistant entry point (parity with Blade `CommandPopover` Vue island).
 */
export function CommandAssistantFab(): ReactElement {
    const [open, setOpen] = useState(false);
    const [inputMessage, setInputMessage] = useState('');
    const [messages, setMessages] = useState<Array<{ role: 'user' | 'assistant'; content: string }>>([]);

    const sendMessage = (event: FormEvent): void => {
        event.preventDefault();
        const trimmed = inputMessage.trim();
        if (!trimmed) {
            return;
        }

        setMessages((prev) => [...prev, { role: 'user', content: trimmed }]);
        setInputMessage('');
        setMessages((prev) => [
            ...prev,
            {
                role: 'assistant',
                content: 'AI assistant integration is not wired on this shell preview yet.',
            },
        ]);
    };

    return (
        <div className="fixed bottom-6 right-6 z-50">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="rounded-full bg-primary-600 p-4 text-white shadow-lg transition-colors hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                aria-label="Open AI Assistant"
            >
                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth={2}
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"
                    />
                </svg>
            </button>

            {open ? (
                <div className="absolute bottom-20 right-0 w-96 overflow-hidden rounded-lg border border-gray-200 bg-white shadow-xl">
                    <div className="bg-primary-600 p-4 text-white">
                        <div className="flex items-center justify-between">
                            <h3 className="text-lg font-semibold">AI Assistant</h3>
                            <button
                                type="button"
                                onClick={() => setOpen(false)}
                                className="text-white hover:text-gray-200"
                            >
                                ×
                            </button>
                        </div>
                    </div>
                    <div className="max-h-96 overflow-y-auto p-4">
                        {messages.length === 0 ? (
                            <p className="py-8 text-center text-gray-500">
                                Ask me anything about your marketing campaigns!
                            </p>
                        ) : (
                            <div className="space-y-4">
                                {messages.map((message, index) => (
                                    <div
                                        key={`${message.role}-${index}`}
                                        className={`flex ${message.role === 'user' ? 'justify-end' : 'justify-start'}`}
                                    >
                                        <div
                                            className={`max-w-[80%] rounded-lg px-4 py-2 text-sm ${
                                                message.role === 'user'
                                                    ? 'bg-primary-600 text-white'
                                                    : 'bg-gray-100 text-gray-900'
                                            }`}
                                        >
                                            {message.content}
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}
                    </div>
                    <form onSubmit={sendMessage} className="flex space-x-2 border-t border-gray-200 p-4">
                        <input
                            value={inputMessage}
                            onChange={(event) => setInputMessage(event.target.value)}
                            type="text"
                            placeholder="Type your question..."
                            className="flex-1 rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                        />
                        <button
                            type="submit"
                            disabled={!inputMessage.trim()}
                            className="rounded-lg bg-primary-600 px-4 py-2 text-white hover:bg-primary-700 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            Send
                        </button>
                    </form>
                </div>
            ) : null}
        </div>
    );
}
