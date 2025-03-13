<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Database\Eloquent\Collection;
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
        $task->update($data);
        return $task;
    }

    public function changeStatus(Task $task, string $status): Task
    {
        $task->status = $status;
        $task->save();
    }

    public function deleteTask(Task $task): void
    {
        $task->delete();
    }
}