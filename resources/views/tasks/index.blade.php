<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Simple Task Management Application</title>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section style="display: flex; height: 100dvh; padding: 10px;">
        <div
            style="width: 100%; height: 100%; min-height: 500px; max-width: 66.67%; margin: auto; padding: 20px; background-color: var(--primary); border-radius: 8px;">
            <div style="display: flex; justify-content: space-between;">
                <p style="font-size: 1.875rem;">To-do List</p>
                <button id="openCreateModal"
                    style="padding: 10px 20px; background-color: var(--darkGreen); color: white; border-radius: 4px; cursor: pointer;">
                    Add Task
                </button>
            </div>
            <div style="margin-top: 20px;">
                <div style="border-radius: 8px;">
                    @if ($tasks->isEmpty())
                        <div style="border: 1px solid #ccc; padding: 10px; text-align: center; margin-bottom: 20px;">
                            There are no available tasks.
                        </div>
                    @endif
                    @if ($errors->any())
                        {!! implode(
                            '',
                            $errors->all(
                                '<div style="background-color: #dc2626; color: white; padding: 10px; text-align: center; margin-bottom: 20px; border-radius: 4px;">:message</div>',
                            ),
                        ) !!}
                    @endif
                    @foreach ($tasks as $task)
                        <div class="task-item" data-id="{{ $task->id }}" data-title="{{ $task->title }}"
                            data-description="{{ $task->description }}" data-due_date="{{ $task->due_date }}"
                            style="border: 1px solid #ccc; border-radius: 4px; padding: 15px; margin-bottom: 20px; cursor: pointer;">
                            <div style="display: flex; justify-content: space-between;">
                                <p
                                    style="font-size: 1.125rem; font-weight: bold; text-transform: capitalize; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $task->title }}
                                </p>
                                <div style="display: flex; gap: 10px;">
                                    {{-- Change Status --}}
                                    <form action="{{ route('tasks.changeStatus', $task->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" id="task_status" onchange="this.form.submit()"
                                            style="padding: 5px; border-radius: 4px; cursor: pointer;
                                            background-color: {{ $task->status === 'pending' ? '#9ca3af' : 'var(--dimGreen)' }};
                                            color: {{ $task->status === 'pending' ? 'black' : 'white' }};">
                                            <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>
                                                Pending
                                            </option>
                                            <option value="completed"
                                                {{ $task->status === 'completed' ? 'selected' : '' }}>
                                                Completed
                                            </option>
                                        </select>
                                    </form>
                                    {{-- Delete Task --}}
                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this task?');"
                                        style="cursor: pointer; display: flex; align-items: center;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border: none; background: none; cursor: pointer;">
                                            <x-heroicon-o-trash style="width: 20px;" />
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if ($task->description)
                                <p style="color: black; font-size: 0.875rem;">
                                    {{ $task->description }}
                                </p>
                            @endif
                            <p style="margin-top: 24px; color: #6b7280; font-style: italic;">
                                Due date: {{ \Carbon\Carbon::parse($task->due_date)->format('F j, Y') }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
            <div style="margin-top: 20px;">
                {{ $tasks->onEachSide(1)->links('vendor.pagination.simple-tailwind') }}
            </div>
        </div>
    </section>

    {{-- Create Task Modal --}}
    <x-task-form action="{{ route('tasks.store') }}" buttonText="Add Task" modalId="createTaskModal"
        headerTitle="Create Task" />

    {{-- Edit Task Modal --}}
    <x-task-form action="" method="PUT" buttonText="Save Changes" modalId="editTaskModal"
        headerTitle="Update Task" />

    <script>
        $(document).ready(function() {
            function openModal(id) {
                document.getElementById(id).style.display = 'block';
            }

            function closeModal(id) {
                document.getElementById(id).style.display = 'none';
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
