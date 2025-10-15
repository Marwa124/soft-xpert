<?php

namespace App\Services;

use App\Enums\TaskStatus;

class TaskService
{
    public function __construct()
    {
        //
    }

    public function canBeCompleted($model): bool
    {
        $relatedTasks = $model->relatedTasks()->get(['status', 'id']);
        $result = $relatedTasks->map(function($relatedTask) {
            if($relatedTask->status !== TaskStatus::COMPLETED){
                return false;
            }
        });

        return $result->contains(false) ? false : true;
    }

}