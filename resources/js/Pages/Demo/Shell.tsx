import AppLayout from '@/Layouts/AppLayout';

export default function Shell() {
    return (
        <AppLayout title="App shell (Inertia)">
            <div className="rounded-lg border border-gray-200 bg-white p-6">
                <p className="text-sm text-gray-600">
                    Phase 3 app shell preview: sidebar from{' '}
                    <code className="rounded bg-gray-100 px-1">SidebarMenuBuilder</code>, header
                    switchers from <code className="rounded bg-gray-100 px-1">AppShellContextBuilder</code>,
                    and the floating AI assistant entry point. Unlisted route for staging proof.
                </p>
            </div>
        </AppLayout>
    );
}
