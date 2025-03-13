<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Pagination\LengthAwarePaginator;

class TaskService
{
    public function getAllTasks(int $perPage = 3): LengthAwarePaginator
    {
        return Task::orderBy('due_date', 'asc')
                    ->paginate($perPage);
    }

    public function getTaskById(int $id): ?Task
    {
        return Task::find($id);
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data): Task
    {
        // Check if the task exists in the database
        if (!$task || !$task->exists) {
            throw new \Exception("Task not found");
        }

        $task->update($data);
        return $task;
    }

    public function changeStatus(Task $task, string $status): Task
    {
        // Check if the task exists in the database
        if (!$task || !$task->exists) {
            throw new \Exception("Task not found");
        }

        $task->status = $status;
        $task->save();

        return $task;
    }

    public function deleteTask(Task $task): bool
    {
        return $task->delete();
    }
}