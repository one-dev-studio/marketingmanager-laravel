import AppLayout from '@/Layouts/AppLayout';

export default function Shell() {
    return (
        <AppLayout title="App shell (Inertia)">
            <div className="rounded-lg border border-gray-200 bg-white p-6">
                <p className="text-sm text-gray-600">
                    Phase 3 proof page: sidebar menu is driven by{' '}
                    <code className="rounded bg-gray-100 px-1">SidebarMenuBuilder</code> shared props.
                    This route is not linked from product navigation.
                </p>
            </div>
        </AppLayout>
    );
}
