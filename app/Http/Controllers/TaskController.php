<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTaskRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\AttachmentUploadService;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    protected AttachmentUploadService $attachmentUploadService;

    public function __construct(AttachmentUploadService $attachmentUploadService)
    {
        $this->attachmentUploadService = $attachmentUploadService;
    }

    public function store(TaskRequest $request)
    {
        try {
            $task = Task::create([
                'user_id' => Auth::user()->id,
                'subject' => $request->input('subject'),
                'description' => $request->input('description'),
                'start_date' => $request->input('start_date'),
                'due_date' => $request->input('due_date'),
                'status' => $request->input('status'),
                'priority' => $request->input('priority'),
            ]);
            if ($request->has('notes')) {
                $task->notes()->createMany($request->input('notes'));
                $task->load('notes');

                foreach ($request->input('notes') as $key => $value) {
                    $note = $task->notes->get($key);
                    if ($request->file('notes.' . $key . '.attachement')) {
                        $this->attachmentUploadService->uploadNoteAttachments($request->file('notes.' . $key . '.attachement'), $note->id);
                    }
                }
            }
            return response()->json([
                'task' => (new TaskResource($task)),
                'message' => trans('Task has been created successfully')
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    public function getTasks(GetTaskRequest $request)
    {
        $perPage = $request->input('per_page', 10); // Default to 10 if not provided
        $page = $request->input('page', 1);

        return TaskResource::collection(
            Task::withCount('notes')
                ->search($request)
                ->paginate($perPage, ['*'], 'page', $page)
        );
    }
}
