@props(['action', 'method' => 'POST', 'task' => null, 'buttonText', 'modalId', 'headerTitle'])

<div id="{{ $modalId }}" class="hidden fixed inset-0 flex items-center justify-center bg-gray-800/50 px-2">
    <div class="bg-white p-6 rounded shadow-lg w-96">
        <h2 class="text-lg font-bold mb-4">{{ $headerTitle }}</h2>
        <form action="{{ $action }}" method="POST">
            @csrf
            @if ($method !== 'POST')
                @method($method)
            @endif
            <input type="hidden" name="task_id" value="{{ $task->id ?? '' }}">
            <input type="text" name="title" placeholder="Task Title" required value="{{ $task->title ?? '' }}"
                class="border p-2 w-full rounded mb-2">
            <textarea name="description" placeholder="Description" class="border p-2 w-full rounded mb-2">{{ $task->description ?? '' }}</textarea>
            <input type="date" name="due_date" required class="border p-2 w-full rounded mb-2"
                value="{{ $task->due_date ?? '' }}">
            <button type="submit" class="bg-darkGreen text-white px-4 py-2 rounded w-full cursor-pointer">
                {{ $buttonText }}
            </button>
        </form>
        <button id="close{{ ucfirst($modalId) }}"
            class="mt-2 text-gray-500 underline w-full text-center block cursor-pointer">Cancel</button>
    </div>
</div>
