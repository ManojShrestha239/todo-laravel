<x-layouts.app title="Create Task">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Create New Task</h1>
            <p class="text-gray-600 mt-1">Add a new task to your to-do list</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <form action="{{ route('tasks.store') }}" method="POST" class="task-create-form space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Title *
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                        placeholder="Enter task title..." required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                        placeholder="Enter task description (optional)...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Due Date
                    </label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('due_date') border-red-500 @enderror">
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input type="hidden" name="completed" value="0">
                    <input type="checkbox" id="completed" name="completed" value="1"
                        {{ old('completed') ? 'checked' : '' }}
                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                    <label for="completed" class="ml-2 text-sm text-gray-700">
                        Mark as completed
                    </label>
                </div>

                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                    <x-button variant="outline" href="{{ route('tasks.index') }}">
                        Cancel
                    </x-button>
                    <x-button type="submit">
                        Create Task
                    </x-button>
                </div>
            </form>
        </div>

        <!-- Quick Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="text-sm font-medium text-blue-800 mb-2">💡 Quick Tips</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Use clear, actionable titles for better organization</li>
                <li>• Set due dates to stay on track with deadlines</li>
                <li>• Add descriptions for complex tasks that need more detail</li>
            </ul>
        </div>
    </div>
</x-layouts.app>
