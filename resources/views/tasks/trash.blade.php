<x-layouts.app title="Trash">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Trash</h1>
                <p class="text-gray-600 mt-1">Deleted tasks can be restored or permanently deleted</p>
            </div>

            <x-button href="{{ route('tasks.index') }}" variant="outline">
                ← Back to Tasks
            </x-button>
        </div>

        @if ($trashedTasks->count() > 0)
            <!-- Warning Notice -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Attention</h3>
                        <p class="text-sm text-yellow-700 mt-1">
                            Tasks in trash will remain here until you permanently delete them. You can restore them
                            anytime.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Trashed Tasks -->
            <div class="space-y-4">
                @foreach ($trashedTasks as $task)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 opacity-75">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-medium text-gray-900 line-through">
                                    {{ $task->title }}
                                </h3>

                                @if ($task->description)
                                    <p class="text-gray-600 mt-1 line-through">
                                        {{ Str::limit($task->description, 150) }}
                                    </p>
                                @endif

                                <div class="flex items-center space-x-4 mt-3 text-sm text-gray-500">
                                    @if ($task->due_date)
                                        <span class="flex items-center space-x-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <span>{{ $task->due_date->format('M j, Y') }}</span>
                                        </span>
                                    @endif

                                    <span class="flex items-center space-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        <span>Deleted {{ $task->deleted_at->diffForHumans() }}</span>
                                    </span>

                                    @if ($task->completed)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Completed
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center space-x-2 ml-4">
                                <!-- Restore Button -->
                                <form action="{{ route('tasks.restore', $task->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <x-button variant="success" size="sm" type="submit">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Restore
                                    </x-button>
                                </form>

                                <!-- Permanent Delete Button -->
                                <form action="{{ route('tasks.force-delete', $task->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <x-button variant="danger" size="sm" type="submit"
                                        onclick="return confirm('Are you sure you want to permanently delete this task? This action cannot be undone.')">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete Forever
                                    </x-button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Empty Trash Action -->
            <div class="border-t border-gray-200 pt-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Empty Trash</h3>
                            <p class="text-sm text-red-700 mt-1">
                                Permanently delete all tasks in trash. This action cannot be undone.
                            </p>
                        </div>
                        <form action="#" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <x-button variant="danger" size="sm" type="submit"
                                onclick="return confirm('Are you sure you want to permanently delete all tasks in trash? This action cannot be undone.')">
                                Empty Trash
                            </x-button>
                        </form>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                <div class="text-gray-400 text-6xl mb-4">🗑️</div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Trash is empty</h3>
                <p class="text-gray-500 mb-4">Deleted tasks will appear here before being permanently removed.</p>
                <x-button href="{{ route('tasks.index') }}">
                    Go to Tasks
                </x-button>
            </div>
        @endif
    </div>
</x-layouts.app>
