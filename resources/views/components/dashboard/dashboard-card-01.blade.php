@props(['title', 'count', 'percent', 'chartId' => 'dashboard-card-01'])

<div class="flex flex-col col-span-full sm:col-span-6 xl:col-span-4 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <div class="px-5 pt-5">
        <header class="flex justify-between items-start mb-2">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                {{ $title }}
            </h2>

            <!-- Dropdown menu -->
            <div class="relative inline-flex" x-data="{ open: false }">
                <button
                    class="rounded-full"
                    :class="open ? 'bg-gray-100 dark:bg-gray-700/60 text-gray-500 dark:text-gray-400': 'text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400'"          
                    aria-haspopup="true"
                    @click.prevent="open = !open"
                    :aria-expanded="open"
                >
                    <span class="sr-only">Menu</span>
                    <svg class="w-8 h-8 fill-current" viewBox="0 0 32 32">
                        <circle cx="16" cy="16" r="2" />
                        <circle cx="10" cy="16" r="2" />
                        <circle cx="22" cy="16" r="2" />
                    </svg>
                </button>
                <div
                    class="origin-top-right z-10 absolute top-full right-0 min-w-36 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1"                
                    @click.outside="open = false"
                    @keydown.escape.window="open = false"
                    x-show="open"
                    x-transition:enter="transition ease-out duration-200 transform"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-out duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-cloak                
                >
                    <ul>
                        <li>
                            <a class="font-medium text-sm text-gray-600 dark:text-gray-300 hover:text-gray-800 dark:hover:text-gray-200 flex py-1 px-3" href="#">
                                View all students
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Total + Percentage -->
        <div class="flex items-start">
            <div class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-2">
                {{ number_format($count) }}
            </div>
            <div class="text-sm font-medium {{ $percent >= 0 ? 'text-green-700 bg-green-500/20' : 'text-red-700 bg-red-500/20' }} px-1.5 rounded-full">
                {{ $percent >= 0 ? '+' : '' }}{{ $percent }}%
            </div>
        </div>
    </div>

    <!-- Chart section (optional) -->
    <div class="grow max-sm:max-h-[128px] xl:max-h-[128px]">
        <canvas id="{{ $chartId }}" width="389" height="128"></canvas>
    </div>
</div>