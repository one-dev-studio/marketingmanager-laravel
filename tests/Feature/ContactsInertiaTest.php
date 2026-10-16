<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Contact;
use Tests\TestCase;

class ContactsInertiaTest extends TestCase
{
    public function test_contacts_index_renders_inertia_with_shell_props(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        Contact::query()->create([
            'organization_id' => $organization->id,
            'email' => 'inertia-'.uniqid().'@example.com',
            'first_name' => 'Inertia',
            'last_name' => 'Contact',
            'status' => 'active',
        ]);

        $response = $this->get(route('main.email-marketing.contacts.index', [
            'organizationId' => $organization->id,
        ]));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Contacts/Index')
            ->has('contacts.data', 1)
            ->where('contacts.data.0.full_name', 'Inertia Contact')
            ->has('sidebar')
            ->has('appShell'));
    }

    public function test_contacts_create_renders_form_page(): void
    {
        [, $organization] = $this->actingAsOrganizationAdmin();

        $this->get(route('main.email-marketing.contacts.create', [
            'organizationId' => $organization->id,
        ]))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Contacts/Create'));
    }
}
