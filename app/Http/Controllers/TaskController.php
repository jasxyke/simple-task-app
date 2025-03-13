<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $tasks = $this->taskService->getAllTasks();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $this->taskService->createTask($request->validated());
        return redirect()->route('tasks.index')->with('Sucess', 'Task Created Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($this->taskService->getTaskById($task->id), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // $this->taskService->updateTask();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->updateTask($task, $request->validated());
        return redirect()->route('tasks.index')->with('sucess', 'Task edited successfully!');
    }

    // toggle the status of the task
    public function changeStatus(Request $request, Task $task)
    {
        $status = $request->input('status');
        $this->taskService->changeStatus($task, $status);
        return redirect()->route('tasks.index')->with('success', 'Successfully changed status!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $this->taskService->deleteTask($task);
        return redirect()->route('tasks.index')->with('sucess', 'Task deleted successfuly!');
    }
}