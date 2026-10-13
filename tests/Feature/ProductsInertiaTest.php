<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class ProductsInertiaTest extends TestCase
{
    public function test_products_index_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        Product::query()->create([
            'organization_id' => $organization->id,
            'name' => 'Inertia Widget',
            'sku' => 'SKU-INERTIA-'.uniqid(),
            'price' => 19.99,
            'stock' => 5,
            'status' => 'active',
        ]);

        $response = $this->get(route('main.products.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Products/Index')
            ->has('products.data', 1)
            ->where('products.data.0.name', 'Inertia Widget')
            ->has('sidebar')
            ->has('appShell')
            ->where('can.create', true));
    }

    public function test_products_create_renders_form_page(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.products.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Products/Create'));
    }
}
