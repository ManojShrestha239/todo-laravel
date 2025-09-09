<x-layouts.app title="Tasks">
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Tasks</h1>
                <p class="text-gray-600 mt-1">Manage your daily tasks and stay organized</p>
            </div>

            <x-button href="{{ route('tasks.create') }}" icon="➕">
                Add New Task
            </x-button>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
            <form method="GET" action="{{ route('tasks.index') }}" class="task-filter-form space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Search tasks..."
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="status" name="status"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Tasks</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending
                            </option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>
                                Completed</option>
                        </select>
                    </div>

                    <div>
                        <label for="date_filter" class="block text-sm font-medium text-gray-700 mb-1">Date
                            Filter</label>
                        <select id="date_filter" name="date_filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Dates</option>
                            <option value="today" {{ request('date_filter') === 'today' ? 'selected' : '' }}>Today
                            </option>
                            <option value="this_week" {{ request('date_filter') === 'this_week' ? 'selected' : '' }}>
                                This Week</option>
                            <option value="overdue" {{ request('date_filter') === 'overdue' ? 'selected' : '' }}>Overdue
                            </option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                        class="clear-filters-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Filter
                    </button>
                </div>
            </form>
        </div>

        <!-- Tasks Content -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Pending Tasks -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Pending Tasks
                        <span id="pending-count"
                            class="text-sm font-normal text-gray-500">({{ $pendingTasks->count() }})</span>
                    </h2>
                </div>

                <div id="pending-tasks-container">
                    @if ($pendingTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach ($pendingTasks as $task)
                                <x-task-card :task="$task" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                            <div class="text-gray-400 text-6xl mb-4">✅</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No pending tasks</h3>
                            <p class="text-gray-500">Great job! You've completed all your tasks.</p>
                            <x-button href="{{ route('tasks.create') }}" class="mt-4">
                                Add Your First Task
                            </x-button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Completed Tasks -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Completed Tasks
                        <span id="completed-count"
                            class="text-sm font-normal text-gray-500">({{ $completedTasks->count() }})</span>
                    </h2>
                </div>

                <div id="completed-tasks-container">
                    @if ($completedTasks->count() > 0)
                        <div class="space-y-4">
                            @foreach ($completedTasks as $task)
                                <x-task-card :task="$task" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-white rounded-lg border border-gray-200">
                            <div class="text-gray-400 text-6xl mb-4">🎯</div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No completed tasks yet</h3>
                            <p class="text-gray-500">Complete some tasks to see them here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics -->
        @if ($pendingTasks->count() > 0 || $completedTasks->count() > 0)
            <div id="statistics-container" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="total-count text-2xl font-bold text-blue-600">
                            {{ $pendingTasks->count() + $completedTasks->count() }}</div>
                        <div class="text-sm text-gray-500">Total Tasks</div>
                    </div>
                    <div class="text-center">
                        <div class="pending-count text-2xl font-bold text-yellow-600">{{ $pendingTasks->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Pending</div>
                    </div>
                    <div class="text-center">
                        <div class="completed-count text-2xl font-bold text-green-600">{{ $completedTasks->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Completed</div>
                    </div>
                    <div class="text-center">
                        <div class="overdue-count text-2xl font-bold text-red-600">
                            {{ $pendingTasks->filter(fn($task) => $task->isOverdue())->count() }}
                        </div>
                        <div class="text-sm text-gray-500">Overdue</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
