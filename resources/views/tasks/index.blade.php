<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Simple Task Management Application</title>

    {{-- CSS --}}
    @vite('resources/css/app.css')

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="flex h-dvh lg:p-10">
        <div class="w-full h-full lg:h-auto lg:min-h-[500px] lg:w-2/3 m-auto p-5 bg-primary rounded-lg">
            <div class="flex justify-between">
                <p class="text-3xl">To-do List</p>
                <button id="openCreateModal" class="button bg-darkGreen text-white rounded-md cursor-pointer">Add
                    Task</button>
            </div>
            <div class="mt-5">
                <div class="rounded-lg">
                    @if ($tasks->isEmpty())
                        <div class="border rounded-md lg:p-4 p-1 mb-5 text-center">There are no available tasks.</div>
                    @endif
                    @if ($errors->any())
                        {!! implode(
                            '',
                            $errors->all('<div class="bg-red-700 text-white border rounded-md lg:p-4 p-1 mb-5 text-center">:message</div>'),
                        ) !!}
                    @endif
                    @foreach ($tasks as $task)
                        <div class="task-item border rounded-md lg:p-4 p-2 mb-5 cursor-pointer"
                            data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                            data-description="{{ $task->description }}" data-due_date="{{ $task->due_date }}">
                            <div class="flex justify-between">
                                <p class="text-lg font-bold capitalize truncate">{{ $task->title }}</p>

                                <div class="flex gap-2">
                                    {{-- change status --}}
                                    <form @class([])
                                        action="{{ route('tasks.changeStatus', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select @class([
                                            'task-status',
                                            'p-1',
                                            'rounded-md',
                                            'cursor-pointer',
                                            'bg-gray-400' => $task->status === 'pending',
                                            'bg-dimGreen' => $task->status === 'completed',
                                            'text-white' => $task->status === 'completed',
                                            'text-black' => $task->status === 'pending',
                                        ]) name="status" id="task_status"
                                            onchange="this.form.submit()">
                                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="completed"
                                                {{ $task->status === 'completed' ? 'selected' : '' }}>Completed
                                            </option>
                                        </select>
                                    </form>
                                    {{-- Delete Task --}}
                                    <form class="cursor-pointer my-auto"
                                        action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this task?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit">
                                            <x-heroicon-o-trash class="w-5 cursor-pointer delete-task"
                                                data-id="{{ $task->id }}" />
                                        </button>
                                    </form>

                                </div>
                            </div>
                            @if ($task->description)
                                <p class=" text-black text-sm">
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
    <x-task-form action="{{ route('tasks.store') }}" buttonText="Add Task" modalId="createTaskModal"
        headerTitle="Create Task" />

    {{-- edit task modal --}}
    <x-task-form action="" method="PUT" buttonText="Save Changes" modalId="editTaskModal"
        headerTitle="Update Task" />

    <script>
        $(document).ready(function() {
            function openModal(id) {
                $(`#${id}`).removeClass('hidden');
            }

            function closeModal(id) {
                $(`#${id}`).addClass('hidden');
            }

            $('#openCreateModal').on('click', function() {
                openModal('createTaskModal');
            });

            $('#closeCreateTaskModal').on('click', function() {
                closeModal('createTaskModal');
            });

            $('.task-item').on('click', function(e) {
                e.stopPropagation();
                let taskId = $(this).data('id');
                let taskTitle = $(this).data('title');
                let taskDescription = $(this).data('description');
                let taskDueDate = $(this).data('due_date');

                $('#editTaskModal input[name="task_id"]').val(taskId);
                $('#editTaskModal input[name="title"]').val(taskTitle);
                $('#editTaskModal textarea[name="description"]').val(taskDescription);
                $('#editTaskModal input[name="due_date"]').val(taskDueDate);
                $('#editTaskModal form').attr('action', `/tasks/${taskId}`);

                openModal('editTaskModal');
            });

            $('#closeEditTaskModal').on('click', function() {
                closeModal('editTaskModal');
            });

            $('.delete-task, .task-status').on('click', function(e) {
                e.stopPropagation();
            });
        });
    </script>

</body>

</html>
