import { Button } from '@/components/ui/button';
import AppLayout from '@/Layouts/AppLayout';
import { router } from '@inertiajs/react';
import { useCallback, useState } from 'react';
import { route } from 'ziggy-js';

interface FileDetail {
    id: number;
    name: string;
    description: string | null;
    mime_type: string | null;
    file_url: string | null;
    file_size: number | null;
    width: number | null;
    height: number | null;
    tags: string[];
    created_at: string | null;
    uploaded_by_name: string | null;
}

export interface FilesShowProps {
    organizationId: string;
    organizationName: string;
    brandId: string | null;
    file: FileDetail;
}

function formatFileSizeKb(bytes: number | null): string {
    if (bytes === null || bytes <= 0) {
        return '—';
    }
    return `${(bytes / 1024).toFixed(1)} KB`;
}

function getCsrfToken(): string {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta?.getAttribute('content') ?? '';
}

export default function Show({ organizationId, organizationName, brandId, file }: FilesShowProps) {
    const [imageScale, setImageScale] = useState(1);
    const [copied, setCopied] = useState(false);

    const isImage = file.mime_type?.startsWith('image/') ?? false;
    const isPdf = file.mime_type === 'application/pdf';

    const downloadHref = route('main.files.download', { organizationId, file: file.id });

    const copyFileUrl = useCallback(async () => {
        if (!file.file_url) {
            return;
        }
        await navigator.clipboard.writeText(file.file_url);
        setCopied(true);
        window.setTimeout(() => setCopied(false), 2000);
    }, [file.file_url]);

    const deleteFile = async () => {
        if (!window.confirm('Are you sure you want to delete this file? This action cannot be undone.')) {
            return;
        }
        try {
            const response = await fetch(route('main.files.destroy', { organizationId, file: file.id }), {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': getCsrfToken(), Accept: 'application/json' },
            });
            const data = (await response.json()) as { success?: boolean; message?: string };
            if (data.success) {
                const indexUrl = route('main.files.index', { organizationId });
                router.visit(brandId ? `${indexUrl}?brandId=${brandId}` : indexUrl);
                return;
            }
            window.alert(data.message ?? 'Delete failed');
        } catch {
            window.alert('Delete failed. Please try again.');
        }
    };

    return (
        <AppLayout title="File Preview">
            <div className="space-y-6">
                <div className="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div className="flex items-center gap-4">
                        <button
                            type="button"
                            onClick={() => window.history.back()}
                            className="text-gray-600 hover:text-gray-900"
                            aria-label="Go back"
                        >
                            ←
                        </button>
                        <div>
                            <h1 className="text-2xl font-semibold text-gray-900">{file.name}</h1>
                            <p className="mt-1 text-sm text-gray-600">
                                {organizationName}
                                {file.created_at ? ` · Uploaded ${file.created_at}` : ''}
                            </p>
                        </div>
                    </div>
                    <div className="flex flex-wrap items-center gap-2">
                        <a
                            href={downloadHref}
                            className="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-medium text-white hover:bg-primary-700"
                        >
                            Download
                        </a>
                        <Button type="button" variant="outline" onClick={() => void deleteFile()}>
                            Delete
                        </Button>
                    </div>
                </div>

                <div className="grid grid-cols-1 gap-6 lg:grid-cols-3">
                    <div className="lg:col-span-2">
                        <div className="overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
                            {isImage && file.file_url ? (
                                <div className="relative bg-gray-50">
                                    <img
                                        src={file.file_url}
                                        alt={file.name}
                                        className="mx-auto h-auto max-h-96 w-full object-contain"
                                        style={{ transform: `scale(${imageScale})`, transformOrigin: 'center center' }}
                                    />
                                    <div className="absolute bottom-4 right-4 flex gap-2">
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => setImageScale((s) => Math.max(s - 0.25, 0.25))}
                                        >
                                            −
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            onClick={() => setImageScale((s) => Math.min(s + 0.25, 3))}
                                        >
                                            +
                                        </Button>
                                    </div>
                                </div>
                            ) : isPdf ? (
                                <div className="bg-gray-100 p-8 text-center">
                                    <h3 className="text-lg font-medium text-gray-900">PDF Document</h3>
                                    <p className="mt-2 text-sm text-gray-600">Click download to view the full document</p>
                                    <a
                                        href={downloadHref}
                                        className="mt-6 inline-flex rounded-md bg-primary-600 px-4 py-2 text-sm text-white hover:bg-primary-700"
                                    >
                                        Download PDF
                                    </a>
                                </div>
                            ) : (
                                <div className="bg-gray-100 p-8 text-center">
                                    <h3 className="text-lg font-medium text-gray-900">{file.name}</h3>
                                    <p className="mt-2 text-sm text-gray-600">{file.mime_type ?? 'Unknown type'}</p>
                                    <a
                                        href={downloadHref}
                                        className="mt-6 inline-flex rounded-md bg-primary-600 px-4 py-2 text-sm text-white hover:bg-primary-700"
                                    >
                                        Download File
                                    </a>
                                </div>
                            )}
                        </div>
                    </div>

                    <div className="space-y-6">
                        <div className="rounded-lg border border-gray-200 bg-white p-6">
                            <h2 className="mb-4 text-lg font-medium text-gray-900">File Details</h2>
                            <dl className="space-y-4 text-sm">
                                <div>
                                    <dt className="font-medium text-gray-500">File Name</dt>
                                    <dd className="mt-1 text-gray-900">{file.name}</dd>
                                </div>
                                {file.description ? (
                                    <div>
                                        <dt className="font-medium text-gray-500">Description</dt>
                                        <dd className="mt-1 text-gray-900">{file.description}</dd>
                                    </div>
                                ) : null}
                                <div>
                                    <dt className="font-medium text-gray-500">File Size</dt>
                                    <dd className="mt-1 text-gray-900">{formatFileSizeKb(file.file_size)}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-500">File Type</dt>
                                    <dd className="mt-1 text-gray-900">{file.mime_type ?? '—'}</dd>
                                </div>
                                <div>
                                    <dt className="font-medium text-gray-500">Uploaded By</dt>
                                    <dd className="mt-1 text-gray-900">{file.uploaded_by_name ?? 'Unknown'}</dd>
                                </div>
                                {file.width && file.height ? (
                                    <div>
                                        <dt className="font-medium text-gray-500">Dimensions</dt>
                                        <dd className="mt-1 text-gray-900">
                                            {file.width} × {file.height} px
                                        </dd>
                                    </div>
                                ) : null}
                                {file.tags.length > 0 ? (
                                    <div>
                                        <dt className="font-medium text-gray-500">Tags</dt>
                                        <dd className="mt-1 flex flex-wrap gap-2">
                                            {file.tags.map((tag) => (
                                                <span
                                                    key={tag}
                                                    className="inline-flex rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800"
                                                >
                                                    {tag}
                                                </span>
                                            ))}
                                        </dd>
                                    </div>
                                ) : null}
                            </dl>
                        </div>
                        <div className="rounded-lg border border-gray-200 bg-white p-6">
                            <h2 className="mb-4 text-lg font-medium text-gray-900">Quick Actions</h2>
                            <div className="space-y-3">
                                <a
                                    href={downloadHref}
                                    className="flex w-full items-center justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
                                >
                                    Download
                                </a>
                                <Button type="button" variant="outline" className="w-full" onClick={() => void copyFileUrl()}>
                                    {copied ? 'Copied!' : 'Copy Link'}
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
