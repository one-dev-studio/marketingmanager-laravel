export type SurveyQuestionType = 'text' | 'textarea' | 'radio' | 'checkbox' | 'select' | 'rating' | 'date';

export interface SurveyQuestionData {
    id?: number;
    question: string;
    type: SurveyQuestionType;
    options: string[];
    is_required: boolean;
    order: number;
}

export interface SurveyListItem {
    id: number;
    title: string;
    status: string;
    response_count: number;
    can: {
        update: boolean;
    };
}

export interface SurveyBuilderData {
    id: number;
    title: string;
    description: string | null;
    status: string;
    questions: SurveyQuestionData[];
    public_url: string;
    can: {
        update: boolean;
    };
}
