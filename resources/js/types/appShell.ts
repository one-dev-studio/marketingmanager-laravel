export interface AppShellOrganization {
    id: string;
    name: string;
    href: string;
}

export interface AppShellLocale {
    code: string;
    name: string;
    href: string;
    isCurrent: boolean;
}

export interface AppShellContext {
    currentOrganization: {
        id: string;
        name: string;
    };
    organizations: AppShellOrganization[];
    reviewPendingCount: number;
    reviewHref: string;
    notificationCount: number;
    locales: AppShellLocale[];
    currentLocale: string;
    dashboardHref: string;
}
