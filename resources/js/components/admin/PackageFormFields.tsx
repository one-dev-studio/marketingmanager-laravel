export interface PackageFormData {
    name: string;
    slug: string;
    price: string;
    billing_cycle: string;
    is_active: boolean;
}

interface PackageFormFieldsProps {
    form: {
        data: PackageFormData;
        setData: (key: keyof PackageFormData, value: PackageFormData[keyof PackageFormData]) => void;
    };
}

const billingCycles = ['monthly', 'yearly'];

export function PackageFormFields({ form }: PackageFormFieldsProps) {
    return (
        <div className="space-y-4">
            <div>
                <label className="block text-sm font-medium text-gray-700" htmlFor="name">Name</label>
                <input
                    id="name"
                    name="name"
                    required
                    value={form.data.name}
                    onChange={(event) => form.setData('name', event.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                />
            </div>
            <div>
                <label className="block text-sm font-medium text-gray-700" htmlFor="slug">Slug</label>
                <input
                    id="slug"
                    name="slug"
                    value={form.data.slug}
                    onChange={(event) => form.setData('slug', event.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                />
            </div>
            <div>
                <label className="block text-sm font-medium text-gray-700" htmlFor="price">Price</label>
                <input
                    id="price"
                    name="price"
                    type="number"
                    step="0.01"
                    required
                    value={form.data.price}
                    onChange={(event) => form.setData('price', event.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                />
            </div>
            <div>
                <label className="block text-sm font-medium text-gray-700" htmlFor="billing_cycle">Billing cycle</label>
                <select
                    id="billing_cycle"
                    name="billing_cycle"
                    value={form.data.billing_cycle}
                    onChange={(event) => form.setData('billing_cycle', event.target.value)}
                    className="mt-1 w-full rounded-md border border-gray-300 px-3 py-2"
                >
                    {billingCycles.map((cycle) => (
                        <option key={cycle} value={cycle}>{cycle.charAt(0).toUpperCase() + cycle.slice(1)}</option>
                    ))}
                </select>
            </div>
            <label className="flex items-center gap-2 text-sm text-gray-700">
                <input
                    type="checkbox"
                    name="is_active"
                    checked={form.data.is_active}
                    onChange={(event) => form.setData('is_active', event.target.checked)}
                />
                Active
            </label>
        </div>
    );
}
