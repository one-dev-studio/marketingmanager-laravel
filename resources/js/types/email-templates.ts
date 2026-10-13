export type EmailBlockType = 'heading' | 'text' | 'button' | 'image';

export interface EmailBlock {
    type: EmailBlockType;
    value: string;
}

export interface EmailTemplateListItem {
    id: number;
    name: string;
    subject: string;
    category: string;
    is_public: boolean;
    can: {
        update: boolean;
    };
}

export interface EmailTemplateBuilderData {
    id: number | null;
    name: string;
    subject: string;
    html_content: string;
    text_content: string;
    can: {
        update: boolean;
    };
}
