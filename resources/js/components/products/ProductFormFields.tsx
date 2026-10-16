import { InputError } from '@/components/InputError';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { ProductDetail, SelectOption } from '@/types/products';

export interface ProductFormData {
    name: string;
    sku: string;
    description: string;
    price: string;
    stock: string;
    category_id: string;
    brand_id: string;
    status: string;
    image: File | null;
}

export function productFormDefaults(product: ProductDetail | null): ProductFormData {
    return {
        name: product?.name ?? '',
        sku: product?.sku ?? '',
        description: product?.description ?? '',
        price: product?.price !== undefined && product?.price !== null ? String(product.price) : '',
        stock: product?.stock !== undefined ? String(product.stock) : '0',
        category_id: product?.category_id ? String(product.category_id) : '',
        brand_id: product?.brand_id ? String(product.brand_id) : '',
        status: product?.status ?? 'draft',
        image: null,
    };
}

interface ProductFormFieldsProps {
    data: ProductFormData;
    errors: Record<string, string | undefined>;
    setData: (key: keyof ProductFormData, value: ProductFormData[keyof ProductFormData]) => void;
    categories: SelectOption[];
    brands: SelectOption[];
}

const selectClass =
    'mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:outline-none focus:ring-1 focus:ring-primary-500';

export function ProductFormFields({
    data,
    errors,
    setData,
    categories,
    brands,
}: ProductFormFieldsProps) {
    return (
        <div className="space-y-4">
            <div>
                <Label htmlFor="name">Name</Label>
                <Input
                    id="name"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                />
                <InputError message={errors.name} />
            </div>
            <div>
                <Label htmlFor="sku">SKU</Label>
                <Input
                    id="sku"
                    value={data.sku}
                    onChange={(e) => setData('sku', e.target.value)}
                    required
                />
                <InputError message={errors.sku} />
            </div>
            <div>
                <Label htmlFor="description">Description</Label>
                <textarea
                    id="description"
                    rows={4}
                    value={data.description}
                    onChange={(e) => setData('description', e.target.value)}
                    className={selectClass}
                />
                <InputError message={errors.description} />
            </div>
            <div className="grid gap-4 sm:grid-cols-2">
                <div>
                    <Label htmlFor="price">Price</Label>
                    <Input
                        id="price"
                        type="number"
                        min="0"
                        step="0.01"
                        value={data.price}
                        onChange={(e) => setData('price', e.target.value)}
                        required
                    />
                    <InputError message={errors.price} />
                </div>
                <div>
                    <Label htmlFor="stock">Stock</Label>
                    <Input
                        id="stock"
                        type="number"
                        min="0"
                        value={data.stock}
                        onChange={(e) => setData('stock', e.target.value)}
                    />
                    <InputError message={errors.stock} />
                </div>
            </div>
            <div>
                <Label htmlFor="category_id">Category</Label>
                <select
                    id="category_id"
                    value={data.category_id}
                    onChange={(e) => setData('category_id', e.target.value)}
                    className={selectClass}
                >
                    <option value="">None</option>
                    {categories.map((category) => (
                        <option key={category.id} value={category.id}>
                            {category.name}
                        </option>
                    ))}
                </select>
                <InputError message={errors.category_id} />
            </div>
            <div>
                <Label htmlFor="brand_id">Brand</Label>
                <select
                    id="brand_id"
                    value={data.brand_id}
                    onChange={(e) => setData('brand_id', e.target.value)}
                    className={selectClass}
                >
                    <option value="">None</option>
                    {brands.map((brand) => (
                        <option key={brand.id} value={brand.id}>
                            {brand.name}
                        </option>
                    ))}
                </select>
                <InputError message={errors.brand_id} />
            </div>
            <div>
                <Label htmlFor="status">Status</Label>
                <select
                    id="status"
                    value={data.status}
                    onChange={(e) => setData('status', e.target.value)}
                    className={selectClass}
                >
                    <option value="draft">draft</option>
                    <option value="active">active</option>
                    <option value="inactive">inactive</option>
                </select>
                <InputError message={errors.status} />
            </div>
            <div>
                <Label htmlFor="image">Image</Label>
                <input
                    id="image"
                    type="file"
                    accept="image/*"
                    className="mt-1 block w-full text-sm text-gray-600"
                    onChange={(e) => setData('image', e.target.files?.[0] ?? null)}
                />
                <InputError message={errors.image} />
            </div>
        </div>
    );
}
