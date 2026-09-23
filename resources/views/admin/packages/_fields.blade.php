<input name="name" value="{{ old('name', $package->name ?? '') }}" required class="w-full rounded-md border-gray-300" placeholder="Name">
<input name="slug" value="{{ old('slug', $package->slug ?? '') }}" class="w-full rounded-md border-gray-300" placeholder="slug">
<input name="price" type="number" step="0.01" value="{{ old('price', $package->price ?? 0) }}" class="w-full rounded-md border-gray-300">
<select name="billing_cycle" class="w-full rounded-md border-gray-300">
    @foreach(['monthly','yearly'] as $cycle)
        <option value="{{ $cycle }}" @selected(old('billing_cycle', $package->billing_cycle ?? 'monthly')===$cycle)>{{ ucfirst($cycle) }}</option>
    @endforeach
</select>
<label class="text-sm"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package->is_active ?? true))> Active</label>
