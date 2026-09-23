<?php

namespace App\Http\Controllers\EmailMarketing;

use App\Http\Controllers\Controller;
use App\Http\Requests\EmailMarketing\CreateEmailTemplateRequest;
use App\Http\Resources\EmailMarketing\EmailTemplateResource;
use App\Models\EmailTemplate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class EmailTemplateController extends Controller
{
    public function index(Request $request, string $organizationId)
    {
        $query = EmailTemplate::where(function ($q) use ($organizationId) {
            $q->where('organization_id', $organizationId)
                ->orWhere('is_public', true);
        });

        $templates = $query->orderBy('created_at', 'desc')->paginate();

        if ($this->wantsJson($request)) {
            return EmailTemplateResource::collection($templates);
        }

        return view('email.templates.index', [
            'title' => 'Email Templates',
            'organizationId' => $organizationId,
            'templates' => $templates,
        ]);
    }

    public function builder(Request $request, string $organizationId, ?EmailTemplate $emailTemplate = null)
    {
        return view('email.templates.builder', [
            'title' => 'Template Builder',
            'organizationId' => $organizationId,
            'template' => $emailTemplate,
        ]);
    }

    public function store(CreateEmailTemplateRequest $request): JsonResponse
    {
        $template = EmailTemplate::create([
            'organization_id' => auth()->user()->primaryOrganization()->id,
            'name' => $request->name,
            'description' => $request->description,
            'subject' => $request->subject,
            'html_content' => $request->html_content,
            'text_content' => $request->text_content,
            'variables' => $request->variables ?? [],
            'category' => $request->category ?? 'custom',
            'is_public' => $request->boolean('is_public', false),
            'created_by' => $request->user()->id,
        ]);

        if ($this->wantsJson($request)) {
            return response()->json([
                'success' => true,
                'data' => new EmailTemplateResource($template),
                'message' => 'Email template created successfully.',
            ], 201);
        }

        return redirect()
            ->route('main.email-marketing.templates.builder', [
                'organizationId' => $request->route('organizationId'),
                'emailTemplate' => $template,
            ])
            ->with('success', 'Template saved.');
    }

    public function show(EmailTemplate $emailTemplate): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new EmailTemplateResource($emailTemplate),
        ]);
    }

    public function update(CreateEmailTemplateRequest $request, EmailTemplate $emailTemplate): JsonResponse
    {
        $emailTemplate->update([
            'name' => $request->name,
            'description' => $request->description,
            'subject' => $request->subject,
            'html_content' => $request->html_content,
            'text_content' => $request->text_content,
            'variables' => $request->variables ?? $emailTemplate->variables,
            'category' => $request->category ?? $emailTemplate->category,
            'is_public' => $request->boolean('is_public', $emailTemplate->is_public),
        ]);

        return response()->json([
            'success' => true,
            'data' => new EmailTemplateResource($emailTemplate),
            'message' => 'Email template updated successfully.',
        ]);
    }

    public function destroy(EmailTemplate $emailTemplate): JsonResponse
    {
        $emailTemplate->delete();

        return response()->json([
            'success' => true,
            'message' => 'Email template deleted successfully.',
        ]);
    }

    public function render(Request $request, EmailTemplate $emailTemplate): JsonResponse
    {
        $request->validate([
            'data' => ['required', 'array'],
        ]);

        $rendered = $emailTemplate->render($request->data);

        return response()->json([
            'success' => true,
            'data' => $rendered,
        ]);
    }
}

