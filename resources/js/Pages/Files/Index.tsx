import AppLayout from '@/Layouts/AppLayout';
import { router } from '@inertiajs/react';
import { route } from 'ziggy-js';

interface FileListItem {
    id: number;
    name: string;
    mime_type: string | null;
    file_url: string | null;
    file_size: number | null;
    created_at: string | null;
    uploaded_by_name: string | null;
}

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface PaginatedFiles {
    data: FileListItem[];
    links: PaginationLink[];
}

export interface FilesIndexProps {
    organizationId: string;
    brandId: string;
    brandName: string;
    files: PaginatedFiles;
    filters: {
        search: string | null;
        type: string | null;
    };
}

export default function Index({ organizationId, brandId, brandName, files, filters }: FilesIndexProps) {
    const items = files.data ?? [];

    const applyFilters = (search: string, type: string) => {
        router.get(
            route('main.files.index', { organizationId }),
            { brandId, search: search || undefined, type: type || undefined },
            { preserveState: true, preserveScroll: true },
        );
    };

    return (
        <AppLayout title="Files">
            <div className="space-y-6">
                <div>
                    <h1 className="text-2xl font-semibold text-gray-900">{brandName} Files</h1>
                    <p className="mt-1 text-sm text-gray-600">Manage your brand files, images, and documents</p>
                </div>

                <div className="flex flex-col gap-4 rounded-lg border border-gray-200 bg-white p-4 sm:flex-row">
                    <input
                        type="search"
                        placeholder="Search files…"
                        className="flex-1 rounded-md border border-gray-300 px-3 py-2 text-sm"
                        defaultValue={filters.search ?? ''}
                        onKeyDown={(e) => {
                            if (e.key === 'Enter') {
                                applyFilters(
                                    (e.target as HTMLInputElement).value,
                                    filters.type ?? '',
                                );
                            }
                        }}
                    />
                    <select
                        className="rounded-md border border-gray-300 px-3 py-2 text-sm sm:w-48"
                        defaultValue={filters.type ?? ''}
                        onChange={(e) => applyFilters(filters.search ?? '', e.target.value)}
                    >
                        <option value="">All types</option>
                        <option value="image">Images</option>
                        <option value="document">Documents</option>
                    </select>
                </div>

                <div className="overflow-hidden rounded-lg border border-gray-200 bg-white">
                    {items.length === 0 ? (
                        <p className="p-8 text-center text-gray-500">No files yet.</p>
                    ) : (
                        <ul className="divide-y divide-gray-100">
                            {items.map((file) => (
                                <li key={file.id} className="flex items-center justify-between gap-4 p-4 text-sm">
                                    <div>
                                        <p className="font-medium text-gray-900">{file.name}</p>
                                        <p className="text-xs text-gray-500">
                                            {file.mime_type ?? 'unknown'}
                                            {file.uploaded_by_name ? ` · ${file.uploaded_by_name}` : ''}
                                        </p>
                                    </div>
                                    {file.file_url && (
                                        <a
                                            href={file.file_url}
                                            className="text-primary-600 hover:underline"
                                            target="_blank"
                                            rel="noreferrer"
                                        >
                                            Open
                                        </a>
                                    )}
                                </li>
                            ))}
                        </ul>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
