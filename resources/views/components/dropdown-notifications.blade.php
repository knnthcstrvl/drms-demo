@props([
    'align' => 'right'
])

<div class="relative inline-flex" x-data="{ open: false }">
    <button
        class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 lg:hover:bg-gray-200 dark:hover:bg-gray-700/50 dark:lg:hover:bg-gray-800 rounded-full"
        :class="{ 'bg-gray-200 dark:bg-gray-800': open }"
        aria-haspopup="true"
        @click.prevent="open = !open"
        :aria-expanded="open"                        
    >
        <span class="sr-only">Notifications</span>
        <svg class="fill-current text-gray-500/80 dark:text-gray-400/80" width="16" height="16" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
            <path d="M224 0c-17.7 0-32 14.3-32 32l0 19.2C119 66 64 130.6 64 208l0 18.8c0 47-17.3 92.4-48.5 127.6l-7.4 8.3c-8.4 9.4-10.4 22.9-5.3 34.4S19.4 416 32 416l384 0c12.6 0 24-7.4 29.2-18.9s3.1-25-5.3-34.4l-7.4-8.3C401.3 319.2 384 273.9 384 226.8l0-18.8c0-77.4-55-142-128-156.8L256 32c0-17.7-14.3-32-32-32zm45.3 493.3c12-12 18.7-28.3 18.7-45.3l-64 0-64 0c0 17 6.7 33.3 18.7 45.3s28.3 18.7 45.3 18.7s33.3-6.7 45.3-18.7z"/>
        </svg>        
        <!-- <div class="absolute top-0 right-0 w-2.5 h-2.5 bg-red-500 border-2 border-gray-100 dark:border-gray-900 rounded-full"></div> -->
    </button>
    <div
        class="origin-top-right z-10 absolute top-full -mr-48 sm:mr-0 min-w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700/60 py-1.5 rounded-lg shadow-lg overflow-hidden mt-1 {{$align === 'right' ? 'right-0' : 'left-0'}}"                
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
        <div class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase pt-1.5 pb-2 px-4">Notifications</div>
        <ul>
            <!-- <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                <a class="block py-2 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20" href="#0" @click="open = false" @focus="open = true" @focusout="open = false">
                    <span class="block text-sm mb-2">📣 <span class="font-medium text-gray-800 dark:text-gray-100">Edit your information in a swipe</span><br>
                    Sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim.</span>
                    <span class="block text-xs font-medium text-gray-400 dark:text-gray-500">Feb 12, 2024</span>
                </a>
            </li> -->
            <li class="border-b border-gray-200 dark:border-gray-700/60 last:border-0">
                <a class="block py-2 px-4 hover:bg-gray-50 dark:hover:bg-gray-700/20" href="#0" @click="open = false" @focus="open = true" @focusout="open = false">
                    <span class="block text-sm mb-2">
                        <span class="font-medium text-default dark:text-gray-100">
                            You have no notifications right now.
                        </span>
                    </span>
                </a>
            </li>
        </ul>                
    </div>
</div>