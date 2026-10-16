export interface ProductPermissions {
    update: boolean;
    delete: boolean;
}

export interface ProductListItem {
    id: number;
    name: string;
    sku: string;
    price: string | number;
    stock: number;
    status: string;
    brand_name: string | null;
    category_name: string | null;
    can: ProductPermissions;
}

export interface ProductDetail {
    id: number;
    name: string;
    sku: string;
    description: string | null;
    price: string | number;
    stock: number;
    status: string;
    brand_id: number | null;
    category_id: number | null;
    image: string | null;
    brand_name: string | null;
    category_name: string | null;
    variants_count: number;
    can: ProductPermissions;
}

export interface SelectOption {
    id: number;
    name: string;
}
