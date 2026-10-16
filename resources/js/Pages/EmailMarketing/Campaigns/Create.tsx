import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { useForm } from '@inertiajs/react';
import { FormEvent } from 'react';
import { route } from 'ziggy-js';

interface SelectOption {
    id: number;
    name: string;
}

export interface EmailCampaignsCreateProps {
    organizationId: string;
    templates: SelectOption[];
    lists: SelectOption[];
}

export default function Create({ organizationId, templates, lists }: EmailCampaignsCreateProps) {
    const form = useForm({
        name: '',
        subject: '',
        from_email: '',
        from_name: '',
        email_template_id: '',
        contact_list_ids: [] as number[],
        scheduled_at: '',
        settings: { ab_testing: { enabled: false } },
    });

    const submit = (event: FormEvent) => {
        event.preventDefault();
        form.post(route('main.email-marketing.campaigns.store', { organizationId }));
    };

    const toggleList = (id: number) => {
        const current = form.data.contact_list_ids;
        form.setData(
            'contact_list_ids',
            current.includes(id) ? current.filter((item) => item !== id) : [...current, id],
        );
    };

    return (
        <AppLayout title="Create Email Campaign">
            <form onSubmit={submit} className="max-w-2xl space-y-3 rounded-lg border border-gray-200 bg-white p-6">
                <input
                    required
                    placeholder="Name"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.name}
                    onChange={(e) => form.setData('name', e.target.value)}
                />
                <input
                    required
                    placeholder="Subject"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.subject}
                    onChange={(e) => form.setData('subject', e.target.value)}
                />
                <input
                    required
                    type="email"
                    placeholder="From email"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.from_email}
                    onChange={(e) => form.setData('from_email', e.target.value)}
                />
                <input
                    placeholder="From name"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.from_name}
                    onChange={(e) => form.setData('from_name', e.target.value)}
                />
                <select
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.email_template_id}
                    onChange={(e) => form.setData('email_template_id', e.target.value)}
                >
                    <option value="">Template</option>
                    {templates.map((template) => (
                        <option key={template.id} value={template.id}>{template.name}</option>
                    ))}
                </select>
                <div className="space-y-2 rounded-md border border-gray-300 p-3">
                    <p className="text-sm font-medium text-gray-700">Contact lists</p>
                    {lists.map((list) => (
                        <label key={list.id} className="flex gap-2 text-sm">
                            <input
                                type="checkbox"
                                checked={form.data.contact_list_ids.includes(list.id)}
                                onChange={() => toggleList(list.id)}
                            />
                            {list.name}
                        </label>
                    ))}
                </div>
                <input
                    type="datetime-local"
                    className="w-full rounded-md border border-gray-300 px-3 py-2 text-sm"
                    value={form.data.scheduled_at}
                    onChange={(e) => form.setData('scheduled_at', e.target.value)}
                />
                <label className="flex items-center gap-2 text-sm">
                    <input
                        type="checkbox"
                        checked={form.data.settings.ab_testing.enabled}
                        onChange={(e) =>
                            form.setData('settings', {
                                ab_testing: { enabled: e.target.checked },
                            })
                        }
                    />
                    Enable A/B subject test
                </label>
                <Button type="submit" disabled={form.processing}>Create</Button>
            </form>
        </AppLayout>
    );
}
