<?php

namespace App\Http\Controllers\Survey;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use App\Services\Survey\SurveyService;
use Illuminate\Http\Request;

class PublicSurveyController extends Controller
{
    public function __construct(
        private SurveyService $surveyService
    ) {}

    public function show(Survey $survey)
    {
        abort_unless(in_array($survey->status, ['active', 'published'], true), 404);

        return view('surveys.public', [
            'survey' => $survey->load('questions'),
        ]);
    }

    public function submit(Request $request, Survey $survey)
    {
        abort_unless(in_array($survey->status, ['active', 'published'], true), 404);

        $validated = $request->validate([
            'respondent_email' => ['nullable', 'email'],
            'answers' => ['required', 'array'],
            'answers.*.question_id' => ['required', 'integer'],
            'answers.*.response' => ['required'],
        ]);

        $this->surveyService->submitResponse(
            $survey,
            $validated['answers'],
            $validated['respondent_email'] ?? null
        );

        return redirect()->route('public.survey', $survey)
            ->with('success', 'Thank you for your response.');
    }
}
