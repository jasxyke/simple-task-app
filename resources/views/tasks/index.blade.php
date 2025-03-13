<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Simple Task Management Application</title>

    {{-- CSS --}}
    @vite('resources/css/app.css')

    {{-- jQuery CDN --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="flex h-dvh">
        <div class="container m-auto p-10 bg-primary rounded-lg min-h-[500px]">
            <div class="flex justify-between">
                <p class="text-3xl">To-do List</p>
                <button id="openCreateModal" class="button bg-darkGreen text-white rounded-md cursor-pointer">Add
                    Task</button>
            </div>
            <div class="mt-5">
                <div class="rounded-lg">
                    @foreach ($tasks as $task)
                        <div class="task-item border rounded-md p-4 mb-5 cursor-pointer" data-id="{{ $task->id }}"
                            data-title="{{ $task->title }}" data-description="{{ $task->description }}"
                            data-due_date="{{ $task->due_date }}">
                            <div class="flex justify-between">
                                <p class="text-lg font-bold capitalize">{{ $task->title }}</p>

                                <div class="flex">
                                    {{-- Delete Task --}}
                                    <form class="cursor-pointer" action="{{ route('tasks.destroy', $task->id) }}"
                                        method="POST" onsubmit="return confirm('Delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <x-heroicon-o-trash class="w-5 mr-4 cursor-pointer delete-task"
                                                data-id="{{ $task->id }}" />
                                        </button>
                                    </form>
                                    {{-- toggle status --}}
                                    <form action="{{ route('tasks.changeStatus', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" id="task_status" onchange="this.form.submit()">
                                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="completed"
                                                {{ $task->status === 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                        </select>
                                    </form>
                                </div>
                            </div>
                            @if ($task->description)
                                <p class="mt-2 text-black">
                                    {{ $task->description }}
                                </p>
                            @endif
                            <p class="mt-6 text-gray-600 italic">
                                Due date: {{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="mt-5">
                {{ $tasks->onEachSide(1)->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>
    </section>

    {{-- Create Task Modal --}}
    <div id="createTaskModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800/50">
        <div class="bg-white p-6 rounded shadow-lg w-96">
            <h2 class="text-lg font-bold mb-4">Create Task</h2>
            <form action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <input type="text" name="title" placeholder="Task Title" required
                    class="border p-2 w-full rounded mb-2">
                <textarea name="description" placeholder="Description" class="border p-2 w-full rounded mb-2"></textarea>
                <input type="date" name="due_date" required class="border p-2 w-full rounded mb-2">
                <button type="submit" class="bg-darkGreen text-white px-4 py-2 rounded w-full cursor-pointer">Add
                    Task</button>
            </form>
            <button id="closeCreateModal"
                class="mt-2 text-gray-500 underline w-full text-center block cursor-pointer">Cancel</button>
        </div>
    </div>

    {{-- Edit Task Modal --}}
    <div id="editTaskModal" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800/50 px-4">
        <div class="bg-white p-6 rounded shadow-lg w-full max-w-[400px]">
            <h2 class="text-lg font-bold mb-4">Update Task</h2>
            <form id="editTaskForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="task_id" id="editTaskId">
                <input type="text" name="title" id="editTaskTitle" required class="border p-2 w-full rounded mb-2">
                <textarea name="description" id="editTaskDescription" class="border p-2 w-full rounded mb-2"></textarea>
                <input type="date" name="due_date" id="editTaskDueDate" required
                    class="border p-2 w-full rounded mb-2">
                <button type="submit" class="bg-darkGreen text-white px-4 py-2 rounded w-full cursor-pointer">Save
                    Changes</button>
            </form>
            <button id="closeEditModal"
                class="mt-2 text-gray-500 underline w-full text-center block cursor-pointer">Cancel</button>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Open Create Task Modal
            $('#openCreateModal').on('click', function() {
                $('#createTaskModal').removeClass('hidden');
            });

            // Close Create Task Modal
            $('#closeCreateModal').on('click', function() {
                $('#createTaskModal').addClass('hidden');
            });

            // Open Edit Task Modal
            $('.task-item').on('click', function(e) {
                e.stopPropagation();
                let taskId = $(this).data('id');
                let taskTitle = $(this).data('title');
                let taskDescription = $(this).data('description');
                let taskDueDate = $(this).data('due_date');

                $('#editTaskId').val(taskId);
                $('#editTaskTitle').val(taskTitle);
                $('#editTaskDescription').val(taskDescription);
                $('#editTaskDueDate').val(taskDueDate);
                $('#editTaskForm').attr('action', `/tasks/${taskId}`);

                $('#editTaskModal').removeClass('hidden');
            });

            // Close Edit Task Modal
            $('#closeEditModal').on('click', function() {
                $('#editTaskModal').addClass('hidden');
            });

            $('.delete-task').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

</body>

</html>
