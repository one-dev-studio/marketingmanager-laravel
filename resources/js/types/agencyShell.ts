import type { SidebarMenuLink } from '@/types/sidebar';

export interface AgencySidebarNavigation {
    title: string;
    agencyId: string;
    agencyName: string;
    items: SidebarMenuLink[];
}

export interface AgencyShellContext {
    agency: {
        id: string;
        name: string;
    };
    isAgencyAdmin: boolean;
    exitAgencyHref: string;
    profileHref: string;
    userName: string;
    userEmail: string;
}
