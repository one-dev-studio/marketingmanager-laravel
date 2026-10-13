export interface ContactListItem {
    id: number;
    email: string;
    full_name: string;
    status: string;
    company: string | null;
}

export interface ContactDetail {
    id: number;
    email: string;
    first_name: string | null;
    last_name: string | null;
    full_name: string;
    phone: string | null;
    company: string | null;
    job_title: string | null;
    status: string;
    source: string | null;
    tags: string[];
    contact_list_ids: number[];
}

export interface ContactListOption {
    id: number;
    name: string;
}
