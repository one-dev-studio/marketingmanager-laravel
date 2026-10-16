import { Button } from '@/components/ui/button';
import type { SurveyBuilderData, SurveyQuestionData, SurveyQuestionType } from '@/types/surveys';
import axios from 'axios';
import { useState } from 'react';
import { route } from 'ziggy-js';

const QUESTION_TYPES: SurveyQuestionType[] = ['text', 'textarea', 'radio', 'rating'];

export interface SurveyBuilderPanelProps {
    organizationId: string;
    survey: SurveyBuilderData;
}

export function SurveyBuilderPanel({ organizationId, survey }: SurveyBuilderPanelProps) {
    const [questions, setQuestions] = useState<SurveyQuestionData[]>(() =>
        survey.questions.length > 0
            ? survey.questions.map((q) => ({ ...q, options: q.options ?? [] }))
            : [],
    );
    const [saving, setSaving] = useState(false);
    const [saveMessage, setSaveMessage] = useState<string | null>(null);

    const addQuestion = (type: SurveyQuestionType) => {
        setQuestions((prev) => [
            ...prev,
            {
                question: '',
                type,
                options: [],
                is_required: false,
                order: prev.length,
            },
        ]);
    };

    const removeQuestion = (index: number) => {
        setQuestions((prev) => prev.filter((_, i) => i !== index));
    };

    const updateQuestion = (index: number, patch: Partial<SurveyQuestionData>) => {
        setQuestions((prev) =>
            prev.map((q, i) => (i === index ? { ...q, ...patch } : q)),
        );
    };

    const save = async () => {
        if (!survey.can.update) {
            return;
        }
        setSaving(true);
        setSaveMessage(null);
        try {
            await axios.put(
                route('main.surveys.update', { organizationId, survey: survey.id }),
                { title: survey.title, description: survey.description },
            );
            for (const question of questions) {
                if (!question.id) {
                    await axios.post(
                        route('main.surveys.questions.store', {
                            organizationId,
                            survey: survey.id,
                        }),
                        question,
                    );
                }
            }
            setSaveMessage('Survey saved.');
        } catch {
            setSaveMessage('Save failed. Check your connection and try again.');
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="space-y-4">
            <div className="flex flex-wrap gap-2">
                {QUESTION_TYPES.map((type) => (
                    <button
                        key={type}
                        type="button"
                        className="rounded border border-gray-300 px-2 py-1 text-sm hover:bg-gray-50"
                        onClick={() => addQuestion(type)}
                        disabled={!survey.can.update}
                    >
                        {type === 'text' ? 'Short text' : type === 'textarea' ? 'Long text' : type === 'radio' ? 'Multiple choice' : 'Rating'}
                    </button>
                ))}
            </div>

            {questions.map((question, index) => (
                <div
                    key={question.id ?? `new-${index}`}
                    className="space-y-2 rounded-lg border border-gray-200 bg-white p-3"
                >
                    <input
                        value={question.question}
                        className="w-full rounded-md border border-gray-300 px-2 py-1 text-sm"
                        placeholder="Question"
                        onChange={(event) => updateQuestion(index, { question: event.target.value })}
                        disabled={!survey.can.update}
                    />
                    <label className="flex items-center gap-2 text-sm text-gray-700">
                        <input
                            type="checkbox"
                            checked={question.is_required}
                            onChange={(event) =>
                                updateQuestion(index, { is_required: event.target.checked })
                            }
                            disabled={!survey.can.update}
                        />
                        Required
                    </label>
                    {survey.can.update ? (
                        <button
                            type="button"
                            className="text-xs text-red-600 hover:text-red-800"
                            onClick={() => removeQuestion(index)}
                        >
                            Remove
                        </button>
                    ) : null}
                </div>
            ))}

            {survey.can.update ? (
                <div className="flex items-center gap-3">
                    <Button type="button" disabled={saving} onClick={() => void save()}>
                        {saving ? 'Saving…' : 'Save survey'}
                    </Button>
                    {saveMessage ? <span className="text-sm text-gray-600">{saveMessage}</span> : null}
                </div>
            ) : null}
        </div>
    );
}
