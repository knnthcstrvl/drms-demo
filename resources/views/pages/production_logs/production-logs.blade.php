@section('title','| Productions Logs')

<x-app-layout>
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Productions Logs</h1>
            </div>
        </div>

        <div class="w-full bg-white dark:bg-gray-800 p-4 rounded-lg">
            <livewire:productionlogs-table />
        </div>
    </div>
</x-app-layout>
