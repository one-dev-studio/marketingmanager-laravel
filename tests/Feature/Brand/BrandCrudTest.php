<?php

namespace Tests\Feature\Brand;

use App\Models\Brand;
use App\Models\Organization;
use App\Models\User;
use App\Services\AI\ContentGenerationService;
use App\Models\AiGeneration;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BrandCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
    }

    public function testIndexReturnsBrandsView(): void
    {
        Brand::factory()->count(2)->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/brands");

        $response->assertOk();
        $response->assertViewIs('brands.index');
        $response->assertSee('Add Brand');
    }

    public function testJsonIndexReturnsBrandCollection(): void
    {
        Brand::factory()->create([
            'organization_id' => $this->organization->id,
            'name' => 'Json Brand',
        ]);

        $response = $this->getJson("/main/{$this->organization->id}/brands");

        $response->assertOk();
        $response->assertJsonFragment(['name' => 'Json Brand']);
    }

    public function testUserCanCreateBrand(): void
    {
        $response = $this->post("/main/{$this->organization->id}/brands", [
            'name' => 'New Brand',
            'summary' => 'A test brand',
            'tone_of_voice' => 'friendly',
            'keywords' => 'eco, coffee',
            'avoid_keywords' => 'cheap',
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brands', [
            'name' => 'New Brand',
            'organization_id' => $this->organization->id,
        ]);

        $brand = Brand::where('name', 'New Brand')->first();
        $this->assertSame(['eco', 'coffee'], $brand->keywords);
        $this->assertSame(['cheap'], $brand->avoid_keywords);
    }

    public function testUserCanViewBrand(): void
    {
        $brand = Brand::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/brands/{$brand->id}");

        $response->assertOk();
        $response->assertViewIs('brands.show');
        $response->assertViewHas('brand');
    }

    public function testUserCanUpdateBrand(): void
    {
        $brand = Brand::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->put("/main/{$this->organization->id}/brands/{$brand->id}", [
            'name' => 'Updated Brand Name',
            'summary' => $brand->summary,
            'status' => $brand->status,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('brands', [
            'id' => $brand->id,
            'name' => 'Updated Brand Name',
        ]);
    }

    public function testUserCanDeleteBrand(): void
    {
        $brand = Brand::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->delete("/main/{$this->organization->id}/brands/{$brand->id}");

        $response->assertRedirect(route('main.brands.index', ['organizationId' => $this->organization->id]));
        $this->assertDatabaseMissing('brands', [
            'id' => $brand->id,
        ]);
    }

    public function testViewerCannotCreateBrand(): void
    {
        [$viewer] = $this->createOrganizationAdmin(
            organization: $this->organization,
            tenantRoleName: 'viewer',
            spatieRoleName: 'viewer',
        );

        $this->actingAs($viewer);

        $this->get("/main/{$this->organization->id}/brands")
            ->assertOk()
            ->assertDontSee('Add Brand');

        $this->post("/main/{$this->organization->id}/brands", [
            'name' => 'Forbidden Brand',
        ])->assertForbidden();
    }

    public function testChooseNamePageIsAvailable(): void
    {
        $response = $this->get("/main/{$this->organization->id}/brands/choose-name");

        $response->assertOk();
        $response->assertViewIs('brands.choose-name');
    }

    public function testFactoryStoresKeywordsAsArray(): void
    {
        $brand = Brand::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $this->assertIsArray($brand->keywords);
        $this->assertNotEmpty($brand->keywords);
    }

    public function testGenerateConceptFillsStructuredFields(): void
    {
        $generation = new AiGeneration([
            'generated_content' => json_encode([
                'summary' => 'A modern coffee brand.',
                'audience' => 'Urban professionals',
                'guidelines' => 'Warm and concise.',
                'tone_of_voice' => 'friendly',
                'keywords' => ['coffee', 'roast'],
                'avoid_keywords' => ['cheap'],
            ]),
        ]);

        $this->mock(ContentGenerationService::class, function ($mock) use ($generation) {
            $mock->shouldReceive('generateContent')->once()->andReturn($generation);
        });

        $response = $this->postJson("/main/{$this->organization->id}/brands/generate-concept", [
            'name' => 'Roastly',
            'industry' => 'Coffee',
            'keywords' => ['bean'],
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.summary', 'A modern coffee brand.');
        $response->assertJsonPath('data.tone_of_voice', 'friendly');
        $response->assertJsonPath('data.keywords.0', 'coffee');
    }

    public function testUserCannotAccessOtherOrganizationBrands(): void
    {
        $otherOrganization = Organization::factory()->create();
        $brand = Brand::factory()->create([
            'organization_id' => $otherOrganization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/brands/{$brand->id}");

        $this->assertTrue(in_array($response->status(), [403, 404], true));
    }
}
