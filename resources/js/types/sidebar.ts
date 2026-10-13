export interface SidebarBrandOption {
    id: number | string;
    name: string;
    href: string;
}

export interface SidebarMenuLink {
    type: 'link';
    label: string;
    href: string;
    icon: string | null;
    badge: number | null;
    badgeVariant: string;
    isActive: boolean;
}

export interface SidebarMenuGroup {
    type: 'group';
    label: string;
    icon: string | null;
    defaultOpen: boolean;
    children: SidebarMenuLink[];
}

export type SidebarMenuItem = SidebarMenuLink | SidebarMenuGroup;

export interface SidebarNavigation {
    appName: string;
    organizationId: string;
    brandId: string | null;
    hasBrands: boolean;
    hasBrandSelected: boolean;
    dashboardHref: string;
    brands: SidebarBrandOption[];
    mentionCount: number;
    reviewCount: number;
    items: SidebarMenuItem[];
}
