<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        return view('admin.packages.index', [
            'packages' => SubscriptionPlan::orderBy('name')->get(),
        ]);
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);
        SubscriptionPlan::create($data);

        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function show(SubscriptionPlan $package)
    {
        return view('admin.packages.show', compact('package'));
    }

    public function edit(SubscriptionPlan $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, SubscriptionPlan $package)
    {
        $package->update($this->validated($request));

        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(SubscriptionPlan $package)
    {
        $package->delete();

        return redirect()->route('admin.packages.index')->with('success', 'Package deleted.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
