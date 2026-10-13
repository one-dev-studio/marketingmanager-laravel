import AppLayout from '@/Layouts/AppLayout';
import { Link, router } from '@inertiajs/react';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

interface ChatTopicSummary {
    id: number;
    name: string;
    description?: string | null;
    unread_count?: number;
    latest_message?: { message?: string } | null;
}

interface PendingReview {
    id: number;
    scheduled_post?: {
        campaign?: { name?: string };
        channel?: { name?: string };
    };
}

interface ActivityItem {
    id: number;
    action?: string;
    user?: { name?: string };
    created_at?: string;
}

export interface CollaborationIndexProps {
    organizationId: string;
    topics: ChatTopicSummary[];
    pendingReviews: PendingReview[];
    recentActivity: ActivityItem[];
}

export default function Index({
    organizationId,
    topics: initialTopics,
    pendingReviews,
    recentActivity,
}: CollaborationIndexProps) {
    const [topics, setTopics] = useState(initialTopics);
    const [selectedTopicId, setSelectedTopicId] = useState<number | null>(null);
    const [messages, setMessages] = useState<{ id: number; message: string; user?: { name?: string } }[]>([]);
    const [newMessage, setNewMessage] = useState('');

    const selectTopic = async (topic: ChatTopicSummary) => {
        setSelectedTopicId(topic.id);
        const response = await axios.get(
            route('main.collaboration.topics.show', { organizationId, topic: topic.id }),
        );
        const payload = response.data.data?.messages;
        const list = payload?.data ?? payload ?? [];
        setMessages(Array.isArray(list) ? list : []);
        setTopics((prev) =>
            prev.map((item) => (item.id === topic.id ? { ...item, unread_count: 0 } : item)),
        );
    };

    const sendMessage = async () => {
        if (!selectedTopicId || !newMessage.trim()) {
            return;
        }
        const response = await axios.post(
            route('main.collaboration.messages.store', {
                organizationId,
                topic: selectedTopicId,
            }),
            { message: newMessage.trim(), attachments: [] },
        );
        if (response.data.success) {
            setMessages((prev) => [...prev, response.data.data]);
            setNewMessage('');
        }
    };

    const approveReview = (approvalId: number) => {
        router.post(route('main.review.approve', { organizationId, approval: approvalId }));
    };

    const rejectReview = (approvalId: number) => {
        router.post(route('main.review.reject', { organizationId, approval: approvalId }), {
            rejection_reason: 'Rejected from collaboration hub',
        });
    };

    const selectedTopic = topics.find((topic) => topic.id === selectedTopicId);

    return (
        <AppLayout title="Collaboration">
            <div className="flex min-h-[32rem] flex-col gap-4 lg:flex-row">
                <aside className="w-full rounded-lg border border-gray-200 bg-white lg:w-64">
                    <div className="border-b border-gray-200 p-4">
                        <h2 className="text-lg font-semibold text-gray-900">Conversations</h2>
                    </div>
                    <div className="max-h-[28rem] overflow-y-auto">
                        {topics.map((topic) => (
                            <button
                                key={topic.id}
                                type="button"
                                className={`w-full border-b border-gray-100 p-3 text-left hover:bg-gray-50 ${
                                    selectedTopicId === topic.id ? 'bg-primary-50' : ''
                                }`}
                                onClick={() => void selectTopic(topic)}
                            >
                                <div className="flex items-center justify-between">
                                    <span className="text-sm font-medium">{topic.name}</span>
                                    {(topic.unread_count ?? 0) > 0 ? (
                                        <span className="rounded-full bg-red-500 px-2 py-0.5 text-xs text-white">
                                            {topic.unread_count}
                                        </span>
                                    ) : null}
                                </div>
                                {topic.latest_message?.message ? (
                                    <p className="truncate text-xs text-gray-500">{topic.latest_message.message}</p>
                                ) : null}
                            </button>
                        ))}
                    </div>
                </aside>

                <div className="flex-1 space-y-4">
                    {selectedTopic ? (
                        <div className="rounded-lg border border-gray-200 bg-white p-4">
                            <h3 className="font-semibold text-gray-900">{selectedTopic.name}</h3>
                            <div className="mt-4 max-h-64 space-y-2 overflow-y-auto">
                                {messages.map((message) => (
                                    <p key={message.id} className="text-sm text-gray-700">
                                        <span className="font-medium">{message.user?.name ?? 'User'}:</span>{' '}
                                        {message.message}
                                    </p>
                                ))}
                            </div>
                            <div className="mt-3 flex gap-2">
                                <input
                                    value={newMessage}
                                    className="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                                    placeholder="Write a message"
                                    onChange={(event) => setNewMessage(event.target.value)}
                                    onKeyDown={(event) => {
                                        if (event.key === 'Enter') {
                                            event.preventDefault();
                                            void sendMessage();
                                        }
                                    }}
                                />
                                <button
                                    type="button"
                                    className="rounded-md bg-primary-600 px-3 py-2 text-sm text-white"
                                    onClick={() => void sendMessage()}
                                >
                                    Send
                                </button>
                            </div>
                        </div>
                    ) : (
                        <div className="rounded-lg border border-gray-200 bg-white p-6">
                            <h2 className="text-xl font-bold text-gray-900">Welcome to Collaboration</h2>
                            <p className="mt-2 text-gray-600">
                                Select a conversation from the sidebar to start collaborating.
                            </p>
                        </div>
                    )}

                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <h3 className="text-lg font-semibold text-gray-900">For your review</h3>
                        {pendingReviews.length === 0 ? (
                            <p className="mt-4 text-center text-gray-500">No pending reviews</p>
                        ) : (
                            <ul className="mt-4 space-y-3">
                                {pendingReviews.map((review) => (
                                    <li
                                        key={review.id}
                                        className="flex flex-col gap-2 rounded-lg border border-gray-100 p-3 sm:flex-row sm:items-center sm:justify-between"
                                    >
                                        <div>
                                            <p className="font-medium">
                                                {review.scheduled_post?.campaign?.name ?? 'Untitled campaign'}
                                            </p>
                                            <p className="text-sm text-gray-600">
                                                {review.scheduled_post?.channel?.name ?? 'No channel'}
                                            </p>
                                        </div>
                                        <div className="flex gap-2">
                                            <button
                                                type="button"
                                                className="rounded bg-green-600 px-3 py-1 text-sm text-white"
                                                onClick={() => approveReview(review.id)}
                                            >
                                                Approve
                                            </button>
                                            <button
                                                type="button"
                                                className="rounded bg-red-600 px-3 py-1 text-sm text-white"
                                                onClick={() => rejectReview(review.id)}
                                            >
                                                Reject
                                            </button>
                                        </div>
                                    </li>
                                ))}
                            </ul>
                        )}
                        <Link
                            href={route('main.review.index', { organizationId })}
                            className="mt-4 inline-block text-sm text-primary-700 hover:text-primary-800"
                        >
                            View all reviews →
                        </Link>
                    </div>

                    <div className="rounded-lg border border-gray-200 bg-white p-4">
                        <h3 className="text-lg font-semibold text-gray-900">Recent activity</h3>
                        {recentActivity.length === 0 ? (
                            <p className="mt-4 text-center text-gray-500">No recent activity</p>
                        ) : (
                            <ul className="mt-4 space-y-2 text-sm text-gray-700">
                                {recentActivity.map((activity) => (
                                    <li key={activity.id}>
                                        {activity.user?.name ?? 'Someone'} — {activity.action ?? 'updated'}{' '}
                                        <span className="text-gray-400">{activity.created_at}</span>
                                    </li>
                                ))}
                            </ul>
                        )}
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
