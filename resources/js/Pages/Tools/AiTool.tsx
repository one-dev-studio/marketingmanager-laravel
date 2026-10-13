import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { Link } from '@inertiajs/react';
import axios from 'axios';
import { useMemo, useState } from 'react';
import { route } from 'ziggy-js';

type FieldType = 'text' | 'textarea' | 'select';

export interface AiToolField {
    name: string;
    label: string;
    type: FieldType;
    rows?: number;
    options?: string[];
    default?: string;
}

export interface ToolsAiToolProps {
    organizationId: string;
    toolKey: string;
    title: string;
    submitRoute: string;
    submitLabel?: string;
    fields: AiToolField[];
    footerLink?: {
        label: string;
        routeName: string;
    };
}

function initialValues(fields: AiToolField[]): Record<string, string> {
    const values: Record<string, string> = {};
    for (const field of fields) {
        values[field.name] = field.default ?? '';
    }
    return values;
}

export default function AiTool({
    organizationId,
    title,
    submitRoute,
    submitLabel = 'Generate',
    fields,
    footerLink,
}: ToolsAiToolProps) {
    const [values, setValues] = useState(() => initialValues(fields));
    const [output, setOutput] = useState('');
    const [loading, setLoading] = useState(false);

    const endpoint = useMemo(
        () => route(submitRoute, { organizationId }),
        [organizationId, submitRoute],
    );

    const run = async () => {
        setLoading(true);
        setOutput('');
        try {
            const { data } = await axios.post(endpoint, values);
            setOutput(JSON.stringify(data.data ?? data, null, 2));
        } catch (error: unknown) {
            const message =
                axios.isAxiosError(error) && error.response?.data?.message
                    ? String(error.response.data.message)
                    : error instanceof Error
                      ? error.message
                      : 'Generation failed.';
            setOutput(message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <AppLayout title={title}>
            <div className="max-w-3xl space-y-4">
                <h1 className="text-2xl font-semibold text-gray-900">{title}</h1>
                <div className="space-y-3 rounded-lg border border-gray-200 bg-white p-6">
                    {fields.map((field) => {
                        if (field.type === 'textarea') {
                            return (
                                <textarea
                                    key={field.name}
                                    rows={field.rows ?? 3}
                                    placeholder={field.label}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                                    value={values[field.name] ?? ''}
                                    onChange={(event) =>
                                        setValues((prev) => ({ ...prev, [field.name]: event.target.value }))
                                    }
                                />
                            );
                        }
                        if (field.type === 'select') {
                            return (
                                <select
                                    key={field.name}
                                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                                    value={values[field.name] ?? ''}
                                    onChange={(event) =>
                                        setValues((prev) => ({ ...prev, [field.name]: event.target.value }))
                                    }
                                >
                                    {(field.options ?? []).map((option) => (
                                        <option key={option} value={option}>{option}</option>
                                    ))}
                                </select>
                            );
                        }
                        return (
                            <input
                                key={field.name}
                                type="text"
                                placeholder={field.label}
                                className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                                value={values[field.name] ?? ''}
                                onChange={(event) =>
                                    setValues((prev) => ({ ...prev, [field.name]: event.target.value }))
                                }
                            />
                        );
                    })}
                    <div className="flex flex-wrap items-center gap-2">
                        <Button type="button" disabled={loading} onClick={() => void run()}>
                            {loading ? 'Working…' : submitLabel}
                        </Button>
                        {footerLink ? (
                            <Link
                                href={route(footerLink.routeName, { organizationId })}
                                className="text-sm text-primary-700 hover:text-primary-800"
                            >
                                {footerLink.label}
                            </Link>
                        ) : null}
                    </div>
                </div>
                {output ? (
                    <pre className="whitespace-pre-wrap rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm text-gray-800">
                        {output}
                    </pre>
                ) : null}
            </div>
        </AppLayout>
    );
}
