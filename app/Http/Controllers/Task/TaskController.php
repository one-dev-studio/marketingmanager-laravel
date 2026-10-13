<?php

declare(strict_types=1);

namespace App\Http\Controllers\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\CreateTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\TaskTemplate;
use App\Models\User;
use App\Services\Task\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService
    ) {}

    public function index(Request $request, string $organizationId)
    {
        $query = Task::where('organization_id', $organizationId)
            ->with(['assignee', 'creator', 'project', 'comments.user', 'attachments']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('assignee_id')) {
            $query->where('assignee_id', $request->assignee_id);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($this->wantsJson($request)) {
            $tasks = $query->orderBy('created_at', 'desc')->paginate();

            return TaskResource::collection($tasks);
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();

        $members = User::whereHas('organizations', fn ($q) => $q->where('organizations.id', $organizationId))->get(['id', 'name']);
        $templates = TaskTemplate::where('organization_id', $organizationId)->get();

        return Inertia::render('Tasks/Index', [
            'organizationId' => $organizationId,
            'members' => $members->map(fn ($user) => ['id' => $user->id, 'name' => $user->name])->values()->all(),
            'templates' => $templates->map(fn ($template) => [
                'id' => $template->id,
                'name' => $template->name,
            ])->values()->all(),
            'tasks' => $tasks
                ->map(fn (Task $task) => (new TaskResource($task))->toArray($request))
                ->values()
                ->all(),
        ]);
    }

    public function store(CreateTaskRequest $request, string $organizationId): JsonResponse
    {
        $task = $this->taskService->createTask(
            $request->validated(),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'creator', 'project'])),
            'message' => 'Task created successfully.',
        ], 201);
    }

    public function show(Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $task->load(['assignee', 'creator', 'project', 'comments.user', 'attachments.uploader']);

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task),
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $task = $this->taskService->updateTask(
            $task,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'creator', 'project'])),
            'message' => 'Task updated successfully.',
        ]);
    }

    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function assign(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'assignee_id' => ['nullable', 'exists:users,id'],
        ]);

        $task = $this->taskService->assignTask(
            $task,
            $request->assignee_id
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'creator', 'project'])),
            'message' => 'Task assigned successfully.',
        ]);
    }

    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'status' => ['required', 'in:todo,in_progress,review,completed,cancelled'],
        ]);

        $task = $this->taskService->updateTaskStatus(
            $task,
            $request->status
        );

        return response()->json([
            'success' => true,
            'data' => new TaskResource($task->load(['assignee', 'creator', 'project'])),
            'message' => 'Task status updated successfully.',
        ]);
    }

    public function addComment(Request $request, Task $task): JsonResponse
    {
        $this->authorize('view', $task);

        $request->validate([
            'comment' => ['required', 'string', 'max:5000'],
        ]);

        $comment = $this->taskService->addComment(
            $task,
            $request->comment,
            $request->user()
        );

        return response()->json([
            'success' => true,
            'data' => $comment->load('user'),
            'message' => 'Comment added successfully.',
        ], 201);
    }

    public function addAttachment(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate([
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $attachment = $this->taskService->addAttachment(
            $task,
            $request->file('file'),
            $request->user()
        );

        return response()->json([
            'success' => true,
            'data' => $attachment->load('uploader'),
            'message' => 'Attachment added successfully.',
        ], 201);
    }

    public function deleteAttachment(TaskAttachment $attachment): JsonResponse
    {
        $this->authorize('delete', $attachment->task);

        $this->taskService->deleteAttachment($attachment);

        return response()->json([
            'success' => true,
            'message' => 'Attachment deleted successfully.',
        ]);
    }
}
