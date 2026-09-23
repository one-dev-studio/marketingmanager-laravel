<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\CreateContactRequest;
use App\Http\Requests\EmailMarketing\UpdateContactRequest;
use App\Http\Requests\EmailMarketing\ImportContactsRequest;
use App\Http\Resources\EmailMarketing\ContactResource;
use App\Models\Contact;
use App\Models\ContactList;
use App\Services\EmailMarketing\ContactService;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct(
        private ContactService $contactService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $query = Contact::where('organization_id', $organizationId)
            ->with(['tags', 'contactLists']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('contact_list_id')) {
            $query->whereHas('contactLists', function ($q) use ($request) {
                $q->where('contact_lists.id', $request->contact_list_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('company', 'like', "%{$search}%");
            });
        }

        $contacts = $query->orderBy('created_at', 'desc')->paginate();
        $lists = ContactList::where('organization_id', $organizationId)->orderBy('name')->get();

        if ($this->wantsJson($request)) {
            return ContactResource::collection($contacts);
        }

        return view('contacts.index', [
            'title' => 'Contacts',
            'organizationId' => $organizationId,
            'contacts' => $contacts,
            'lists' => $lists,
            'filters' => $request->only(['search', 'status', 'contact_list_id']),
        ]);
    }

    public function create(Request $request, string $organizationId)
    {
        return view('contacts.form', [
            'title' => 'Add Contact',
            'organizationId' => $organizationId,
            'contact' => null,
            'lists' => ContactList::where('organization_id', $organizationId)->orderBy('name')->get(),
        ]);
    }

    public function store(CreateContactRequest $request, string $organizationId)
    {
        $contact = $this->contactService->createContact($request->validated(), $request->user());

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ContactResource($contact),
                'message' => 'Contact created successfully.',
            ], 201);
        }

        return redirect()
            ->route('main.email-marketing.contacts.show', ['organizationId' => $organizationId, 'contact' => $contact])
            ->with('success', 'Contact created.');
    }

    public function show(Request $request, string $organizationId, Contact $contact)
    {
        $contact->load(['tags', 'contactLists', 'activities.emailCampaign']);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ContactResource($contact),
            ]);
        }

        $duplicates = $this->contactService->findDuplicates($contact);

        return view('contacts.show', [
            'title' => $contact->full_name,
            'organizationId' => $organizationId,
            'contact' => $contact,
            'duplicates' => $duplicates,
        ]);
    }

    public function update(UpdateContactRequest $request, string $organizationId, Contact $contact)
    {
        $contact = $this->contactService->updateContact($contact, $request->validated());

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ContactResource($contact),
                'message' => 'Contact updated successfully.',
            ]);
        }

        return back()->with('success', 'Contact updated.');
    }

    public function destroy(Request $request, string $organizationId, Contact $contact)
    {
        $this->contactService->deleteContact($contact);

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Contact deleted successfully.']);
        }

        return redirect()
            ->route('main.email-marketing.contacts.index', ['organizationId' => $organizationId])
            ->with('success', 'Contact deleted.');
    }

    public function import(ImportContactsRequest $request, string $organizationId)
    {
        try {
            $result = $this->contactService->importContactsFromFile(
                $request->file('file'),
                $request->user(),
                [
                    'contact_list_ids' => $request->contact_list_ids ?? [],
                    'skip_duplicates' => $request->boolean('skip_duplicates', true),
                    'source' => $request->source ?? 'import',
                ]
            );

            if ($this->wantsJson($request)) {
                return response()->json([
                    'success' => true,
                    'data' => $result,
                    'message' => "Imported {$result['imported']} contacts successfully.",
                ]);
            }

            return back()->with('success', "Imported {$result['imported']} contacts.");
        } catch (\Exception $e) {
            if ($this->wantsJson($request)) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
            }
            return back()->with('error', $e->getMessage());
        }
    }

    public function findDuplicates(string $organizationId, Contact $contact)
    {
        return response()->json([
            'success' => true,
            'data' => ContactResource::collection($this->contactService->findDuplicates($contact)),
        ]);
    }

    public function merge(Request $request, string $organizationId, Contact $contact)
    {
        $request->validate([
            'contact_ids' => ['required', 'array', 'min:1'],
            'contact_ids.*' => ['exists:contacts,id'],
        ]);

        $mergedContact = $this->contactService->mergeContacts($contact, $request->contact_ids);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new ContactResource($mergedContact),
                'message' => 'Contacts merged successfully.',
            ]);
        }

        return back()->with('success', 'Contacts merged.');
    }

    public function subscribe(Request $request, string $organizationId, Contact $contact)
    {
        $contact->subscribe();
        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'data' => new ContactResource($contact)]);
        }
        return back()->with('success', 'Contact subscribed.');
    }

    public function unsubscribe(Request $request, string $organizationId, Contact $contact)
    {
        $contact->unsubscribe();
        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'data' => new ContactResource($contact)]);
        }
        return back()->with('success', 'Contact unsubscribed.');
    }

    public function exportData(string $organizationId, Contact $contact)
    {
        $data = $this->contactService->exportContactData($contact);

        return response()->json(['success' => true, 'data' => $data])
            ->header('Content-Disposition', 'attachment; filename="contact-' . $contact->id . '.json"');
    }

    public function deleteData(Request $request, string $organizationId, Contact $contact)
    {
        $this->contactService->deleteContactData($contact);

        if ($this->wantsJson($request)) {
            return response()->json(['success' => true, 'message' => 'Contact data deleted successfully.']);
        }

        return redirect()
            ->route('main.email-marketing.contacts.index', ['organizationId' => $organizationId])
            ->with('success', 'Contact data deleted (GDPR).');
    }
}
