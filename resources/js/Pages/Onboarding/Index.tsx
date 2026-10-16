import OnboardingChat from '@/components/onboarding/OnboardingChat';
import OnboardingDataCard from '@/components/onboarding/OnboardingDataCard';
import {
    type ConfirmedDataField,
    type OnboardingActionButton,
    type OnboardingChatApiPayload,
    type OnboardingConfirmedData,
    type OnboardingMessage,
    ONBOARDING_DRAFT_STORAGE_KEY,
    confirmedDataFieldLabels,
    emptyConfirmedData,
} from '@/types/onboarding';
import { Head, router } from '@inertiajs/react';
import axios from 'axios';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { route } from 'ziggy-js';

const WELCOME_MESSAGE: OnboardingMessage = {
    role: 'assistant',
    content:
        "Welcome to Marketing Manager! I'm Jenna, your AI marketing partner. I'm here to help you get set up. First, what is the name of your organization?",
    timestamp: new Date().toISOString(),
};

function triggerConfetti(): void {
    import('canvas-confetti').then((confettiModule) => {
        const confetti = confettiModule.default;
        const duration = 3000;
        const animationEnd = Date.now() + duration;
        const defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 0 };

        const randomInRange = (min: number, max: number) => Math.random() * (max - min) + min;

        const interval = window.setInterval(() => {
            const timeLeft = animationEnd - Date.now();

            if (timeLeft <= 0) {
                window.clearInterval(interval);
                return;
            }

            const particleCount = 50 * (timeLeft / duration);
            confetti({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 },
            });
            confetti({
                ...defaults,
                particleCount,
                origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 },
            });
        }, 250);
    });
}

