import { ShellMobileSidebar } from '@/components/layout/ShellMobileSidebar';
import { SidebarIcon } from '@/components/layout/SidebarIcon';
import type { SidebarMenuItem, SidebarNavigation } from '@/types/sidebar';
import { Link } from '@inertiajs/react';
import { route } from 'ziggy-js';
import { useState, type ReactElement } from 'react';

interface AppSidebarProps {
    navigation: SidebarNavigation;
    mobileOpen: boolean;
    onMobileClose: () => void;
}

function badgeClasses(variant: string): string {
    if (variant === 'danger') {
        return 'bg-red-500 text-white';
    }

    return 'bg-gray-200 text-gray-700';
}

function SidebarLink({
    item,
    onNavigate,
}: {
    item: SidebarMenuItem & { type: 'link' };
    onNavigate?: () => void;
}): ReactElement {
    const activeClasses = item.isActive
        ? 'bg-primary-50 text-primary-700 border-r-2 border-primary-600'
        : 'text-gray-700 hover:bg-gray-100';

    return (
        <Link
            href={item.href}
            className={`flex items-center rounded-lg px-3 py-2 transition-colors ${activeClasses}`}
            aria-current={item.isActive ? 'page' : undefined}
            onClick={onNavigate}
        >
            {item.icon ? (
                <span className="mr-3 flex-shrink-0">
                    <SidebarIcon name={item.icon} />
                </span>
            ) : null}
            <span className="flex-1">{item.label}</span>
            {item.badge ? (
                <span
                    className={`ml-auto rounded-full px-2 py-0.5 text-xs font-semibold ${badgeClasses(item.badgeVariant)}`}
                >
                    {item.badge}
                </span>
            ) : null}
        </Link>
    );
}

function SidebarGroup({
    item,
}: {
    item: SidebarMenuItem & { type: 'group' };
}): ReactElement {
    const [open, setOpen] = useState(item.defaultOpen);

    return (
        <div className="space-y-1">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="flex w-full items-center justify-between rounded-lg px-3 py-2 text-gray-700 transition-colors hover:bg-gray-100"
            >
                <div className="flex flex-1 items-center">
                    {item.icon ? (
                        <span className="mr-3 flex-shrink-0">
                            <SidebarIcon name={item.icon} />
                        </span>
                    ) : null}
                    <span className="flex-1 text-left">{item.label}</span>
                </div>
                <svg
                    className={`h-4 w-4 transition-transform duration-200 ${open ? 'rotate-180' : ''}`}
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {open ? (
                <div className="ml-6 space-y-1">
                    {item.children.map((child) => (
                        <SidebarLink key={`${child.label}-${child.href}`} item={child} />
                    ))}
                </div>
            ) : null}
        </div>
    );
}

function BrandSwitcher({ navigation }: { navigation: SidebarNavigation }): ReactElement {
    const [open, setOpen] = useState(false);
    const currentBrand = navigation.brands.find((b) => String(b.id) === String(navigation.brandId));

    return (
        <div className="relative w-full">
            <button
                type="button"
                onClick={() => setOpen((value) => !value)}
                className="flex w-full items-center justify-between rounded-lg px-3 py-2 text-gray-700 transition-colors hover:bg-gray-100"
            >
                <span className="truncate">{currentBrand?.name ?? 'Select Brand'}</span>
                <svg className="h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            {open ? (
                <div className="absolute z-50 mt-2 max-h-64 w-full overflow-y-auto rounded-lg border border-gray-200 bg-white py-1 shadow-lg">
                    <Link
                        href={navigation.dashboardHref}
                        className={`block px-4 py-2 text-sm hover:bg-gray-100 ${!navigation.brandId ? 'bg-primary-50 text-primary-700' : 'text-gray-700'}`}
                        onClick={() => setOpen(false)}
                    >
                        All Brands
                    </Link>
                    {navigation.brands.map((brand) => (
                        <Link
                            key={brand.id}
                            href={brand.href}
                            className={`block px-4 py-2 text-sm hover:bg-gray-100 ${
                                String(brand.id) === String(navigation.brandId)
                                    ? 'bg-primary-50 text-primary-700'
                                    : 'text-gray-700'
                            }`}
                            onClick={() => setOpen(false)}
                        >
                            {brand.name}
                        </Link>
                    ))}
                </div>
            ) : null}
        </div>
    );
}

function AppSidebarPanel({
    navigation,
    collapsed,
    onCollapsedToggle,
    onNavigate,
}: {
    navigation: SidebarNavigation;
    collapsed: boolean;
    onCollapsedToggle?: () => void;
    onNavigate?: () => void;
}): ReactElement {
    return (
        <div className="flex h-full flex-col overflow-y-auto p-4">
                <div className="mb-4">
                    <Link href={navigation.dashboardHref} className="flex items-center space-x-2" onClick={onNavigate}>
                        <svg className="h-8 w-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={2}
                                d="M13 10V3L4 14h7v7l9-11h-7z"
                            />
                        </svg>
                        {!collapsed ? (
                            <span className="text-xl font-bold text-gray-900">{navigation.appName}</span>
                        ) : null}
                    </Link>
                    {!collapsed && navigation.hasBrands ? <BrandSwitcher navigation={navigation} /> : null}
                </div>

                <nav className="flex-1 space-y-1">
                    {navigation.items.map((item) => {
                        if (item.type === 'link') {
                            return (
                                <SidebarLink
                                    key={`${item.label}-${item.href}`}
                                    item={item}
                                    onNavigate={onNavigate}
                                />
                            );
                        }

                        return <SidebarGroup key={item.label} item={item} />;
                    })}
                </nav>

                <div className="mt-4 border-t border-gray-200 pt-4">
                    {onCollapsedToggle ? (
                        <button
                            type="button"
                            onClick={onCollapsedToggle}
                            className="mb-2 rounded-lg p-2 text-gray-600 hover:bg-gray-100"
                            aria-label="Toggle sidebar"
                        >
                            <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={2}
                                    d="M11 19l-7-7 7-7m8 14l-7-7 7-7"
                                />
                            </svg>
                        </button>
                    ) : null}
                    <Link
                        href={route('profile.show')}
                        className="block truncate text-sm text-primary-600 hover:text-primary-800"
                        onClick={onNavigate}
                    >
                        Profile
                    </Link>
                </div>
        </div>
    );
}

export function AppSidebar({ navigation, mobileOpen, onMobileClose }: AppSidebarProps): ReactElement {
    const [collapsed, setCollapsed] = useState(false);
    const widthClass = collapsed ? 'w-16' : 'w-64';

    return (
        <>
            <aside
                className={`fixed left-0 top-0 z-40 hidden h-screen bg-white shadow-lg transition-all duration-300 ease-in-out md:block ${widthClass}`}
            >
                <AppSidebarPanel
                    navigation={navigation}
                    collapsed={collapsed}
                    onCollapsedToggle={() => setCollapsed((value) => !value)}
                />
            </aside>
            <ShellMobileSidebar open={mobileOpen} onClose={onMobileClose} navLabel="Main navigation">
                <AppSidebarPanel navigation={navigation} collapsed={false} onNavigate={onMobileClose} />
            </ShellMobileSidebar>
        </>
    );
}
