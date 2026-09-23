<?php

namespace Tests\Feature;

use App\Models\AiGeneration;
use App\Models\GeneratedImage;
use App\Models\Organization;
use App\Models\User;
use App\Services\AI\ContentGenerationService;
use App\Services\AI\ImageGenerationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AiGenerationTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();

        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
    }

    public function testUserCanGenerateSocialMediaPost(): void
    {
        $generation = new AiGeneration([
            'generated_content' => 'Launch post copy',
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'type' => 'content',
            'provider' => 'openai',
            'model' => 'gpt-4',
            'prompt' => 'Product Launch',
            'status' => 'completed',
        ]);

        $this->mock(ContentGenerationService::class, function ($mock) use ($generation) {
            $mock->shouldReceive('generateSocialMediaPost')->once()->andReturn($generation);
        });

        $response = $this->postJson("/main/{$this->organization->id}/ai/content/social-media", [
            'platform' => 'facebook',
            'topic' => 'Product Launch',
            'tone' => 'professional',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
        $response->assertJsonStructure(['success', 'data']);
    }

    public function testUserCanGenerateEmailTemplate(): void
    {
        $generation = new AiGeneration([
            'generated_content' => json_encode(['subject' => 'Welcome', 'body' => 'Hello there']),
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'type' => 'email',
            'provider' => 'openai',
            'model' => 'gpt-4',
            'prompt' => 'onboarding',
            'status' => 'completed',
        ]);

        $this->mock(ContentGenerationService::class, function ($mock) use ($generation) {
            $mock->shouldReceive('generateEmailTemplate')->once()->andReturn($generation);
        });

        $response = $this->postJson("/main/{$this->organization->id}/ai/content/email", [
            'purpose' => 'onboarding',
            'audience' => 'new customers',
            'tone' => 'friendly',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
    }

    public function testUserCanGenerateImage(): void
    {
        $image = new GeneratedImage([
            'image_url' => 'https://example.com/image.png',
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'provider' => 'openai',
            'model' => 'dall-e-3',
            'prompt' => 'A modern office space',
        ]);

        $this->mock(ImageGenerationService::class, function ($mock) use ($image) {
            $mock->shouldReceive('generateImage')->once()->andReturn($image);
        });

        $response = $this->postJson("/main/{$this->organization->id}/ai/images/generate", [
            'prompt' => 'A modern office space',
            'style' => 'realistic',
            'size' => '1024x1024',
        ]);

        $response->assertOk();
        $response->assertJsonPath('success', true);
    }

    public function testAiGenerationTracksUsage(): void
    {
        $generation = AiGeneration::create([
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
            'type' => 'content',
            'provider' => 'openai',
            'model' => 'gpt-4',
            'prompt' => 'Test',
            'generated_content' => 'content',
            'status' => 'completed',
        ]);

        app(\App\Services\AI\AiUsageTrackingService::class)->logUsage(
            $this->organization,
            $this->user,
            $generation,
            'openai',
            'gpt-4',
            'content',
            42,
            0.01,
        );

        $this->assertDatabaseHas('ai_usage_logs', [
            'organization_id' => $this->organization->id,
            'user_id' => $this->user->id,
        ]);
    }
}
