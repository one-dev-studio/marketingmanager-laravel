import { useState } from 'react';

interface OnboardingDataCardProps {
    label: string;
    value: string;
    onEdit: () => void;
}

export default function OnboardingDataCard({ label, value, onEdit }: OnboardingDataCardProps) {
    const [showEdit, setShowEdit] = useState(false);

    return (
        <div
            className="group relative rounded-lg border border-gray-200 bg-white p-4 transition-colors hover:border-primary-300"
            onMouseEnter={() => setShowEdit(true)}
            onMouseLeave={() => setShowEdit(false)}
        >
            <div className="flex items-start justify-between">
                <div className="flex-1">
                    <p className="mb-1 text-xs font-medium uppercase text-gray-500">{label}</p>
                    <p className="text-sm font-medium text-gray-900">{value}</p>
                </div>
                {showEdit ? (
                    <button
                        type="button"
                        onClick={onEdit}
                        className="ml-2 p-1 text-gray-400 transition-colors hover:text-primary-600"
                        title="Edit"
                    >
                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                            />
                        </svg>
                    </button>
                ) : null}
            </div>
        </div>
    );
}
