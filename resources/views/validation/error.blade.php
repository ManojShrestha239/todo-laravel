<!-- resources/views/components/alerts.blade.php -->
<svg xmlns="http://www.w3.org/2000/svg" class="hidden">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path
            d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
    </symbol>
</svg>

<div x-data="{ alerts: [] }"
    class="fixed inset-0 z-50 flex flex-col items-end p-4 space-y-4 pointer-events-none sm:p-6 sm:pt-20">
    {{-- Info Alert --}}
    @if (session('status') || session('Status'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-full"
            class="relative flex items-center w-full max-w-sm p-4 space-x-4 text-blue-900 bg-blue-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-blue-500/20">
            <svg class="flex-shrink-0 w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                <use xlink:href="#info-fill" />
            </svg>
            <div class="flex-1">{{ session('status') ?? session('Status') }}</div>
            <button @click="show = false"
                class="p-1.5 -m-1.5 rounded-full hover:bg-blue-100/40 transition-colors duration-200">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Success Alert --}}
    @if (session('success') || session('Success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="relative flex items-center w-full max-w-sm p-4 space-x-4 text-green-900 bg-green-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-green-500/20">
            <svg class="flex-shrink-0 w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                <use xlink:href="#check-circle-fill" />
            </svg>
            <div class="flex-1">{{ session('success') ?? session('Success') }}</div>
            <button @click="show = false"
                class="p-1.5 -m-1.5 rounded-full hover:bg-green-100/40 transition-colors duration-200">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Warning Alert --}}
    @if (session('warning') || session('Warning'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition
            class="relative flex items-center w-full max-w-sm p-4 space-x-4 text-yellow-900 bg-yellow-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-yellow-500/20">
            <svg class="flex-shrink-0 w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                <use xlink:href="#exclamation-triangle-fill" />
            </svg>
            <div class="flex-1">{{ session('warning') ?? session('Warning') }}</div>
            <button @click="show = false"
                class="p-1.5 -m-1.5 rounded-full hover:bg-yellow-100/40 transition-colors duration-200">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="relative flex items-center w-full max-w-sm p-4 space-x-4 text-red-900 bg-red-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-red-500/20">
            <svg class="flex-shrink-0 w-6 h-6" fill="currentColor" viewBox="0 0 16 16">
                <use xlink:href="#exclamation-triangle-fill" />
            </svg>
            <div class="flex-1">{{ session('error') }}</div>
            <button @click="show = false"
                class="p-1.5 -m-1.5 rounded-full hover:bg-red-100/40 transition-colors duration-200">
                <span class="sr-only">Close</span>
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition
            class="relative w-full max-w-sm p-4 text-red-900 bg-red-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-red-500/20">
            <div class="flex items-start space-x-4">
                <svg class="flex-shrink-0 w-6 h-6 mt-0.5" fill="currentColor" viewBox="0 0 16 16">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div class="flex-1 space-y-2">
                    <p class="font-medium">Oops! Something went wrong</p>
                    @if (config('app.env') == 'local')
                        <ul class="pl-5 space-y-1 text-sm list-disc max-h-32 overflow-y-auto">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <button @click="show = false"
                    class="p-1.5 -m-1.5 rounded-full hover:bg-red-100/40 transition-colors duration-200">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif



    @if (session('skipped') && is_array(session('skipped')))
        <div x-data="{ show: true }" x-show="show" x-transition
            class="relative w-full max-w-sm p-4 text-yellow-900 bg-yellow-50 rounded-lg shadow-lg pointer-events-auto ring-1 ring-yellow-500/20">
            <div class="flex items-start space-x-4">
                <svg class="flex-shrink-0 w-6 h-6 mt-0.5" fill="currentColor" viewBox="0 0 16 16">
                    <use xlink:href="#exclamation-triangle-fill" />
                </svg>
                <div class="flex-1 space-y-2">
                    <p class="font-medium">Some rows were skipped during import:</p>
                    <ul class="pl-5 space-y-1 text-sm list-disc max-h-32 overflow-y-auto">
                        @foreach (session('skipped') as $skipMsg)
                            <li>{{ $skipMsg }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false"
                    class="p-1.5 -m-1.5 rounded-full hover:bg-yellow-100/40 transition-colors duration-200">
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

</div>
