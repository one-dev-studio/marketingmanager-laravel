export interface OrganizationSettingsForm {
    name: string;
    timezone: string;
    locale: string;
    settings: {
        slack_webhook: string;
        notify_email: boolean;
        notify_in_app: boolean;
        notify_push: boolean;
        require_2fa: boolean;
    };
}
