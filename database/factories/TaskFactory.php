<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        $userIds = User::pluck('id')->toArray();
        $createdBy = fake()->randomElement($userIds);

        $assigneeCandidates = array_filter($userIds, fn($id) => $id !== $createdBy);
        $assigneeId = fake()->randomElement($assigneeCandidates);

        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'created_by' => $createdBy,
            'assignee_id' => $assigneeId,
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => fake()->numberBetween(0, 2),
            'created_at' => now()
        ];
    }
}
