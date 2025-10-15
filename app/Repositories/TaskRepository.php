<?php

namespace App\Repositories;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskRepository 
{
    /**
     * Get all tasks with optional filters.
     *
     * @param  Request  $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllTasks(Request $request)
    {
        $query = Task::query();

        if(auth()->user()->hasRole('Actor')){
            $query->where('assignee_id', auth()->id());
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('due_date')) {
            $query->where('due_date', $request->input('due_date'));
        }

        if ($request->filled('assignee_id')) {
            $query->where('assignee_id', $request->input('assignee_id'));
        }

        return $query->latest()->paginate($request->input('per_page') ?? 5);
    }
}
