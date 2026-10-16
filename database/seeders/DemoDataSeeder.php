<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Channel;
use App\Models\Chatbot;
use App\Models\Contest;
use App\Models\LandingPage;
use App\Models\Organization;
use App\Models\Role;
use App\Models\Survey;
use App\Models\SurveyQuestion;
use App\Models\Task;
use App\Models\User;
use App\Models\Workflow;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::firstOrCreate(
            ['slug' => 'demo-company'],
            [
                'name' => 'Brightpath Marketing (demo)',
                'timezone' => 'UTC',
                'status' => 'active',
            ]
        );
        $organization->update([
            'name' => 'Brightpath Marketing (demo)',
            'status' => 'active',
        ]);

        $adminRole = Role::where('name', 'admin')->first();

        $demoUser = User::firstOrCreate(
            ['email' => 'demo@test.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password123'),
                'user_type' => 'customer',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        if ($adminRole) {
            $existing = $demoUser->organizations()->where('organizations.id', $organization->id)->exists();
            if (! $existing) {
                $demoUser->organizations()->attach($organization->id, ['role_id' => $adminRole->id]);
            } else {
                $demoUser->organizations()->updateExistingPivot($organization->id, ['role_id' => $adminRole->id]);
            }
        }

        $demoUser->syncRoles(['admin']);

        $platformAdmin = User::where('email', 'admin@test.com')->first();
        if ($platformAdmin && $adminRole && ! $platformAdmin->organizations()->where('organizations.id', $organization->id)->exists()) {
            $platformAdmin->organizations()->attach($organization->id, ['role_id' => $adminRole->id]);
        }

        $brand = Brand::firstOrCreate(
            ['organization_id' => $organization->id, 'name' => 'Northline Studio'],
            [
                'summary' => 'Fictional demo brand for marketing screenshots (no customer data).',
                'status' => 'active',
                'tone_of_voice' => 'professional',
            ]
        );
        $brand->update([
            'summary' => 'Fictional demo brand for marketing screenshots (no customer data).',
            'status' => 'active',
        ]);

        $campaignDefinitions = [
            [
                'name' => 'Q4 Product Launch',
                'description' => 'Multi-channel launch for the autumn product line.',
                'status' => 'active',
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addMonths(2),
            ],
            [
                'name' => 'Holiday Email Series',
                'description' => 'Seasonal nurture and promo emails (demo data).',
                'status' => 'active',
                'start_date' => now()->subDays(5),
                'end_date' => now()->addMonths(1),
            ],
            [
                'name' => 'Brand Awareness — Social',
                'description' => 'Paid social and organic posts across channels.',
                'status' => 'paused',
                'start_date' => now()->subMonth(),
                'end_date' => now()->addMonths(3),
            ],
            [
                'name' => 'Webinar Follow-up',
                'description' => 'Post-event emails and retargeting (fake contacts).',
                'status' => 'draft',
                'start_date' => now()->addWeek(),
                'end_date' => now()->addMonths(2),
            ],
            [
                'name' => 'Partner Co-marketing',
                'description' => 'Joint campaign with a fictional partner brand.',
                'status' => 'completed',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->subWeeks(1),
            ],
        ];

        $campaign = null;
        foreach ($campaignDefinitions as $definition) {
            $campaign = Campaign::firstOrCreate(
                ['organization_id' => $organization->id, 'name' => $definition['name']],
                [
                    'brand_id' => $brand->id,
                    'description' => $definition['description'],
                    'status' => $definition['status'],
                    'start_date' => $definition['start_date'],
                    'end_date' => $definition['end_date'],
                    'created_by' => $demoUser->id,
                ]
            );
        }

        $this->seedDemoTasks($organization, $demoUser);

        Chatbot::firstOrCreate(
            ['organization_id' => $organization->id, 'name' => 'Website Assistant'],
            [
                'description' => 'Demo chatbot for website visitors.',
                'welcome_message' => 'Hello! How can I help you today?',
                'is_active' => true,
                'created_by' => $demoUser->id,
            ]
        );

        Workflow::firstOrCreate(
            ['organization_id' => $organization->id, 'name' => 'Welcome Email Flow'],
            [
                'description' => 'Demo automation workflow.',
                'type' => 'automation',
                'is_active' => true,
                'created_by' => $demoUser->id,
            ]
        );

        $landingPage = LandingPage::firstOrCreate(
            ['slug' => 'demo-landing-page'],
            [
                'organization_id' => $organization->id,
                'name' => 'Demo Landing Page',
                'description' => 'Demo landing page for campaigns.',
                'status' => 'published',
                'is_active' => true,
                'html_content' => '<main style="font-family:sans-serif;padding:3rem;text-align:center"><h1>Demo Landing Page</h1><p>Published demo page for Marketing Manager.</p></main>',
                'created_by' => $demoUser->id,
            ]
        );
        $landingPage->update([
            'status' => 'published',
            'is_active' => true,
            'html_content' => $landingPage->html_content ?: '<main style="font-family:sans-serif;padding:3rem;text-align:center"><h1>Demo Landing Page</h1><p>Published demo page for Marketing Manager.</p></main>',
        ]);

        $survey = Survey::firstOrCreate(
            ['organization_id' => $organization->id, 'title' => 'Customer Feedback Survey'],
            [
                'description' => 'Demo survey for collecting feedback.',
                'status' => 'active',
                'created_by' => $demoUser->id,
            ]
        );
        $survey->update(['status' => 'active']);

        if ($survey->questions()->count() === 0) {
            SurveyQuestion::create([
                'survey_id' => $survey->id,
                'question' => 'How satisfied are you with Demo Brand?',
                'type' => 'text',
                'is_required' => false,
                'order' => 1,
            ]);
        }

        Channel::firstOrCreate(
            ['organization_id' => $organization->id, 'display_name' => 'Facebook Page'],
            [
                'type' => 'social',
                'platform' => 'facebook',
                'status' => 'active',
            ]
        );

        Contest::firstOrCreate(
            ['organization_id' => $organization->id, 'name' => 'Summer Giveaway'],
            [
                'campaign_id' => $campaign->id,
                'description' => 'Demo contest for the summer launch.',
                'prize' => 'Brand merch pack',
                'status' => 'active',
                'starts_at' => now(),
                'ends_at' => now()->addMonth(),
            ]
        );

        $this->seedAgency($organization, $adminRole);
    }

    private function seedDemoTasks(Organization $organization, User $demoUser): void
    {
        $taskDefinitions = [
            ['title' => 'Draft launch email #1', 'status' => 'todo', 'priority' => 'high'],
            ['title' => 'Approve social ad sets', 'status' => 'todo', 'priority' => 'medium'],
            ['title' => 'Brief agency on Q4 creative', 'status' => 'todo', 'priority' => 'low'],
            ['title' => 'Update landing page hero', 'status' => 'in_progress', 'priority' => 'high'],
            ['title' => 'Schedule webinar reminders', 'status' => 'in_progress', 'priority' => 'medium'],
            ['title' => 'Review Q4 budget spreadsheet', 'status' => 'in_progress', 'priority' => 'low'],
            ['title' => 'Publish blog post recap', 'status' => 'completed', 'priority' => 'medium'],
            ['title' => 'Send partner kickoff brief', 'status' => 'completed', 'priority' => 'low'],
            ['title' => 'Archive summer campaign assets', 'status' => 'completed', 'priority' => 'low'],
        ];

        foreach ($taskDefinitions as $definition) {
            Task::firstOrCreate(
                [
                    'organization_id' => $organization->id,
                    'title' => $definition['title'],
                ],
                [
                    'description' => 'Demo task for marketing screenshots (no real customer data).',
                    'status' => $definition['status'],
                    'priority' => $definition['priority'],
                    'assignee_id' => $demoUser->id,
                    'created_by' => $demoUser->id,
                    'due_date' => now()->addDays(7),
                    'completed_at' => $definition['status'] === 'completed' ? now()->subDay() : null,
                ]
            );
        }
    }

    private function seedAgency(Organization $organization, ?Role $adminRole): void
    {
        $agencyUser = User::firstOrCreate(
            ['email' => 'agency@test.com'],
            [
                'name' => 'Agency Admin',
                'password' => Hash::make('password123'),
                'user_type' => 'agency',
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );

        if (! $agencyUser->hasRole('agency')) {
            $agencyUser->assignRole('agency');
        }
        if (! $agencyUser->hasRole('agency_admin')) {
            $agencyUser->assignRole('agency_admin');
        }

        $agency = Agency::firstOrCreate(
            ['name' => 'Demo Agency'],
            [
                'owner_id' => $agencyUser->id,
                'status' => 'active',
            ]
        );

        if (! $agencyUser->agencies()->where('agencies.id', $agency->id)->exists()) {
            $agencyUser->agencies()->attach($agency->id, ['role' => 'admin']);
        }

        if (! $agency->clientOrganizations()->where('organizations.id', $organization->id)->exists()) {
            $agency->clientOrganizations()->attach($organization->id, ['status' => 'active']);
        }
    }
}
