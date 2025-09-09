<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - To-Do List' : 'To-Do List' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">
                            <a href="{{ route('tasks.index') }}" class="hover:text-blue-600 transition-colors">
                                📝 To-Do List
                            </a>
                        </h1>
                    </div>

                    <div class="flex items-center space-x-4">
                        <a href="{{ route('tasks.index') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tasks.index') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-blue-600' }}">
                            Tasks
                        </a>
                        <a href="{{ route('tasks.trash') }}"
                            class="px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('tasks.trash') ? 'bg-blue-100 text-blue-700' : 'text-gray-700 hover:text-blue-600' }}">
                            Trash
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Flash Messages -->
        @include('validation.error')
        {{-- <x-validation.errors /> --}}
        {{-- @if (session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            </div>
        @endif --}}

        <!-- Page Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Modal Container -->
    <div id="modal-container"></div>

    <script>
        // Simple modal functionality
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-backdrop')) {
                const modal = e.target.closest('.modal');
                if (modal) {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const visibleModals = document.querySelectorAll('.modal:not(.hidden)');
                visibleModals.forEach(modal => {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                });
            }
        });
    </script>
</body>

</html>
