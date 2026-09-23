<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $survey->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
<div class="max-w-xl mx-auto bg-white border rounded-lg p-6 space-y-4">
    <h1 class="text-2xl font-semibold">{{ $survey->title }}</h1>
    <p class="text-sm text-gray-600">{{ $survey->description }}</p>
    @if(session('success'))
        <p class="text-green-700 text-sm">{{ session('success') }}</p>
    @endif
    <form method="POST" action="{{ route('public.survey.submit', $survey) }}" class="space-y-4">
        @csrf
        <input type="email" name="respondent_email" class="w-full border rounded px-3 py-2" placeholder="Email (optional)">
        @foreach($survey->questions as $i => $question)
            <div>
                <label class="block text-sm font-medium mb-1">{{ $question->question }}@if($question->is_required) * @endif</label>
                <input type="hidden" name="answers[{{ $i }}][question_id]" value="{{ $question->id }}">
                @if(in_array($question->type, ['textarea']))
                    <textarea name="answers[{{ $i }}][response]" class="w-full border rounded px-3 py-2" @required($question->is_required)></textarea>
                @elseif(in_array($question->type, ['radio','select']) && is_array($question->options))
                    <select name="answers[{{ $i }}][response]" class="w-full border rounded px-3 py-2" @required($question->is_required)>
                        @foreach($question->options as $option)
                            <option value="{{ $option }}">{{ $option }}</option>
                        @endforeach
                    </select>
                @else
                    <input name="answers[{{ $i }}][response]" class="w-full border rounded px-3 py-2" @required($question->is_required)>
                @endif
            </div>
        @endforeach
        <button class="bg-blue-600 text-white px-4 py-2 rounded-md">Submit</button>
    </form>
</div>
</body>
</html>
