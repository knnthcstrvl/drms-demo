@props(['title', 'recentActivities'])

<div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    </header>
    <div class="p-3">
        <!-- "Today" group -->
        <div>
            <header class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm font-semibold p-2">Today</header>
            <ul class="my-1">
                @forelse($recentActivities['today'] ?? [] as $activity)
                <li class="flex px-2">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full shrink-0 my-2 mr-3 flex items-center justify-center @php
                        echo match($activity->activity_type) {
                            'Login' => 'bg-gray3',
                            'View'  => 'bg-primary-500',
                            'Download'  => 'bg-secondary',
                            'Print'  => 'bg-green-500',
                            'Add'  => 'bg-sky-500',
                            'Edit'  => 'bg-blue-500',
                            'Reset'  => 'bg-cyan-500',
                            'Delete'  => 'bg-red-500',
                            default => 'bg-gray-400',
                        };
                    @endphp">
                        @switch($activity->activity_type)
                            @case('Login')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M352 96l64 0c17.7 0 32 14.3 32 32l0 256c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0c53 0 96-43 96-96l0-256c0-53-43-96-96-96l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32zm-9.4 182.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L242.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg>
                                @break
                            @case('View')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                @break
                            @case('Download')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 242.7-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7 288 32zM64 352c-35.3 0-64 28.7-64 64l0 32c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-32c0-35.3-28.7-64-64-64l-101.5 0-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352 64 352zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                @break
                            @case('Print')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                @break
                            @case('Add')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                                @break
                            @case('Edit')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/></svg>
                                @break
                            @case('Reset')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>
                                @break
                            @case('Delete')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                @break
                            @default
                                <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 36 36"><circle cx="18" cy="18" r="16" /></svg>
                        @endswitch
                    </div>
                    <div class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                        <div class="grow flex justify-between">
                            <div class="self-center">
                                @php $user = $activity->user; @endphp
                                @switch($activity->activity_type)
                                    @case('Login')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> logged in at {{ $activity->date_time->format('g:i A') }}
                                        @break
                                    @case('View')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span>
                                        @if($activity->first_name && $activity->student_number)
                                            viewed the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @else
                                            viewed the document ({{ $activity->filename }})
                                        @endif
                                        @break

                                    @case('Download')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span>
                                        @if($activity->first_name && $activity->student_number)
                                            downloaded the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @else
                                            downloaded the document ({{ $activity->filename }})
                                        @endif
                                        @break

                                    @case('Print')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span>
                                        @if($activity->first_name && $activity->student_number)
                                            printed the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @else
                                            printed the document ({{ $activity->filename }})
                                        @endif
                                        @break
                                    @case('Add')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> created an account for <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Edit')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> edited the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Reset')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> reset the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Delete')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> deleted the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @default
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> performed an action.
                                @endswitch
                            </div>
                            <div class="shrink-0 self-end ml-2">
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $activity->date_time->format('g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-2 py-2 text-sm text-gray-400 dark:text-gray-500">No activity today.</li>
                @endforelse
            </ul>
        </div>
        <!-- "Yesterday" group -->
        <div>
            <header class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm font-semibold p-2">Yesterday</header>
            <ul class="my-1">
                @forelse($recentActivities['yesterday'] ?? [] as $activity)
                <li class="flex px-2">
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full shrink-0 my-2 mr-3 flex items-center justify-center @php
                        echo match($activity->activity_type) {
                            'Login' => 'bg-gray3',
                            'View'  => 'bg-primary-500',
                            'Download'  => 'bg-secondary',
                            'Print'  => 'bg-green-500',
                            'Add'  => 'bg-sky-500',
                            'Edit'  => 'bg-blue-500',
                            'Reset'  => 'bg-cyan-500',
                            'Delete'  => 'bg-red-500',
                            default => 'bg-gray-400',
                        };
                    @endphp">
                        @switch($activity->activity_type)
                            @case('Login')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M352 96l64 0c17.7 0 32 14.3 32 32l0 256c0 17.7-14.3 32-32 32l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0c53 0 96-43 96-96l0-256c0-53-43-96-96-96l-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32zm-9.4 182.6c12.5-12.5 12.5-32.8 0-45.3l-128-128c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L242.7 224 32 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l210.7 0-73.4 73.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l128-128z"/></svg>
                                @break
                            @case('View')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z"/></svg>
                                @break
                            @case('Download')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M288 32c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 242.7-73.4-73.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3l128 128c12.5 12.5 32.8 12.5 45.3 0l128-128c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L288 274.7 288 32zM64 352c-35.3 0-64 28.7-64 64l0 32c0 35.3 28.7 64 64 64l384 0c35.3 0 64-28.7 64-64l0-32c0-35.3-28.7-64-64-64l-101.5 0-45.3 45.3c-25 25-65.5 25-90.5 0L165.5 352 64 352zm368 56a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                @break
                            @case('Print')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M128 0C92.7 0 64 28.7 64 64l0 96 64 0 0-96 226.7 0L384 93.3l0 66.7 64 0 0-66.7c0-17-6.7-33.3-18.7-45.3L400 18.7C388 6.7 371.7 0 354.7 0L128 0zM384 352l0 32 0 64-256 0 0-64 0-16 0-16 256 0zm64 32l32 0c17.7 0 32-14.3 32-32l0-96c0-35.3-28.7-64-64-64L64 192c-35.3 0-64 28.7-64 64l0 96c0 17.7 14.3 32 32 32l32 0 0 64c0 35.3 28.7 64 64 64l256 0c35.3 0 64-28.7 64-64l0-64zM432 248a24 24 0 1 1 0 48 24 24 0 1 1 0-48z"/></svg>
                                @break
                            @case('Add')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 144L48 224c-17.7 0-32 14.3-32 32s14.3 32 32 32l144 0 0 144c0 17.7 14.3 32 32 32s32-14.3 32-32l0-144 144 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-144 0 0-144z"/></svg>
                                @break
                            @case('Edit')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M471.6 21.7c-21.9-21.9-57.3-21.9-79.2 0L362.3 51.7l97.9 97.9 30.1-30.1c21.9-21.9 21.9-57.3 0-79.2L471.6 21.7zm-299.2 220c-6.1 6.1-10.8 13.6-13.5 21.9l-29.6 88.8c-2.9 8.6-.6 18.1 5.8 24.6s15.9 8.7 24.6 5.8l88.8-29.6c8.2-2.7 15.7-7.4 21.9-13.5L437.7 172.3 339.7 74.3 172.4 241.7zM96 64C43 64 0 107 0 160L0 416c0 53 43 96 96 96l256 0c53 0 96-43 96-96l0-96c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7-14.3 32-32 32L96 448c-17.7 0-32-14.3-32-32l0-256c0-17.7 14.3-32 32-32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L96 64z"/></svg>
                                @break
                            @case('Reset')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M336 352c97.2 0 176-78.8 176-176S433.2 0 336 0S160 78.8 160 176c0 18.7 2.9 36.8 8.3 53.7L7 391c-4.5 4.5-7 10.6-7 17l0 80c0 13.3 10.7 24 24 24l80 0c13.3 0 24-10.7 24-24l0-40 40 0c13.3 0 24-10.7 24-24l0-40 40 0c6.4 0 12.5-2.5 17-7l33.3-33.3c16.9 5.4 35 8.3 53.7 8.3zM376 96a40 40 0 1 1 0 80 40 40 0 1 1 0-80z"/></svg>
                                @break
                            @case('Delete')
                                <svg class="w-4 h-4 fill-current text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M135.2 17.7L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-7.2-14.3C307.4 6.8 296.3 0 284.2 0L163.8 0c-12.1 0-23.2 6.8-28.6 17.7zM416 128L32 128 53.2 467c1.6 25.3 22.6 45 47.9 45l245.8 0c25.3 0 46.3-19.7 47.9-45L416 128z"/></svg>
                                @break
                            @default
                                <svg class="w-4 h-4 fill-current text-white" viewBox="0 0 36 36"><circle cx="18" cy="18" r="16" /></svg>
                        @endswitch
                    </div>
                    <div class="grow flex items-center border-b border-gray-100 dark:border-gray-700/60 text-sm py-2">
                        <div class="grow flex justify-between">
                            <div class="self-center">
                                @php $user = $activity->user; @endphp
                                @switch($activity->activity_type)
                                    @case('Login')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> logged in at {{ $activity->date_time->format('g:i A') }}
                                        @break
                                    @case('View')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> viewed the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @break
                                    @case('Download')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> downloaded the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @break
                                    @case('Print')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> printed the document of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->first_name }}</span> ({{ $activity->student_number }})
                                        @break
                                    @case('Add')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> created an account for <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Edit')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> edited the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Reset')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> reset the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @case('Delete')
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> deleted the account of <span class="font-medium text-gray-800 dark:text-gray-100">{{ $activity->full_name }}</span> ({{ $activity->username }})
                                        @break
                                    @default
                                        <span class="font-medium text-gray-800 dark:text-gray-100">{{ $user }}</span> performed an action.
                                @endswitch
                            </div>
                            <div class="shrink-0 self-end ml-2">
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $activity->date_time->format('g:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </li>
                @empty
                <li class="px-2 py-2 text-sm text-gray-400 dark:text-gray-500">No activity yesterday.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>