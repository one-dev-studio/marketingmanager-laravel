export interface OnboardingConfirmedData {
    name: string | null;
    website: string | null;
    focus: string | null;
    businessModel: string | null;
}

export interface OnboardingMessage {
    role: 'user' | 'assistant';
    content: string;
    timestamp: string;
}

export interface OnboardingActionButton {
    label: string;
    value: string;
}

export interface OnboardingChatApiPayload {
    message: string;
    confirmedData?: Partial<OnboardingConfirmedData>;
    actionButtons?: OnboardingActionButton[];
    completed?: boolean;
}

export const emptyConfirmedData = (): OnboardingConfirmedData => ({
    name: null,
    website: null,
    focus: null,
    businessModel: null,
});

export const ONBOARDING_DRAFT_STORAGE_KEY = 'onboarding_draft';

export type ConfirmedDataField = keyof OnboardingConfirmedData;

export const confirmedDataFieldLabels: Record<ConfirmedDataField, string> = {
    name: 'organization name',
    website: 'website URL',
    focus: 'business focus',
    businessModel: 'business model',
};
