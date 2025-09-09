@props(['task', 'showActions' => true])

@php
    $cardClasses = 'bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow';
    if ($task->isOverdue()) {
        $cardClasses .= ' border-l-4 border-l-red-500 bg-red-50';
    }
@endphp

<div class="{{ $cardClasses }}">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-3">
                @if ($showActions)
                    <button type="button" data-task-id="{{ $task->id }}"
                        class="task-toggle-btn flex-shrink-0 w-5 h-5 rounded border-2 transition-colors
                                       {{ $task->completed ? 'bg-green-500 border-green-500 text-white' : 'border-gray-300 hover:border-green-400' }}">
                        @if ($task->completed)
                            <svg class="w-3 h-3 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        @endif
                    </button>
                @endif
                <div class="flex-1">
                    <h3
                        class="text-lg font-medium text-gray-900 {{ $task->completed ? 'line-through text-gray-500' : '' }}">
                        {{ $task->title }}
                    </h3>

                    @if ($task->description)
                        <p class="text-gray-600 mt-1 {{ $task->completed ? 'line-through' : '' }}">
                            {{ Str::limit($task->description, 150) }}
                        </p>
                    @endif

                    <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                        @if ($task->due_date)
                            <span
                                class="flex items-center space-x-1
                                         {{ $task->isOverdue() ? 'text-red-600 font-medium' : '' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>
                                    {{ $task->due_date->format('M j, Y') }}
                                    @if ($task->isOverdue())
                                        (Overdue)
                                    @endif
                                </span>
                            </span>
                        @endif

                        <span class="flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ $task->created_at->diffForHumans() }}</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        @if ($showActions)
            <div class="flex items-center space-x-2 ml-4">
                <x-button variant="ghost" size="sm" onclick="openModal('edit-modal-{{ $task->id }}')"
                    class="p-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </x-button>

                <button type="button" data-task-id="{{ $task->id }}"
                    class="task-delete-btn p-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-md transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </div>
        @endif
    </div>

    @if ($showActions)
        <!-- Edit Modal -->
        <x-modal id="edit-modal-{{ $task->id }}" title="Edit Task">
            <form action="{{ route('tasks.update', $task) }}" method="POST" class="task-update-form"
                data-task-id="{{ $task->id }}">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="edit-title-{{ $task->id }}"
                            class="block text-sm font-medium text-gray-700 mb-1">
                            Title *
                        </label>
                        <input type="text" id="edit-title-{{ $task->id }}" name="title"
                            value="{{ old('title', $task->title) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            required>
                    </div>

                    <div>
                        <label for="edit-description-{{ $task->id }}"
                            class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea id="edit-description-{{ $task->id }}" name="description" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">{{ old('description', $task->description) }}</textarea>
                    </div>

                    <div>
                        <label for="edit-due_date-{{ $task->id }}"
                            class="block text-sm font-medium text-gray-700 mb-1">
                            Due Date
                        </label>
                        <input type="date" id="edit-due_date-{{ $task->id }}" name="due_date"
                            value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex items-center">
                        <input type="hidden" name="completed" value="0">
                        <input type="checkbox" id="edit-completed-{{ $task->id }}" name="completed" value="1"
                            {{ old('completed', $task->completed) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <label for="edit-completed-{{ $task->id }}" class="ml-2 text-sm text-gray-700">
                            Mark as completed
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <x-button variant="outline" type="button" onclick="closeModal('edit-modal-{{ $task->id }}')">
                        Cancel
                    </x-button>
                    <x-button type="submit">
                        Update Task
                    </x-button>
                </div>
            </form>
        </x-modal>
    @endif
</div>
