export interface ClientPortalBranding {
    enabled: boolean;
    title: string;
    welcome_text: string | null;
    logo_url: string | null;
    primary_color: string;
    secondary_color: string;
    css_variables: Record<string, string>;
    agency_id?: number;
    agency_name?: string;
    portal_home_href?: string;
}

export interface ClientPortalOrganizationLink {
    id: string;
    name: string;
    dashboard_href: string;
    workspace_href: string;
}
