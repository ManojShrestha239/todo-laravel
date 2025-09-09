@props(['id', 'title', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
    ];
@endphp

<div id="{{ $id }}" class="modal fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="modal-backdrop fixed inset-0 bg-black opacity-50"></div>

        <div class="relative w-full {{ $sizes[$size] }} bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">
                    {{ $title }}
                </h3>
                <button type="button" onclick="closeModal('{{ $id }}')"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="p-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
