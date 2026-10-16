export interface BillingSubscription {
    status: string;
    trial_ends_at: string | null;
    plan: { id: number; name: string } | null;
}

export interface BillingPlanOption {
    id: number;
    name: string;
    price: string;
    billing_cycle: string;
}

export interface BillingInvoiceRow {
    id: number;
    invoice_number: string;
    status: string;
    total: string;
    due_date: string | null;
}

export interface BillingAlert {
    type: string;
    message: string;
}

export interface BillingPaymentRow {
    id: number;
    invoice_number: string | null;
    payment_method: string;
    amount: string;
    status: string;
    processed_at: string | null;
}

export interface BillingPaymentMethodSummary {
    gateway: string;
    label: string;
}