export default function OnboardingIndex() {
    const [messages, setMessages] = useState<OnboardingMessage[]>([]);
    const [isLoading, setIsLoading] = useState(false);
    const [confirmedData, setConfirmedData] = useState<OnboardingConfirmedData>(emptyConfirmedData);
    const [showSuccessToast, setShowSuccessToast] = useState(false);
    const [showRestartDialog, setShowRestartDialog] = useState(false);
    const [currentActionButtons, setCurrentActionButtons] = useState<OnboardingActionButton[]>([]);
    const [draftLoaded, setDraftLoaded] = useState(false);

    const hasConfirmedData = useMemo(
        () =>
            Boolean(
                confirmedData.name ||
                    confirmedData.website ||
                    confirmedData.focus ||
                    confirmedData.businessModel,
            ),
        [confirmedData],
    );

    useEffect(() => {
        const draftJson = localStorage.getItem(ONBOARDING_DRAFT_STORAGE_KEY);
        if (draftJson) {
            try {
                const draft = JSON.parse(draftJson) as {
                    messages?: OnboardingMessage[];
                    confirmedData?: OnboardingConfirmedData;
                };
                setMessages(draft.messages ?? []);
                setConfirmedData(draft.confirmedData ?? emptyConfirmedData());
            } catch {
                localStorage.removeItem(ONBOARDING_DRAFT_STORAGE_KEY);
            }
        }
        setDraftLoaded(true);
    }, []);

    useEffect(() => {
        if (!draftLoaded) {
            return;
        }
        if (messages.length === 0) {
            setMessages([WELCOME_MESSAGE]);
        }
    }, [draftLoaded, messages.length]);

    useEffect(() => {
        if (!draftLoaded) {
            return;
        }
        const draft = {
            messages,
            confirmedData,
            timestamp: new Date().toISOString(),
        };
        localStorage.setItem(ONBOARDING_DRAFT_STORAGE_KEY, JSON.stringify(draft));
    }, [messages, confirmedData, draftLoaded]);

    const handleCompletion = useCallback(async () => {
        setShowSuccessToast(true);

        try {
            const response = await axios.post<{ success: boolean; data: { organizationId: number } }>(
                '/api/v1/onboarding/complete',
                { confirmedData },
            );

            if (response.data.success && response.data.data.organizationId) {
                localStorage.removeItem(ONBOARDING_DRAFT_STORAGE_KEY);
                triggerConfetti();

                window.setTimeout(() => {
                    router.visit(
                        route('main.brands.index', {
                            organizationId: response.data.data.organizationId,
                        }),
                    );
                }, 2000);
            }
        } catch {
            setShowSuccessToast(false);
            setMessages((previous) => [
                ...previous,
                {
                    role: 'assistant',
                    content:
                        'I encountered an error completing your setup. Please try again or contact support.',
                    timestamp: new Date().toISOString(),
                },
            ]);
        }
    }, [confirmedData]);

    const handleSendMessage = useCallback(
        async (message: string) => {
            const userMessage: OnboardingMessage = {
                role: 'user',
                content: message,
                timestamp: new Date().toISOString(),
            };

            const historyBeforeUser = messages;
            setMessages((previous) => [...previous, userMessage]);
            setIsLoading(true);

            try {
                const response = await axios.post<{ success: boolean; data: OnboardingChatApiPayload }>(
                    '/api/v1/onboarding/chat',
                    {
                        message,
                        confirmedData,
                        conversationHistory: historyBeforeUser.map((entry) => ({
                            role: entry.role,
                            content: entry.content,
                        })),
                    },
                );

                const aiResponse = response.data.data;

                setMessages((previous) => [
                    ...previous,
                    {
                        role: 'assistant',
                        content: aiResponse.message,
                        timestamp: new Date().toISOString(),
                    },
                ]);

                if (aiResponse.confirmedData) {
                    setConfirmedData((previous) => ({ ...previous, ...aiResponse.confirmedData }));
                }

                if (aiResponse.completed) {
                    await handleCompletion();
                }

                setCurrentActionButtons(aiResponse.actionButtons ?? []);
            } catch {
                setMessages((previous) => [
                    ...previous,
                    {
                        role: 'assistant',
                        content: "I'm sorry, I encountered an error. Please try again.",
                        timestamp: new Date().toISOString(),
                    },
                ]);
                setCurrentActionButtons([]);
            } finally {
                setIsLoading(false);
            }
        },
        [confirmedData, handleCompletion, messages],
    );

    const handleEditData = (field: ConfirmedDataField) => {
        const editMessage = `I'd like to change the ${confirmedDataFieldLabels[field]}.`;
        void handleSendMessage(editMessage);
    };

    const handleSkip = () => {
        if (confirmedData.name) {
            router.visit(route('main.organizations'));
        }
    };

    const confirmRestart = () => {
        setMessages([WELCOME_MESSAGE]);
        setConfirmedData(emptyConfirmedData());
        setShowRestartDialog(false);
        setCurrentActionButtons([]);
        localStorage.removeItem(ONBOARDING_DRAFT_STORAGE_KEY);
    };

    return (
        <>
            <Head title="Onboarding" />
            <div className="flex min-h-screen items-center justify-center bg-gradient-to-br from-indigo-50 to-indigo-100 p-4">
                <div className="mx-auto flex h-[calc(100vh-2rem)] max-h-[900px] w-full max-w-7xl flex-col overflow-hidden rounded-lg bg-white shadow-xl">
                    <div className="flex items-center justify-between bg-primary-600 px-6 py-4 text-white">
                        <div className="flex items-center space-x-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-white/20">
                                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h1 className="text-xl font-semibold">Welcome to Marketing Manager</h1>
                                <p className="text-sm text-primary-100">Let&apos;s get you set up</p>
                            </div>
                        </div>
                        <div className="flex items-center space-x-2">
                            {confirmedData.name ? (
                                <button
                                    type="button"
                                    onClick={handleSkip}
                                    className="rounded-lg px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary-700"
                                >
                                    Skip for now
                                </button>
                            ) : null}
                            <button
                                type="button"
                                onClick={() => setShowRestartDialog(true)}
                                className="rounded-lg px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-primary-700"
                            >
                                Restart
                            </button>
                        </div>
                    </div>

                    <div className="flex flex-1 overflow-hidden">
                        <div className="flex flex-1 flex-col">
                            <OnboardingChat
                                messages={messages}
                                isLoading={isLoading}
                                actionButtons={currentActionButtons}
                                onSendMessage={(text) => {
                                    void handleSendMessage(text);
                                }}
                            />
                        </div>

                        <div className="w-80 overflow-y-auto border-l border-gray-200 bg-gray-50">
                            <div className="p-4">
                                <h2 className="mb-4 text-sm font-semibold text-gray-700">Confirmed Information</h2>
                                <div className="space-y-3">
                                    {confirmedData.name ? (
                                        <OnboardingDataCard
                                            label="Organization Name"
                                            value={confirmedData.name}
                                            onEdit={() => handleEditData('name')}
                                        />
                                    ) : null}
                                    {confirmedData.website ? (
                                        <OnboardingDataCard
                                            label="Website"
                                            value={confirmedData.website}
                                            onEdit={() => handleEditData('website')}
                                        />
                                    ) : null}
                                    {confirmedData.focus ? (
                                        <OnboardingDataCard
                                            label="Business Focus"
                                            value={confirmedData.focus}
                                            onEdit={() => handleEditData('focus')}
                                        />
                                    ) : null}
                                    {confirmedData.businessModel ? (
                                        <OnboardingDataCard
                                            label="Business Model"
                                            value={confirmedData.businessModel}
                                            onEdit={() => handleEditData('businessModel')}
                                        />
                                    ) : null}
                                    {!hasConfirmedData ? (
                                        <div className="py-8 text-center text-sm text-gray-400">
                                            <p>No information confirmed yet</p>
                                        </div>
                                    ) : null}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {showSuccessToast ? (
                <div className="fixed right-4 top-4 z-50 flex items-center space-x-3 rounded-lg bg-green-500 px-6 py-4 text-white shadow-lg">
                    <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 13l4 4L19 7" />
                    </svg>
                    <span className="font-medium">Setup complete! Redirecting...</span>
                </div>
            ) : null}

            {showRestartDialog ? (
                <div
                    className="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                    onClick={() => setShowRestartDialog(false)}
                    onKeyDown={(event) => {
                        if (event.key === 'Escape') {
                            setShowRestartDialog(false);
                        }
                    }}
                    role="presentation"
                >
                    <div
                        className="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="restart-dialog-title"
                        onClick={(event) => event.stopPropagation()}
                    >
                        <h3 id="restart-dialog-title" className="mb-2 text-lg font-semibold text-gray-900">
                            Restart Onboarding?
                        </h3>
                        <p className="mb-6 text-gray-600">
                            This will clear all your progress. Are you sure you want to restart?
                        </p>
                        <div className="flex justify-end space-x-3">
                            <button
                                type="button"
                                onClick={() => setShowRestartDialog(false)}
                                className="rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-200"
                            >
                                Cancel
                            </button>
                            <button
                                type="button"
                                onClick={confirmRestart}
                                className="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-red-700"
                            >
                                Restart
                            </button>
                        </div>
                    </div>
                </div>
            ) : null}
        </>
    );
}
