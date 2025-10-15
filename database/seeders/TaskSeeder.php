<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = Task::factory(10)->create();

        foreach ($tasks as $task) {
            if ($task->id > 1) {
                $relatedTaskId = fake()->randomElement($tasks->pluck('id')->toArray());
                
                $task->update(['related_task_id' => $relatedTaskId]);
            }
        }
    }
}
