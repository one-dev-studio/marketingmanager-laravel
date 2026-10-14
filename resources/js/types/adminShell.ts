import type { SidebarMenuLink } from '@/types/sidebar';

export interface AdminSidebarNavigation {
    title: string;
    items: SidebarMenuLink[];
}

export interface AdminShellContext {
    returnToAppHref: string;
    profileHref: string;
    userName: string;
    userEmail: string;
}
