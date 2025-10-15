<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\ApiResource;
use App\Models\Task;
use App\Repositories\TaskRepository;
use App\Services\TaskService;

class TaskController extends Controller
{
    public function __construct(public TaskService $taskService, public TaskRepository $taskRepo)
    {
        //
    }

    public function index(FilterRequest $request)
    {        
        $tasks = $this->taskRepo->getAllTasks($request);
        return response()->json(new ApiResource($tasks, 200, 'Tasks retrieved successfully'));
    }

    public function store(TaskRequest $request)
    {
        $task = Task::create($request->validated() + ['created_by' => auth()->id()]);

        return response()->json(new ApiResource($task, 201, 'Task created successfully'));
    }

    public function show(Task $task)
    {
        if(auth()->user()->hasRole('Actor') && $task->assignee_id !== auth()->id()){
            return response()->json(new ApiResource([], 403, 'Unauthorized to view this task'));
        }
        
        return response()->json(new ApiResource($task, 200, 'Task retrieved successfully'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return response()->json(new ApiResource($task, 200, 'Task updated successfully'));
    }

    public function updateStatus(UpdateTaskRequest $request, Task $task)
    {
        if(auth()->user()->hasRole('Actor') && $task->assignee_id !== auth()->id()){
            return response()->json(new ApiResource([], 403, 'Unauthorized to update this task'));
        }

        if (!$this->taskService->canBeCompleted($task)) {
            return response()->json(new ApiResource([], 200, "Cannot update status. Related tasks are not completed."));
        }

        $task->update($request->only('status'));

        return response()->json(new ApiResource($task, 200, 'Task status updated successfully'));
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(new ApiResource([], 200, 'Task deleted successfully'));
    }
}
