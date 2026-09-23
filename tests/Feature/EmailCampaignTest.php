<?php

namespace Tests\Feature;

use App\Http\Controllers\EmailMarketing\EmailCampaignController;
use App\Models\ContactList;
use App\Models\EmailCampaign;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class EmailCampaignTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Organization $organization;

    protected function setUp(): void
    {
        parent::setUp();
        
        [$this->user, $this->organization] = $this->actingAsOrganizationAdmin();
    }

    public function testUserCanCreateEmailCampaign(): void
    {
        $contactList = ContactList::factory()->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->post("/main/{$this->organization->id}/email-marketing/campaigns", [
            'name' => 'Test Email Campaign',
            'subject' => 'Test Subject',
            'from_email' => 'sender@example.com',
            'contact_list_ids' => [$contactList->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('email_campaigns', [
            'name' => 'Test Email Campaign',
            'organization_id' => $this->organization->id,
        ]);
    }

    public function testUserCanViewEmailCampaigns(): void
    {
        EmailCampaign::factory()->count(3)->create([
            'organization_id' => $this->organization->id,
        ]);

        $response = $this->get("/main/{$this->organization->id}/email-marketing/campaigns");

        $response->assertStatus(200);
    }

    public function testUserCanSendEmailCampaign(): void
    {
        $campaign = EmailCampaign::factory()->create([
            'organization_id' => $this->organization->id,
            'status' => 'draft',
            'total_recipients' => 25,
        ]);

        $campaign->refresh();
        $this->assertTrue($campaign->canSend());

        Queue::fake();

        $request = Request::create('/', 'POST');
        $request->headers->set('Accept', 'application/json');
        $request->setUserResolver(fn () => $this->user);

        $response = app(EmailCampaignController::class)->send(
            $request,
            (string) $this->organization->id,
            $campaign->fresh(),
        );

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertDatabaseHas('email_campaigns', [
            'id' => $campaign->id,
            'status' => 'sending',
        ]);
    }
}

