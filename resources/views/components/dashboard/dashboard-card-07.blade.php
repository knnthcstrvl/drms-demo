@props(['title', 'mostActiveUsers'])

<div class="col-span-full xl:col-span-8 bg-white dark:bg-gray-800 shadow-sm rounded-xl h-full flex flex-col">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    </header>
    <div class="flex-1 p-3">
        <!-- Table -->
        <div class="overflow-x-auto flex-1">
            <table class="table-auto w-full h-full dark:text-gray-300 min-h-full">
                <!-- Table header -->
                <thead class="text-xs uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50 rounded-sm">
                    <tr>
                        <th class="px-2 py-1">
                            <div class="font-semibold text-left">User</div>
                        </th>
                        <th class="px-2 py-1">
                            <div class="font-semibold text-center">Role</div>
                        </th>
                        <th class="px-2 py-1">
                            <div class="font-semibold text-center">Activity Type</div>
                        </th>
                        <th class="px-2 py-1">
                            <div class="font-semibold text-center">Number of Activity</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm font-medium divide-y divide-gray-100 dark:divide-gray-700/60">
                    @foreach($mostActiveUsers as $row)
                    <tr>
                        <td class="px-2 py-1">
                            <div class="text-blue-400 hover:underline">{{ $row->user_name }}</div>
                        </td>
                        <td class="px-2 py-1 text-center font-normal">
                            <div>{{ $row->role }}</div>
                        </td>
                        <td class="px-2 py-1 text-center">
                            @php
                                $activityType = $row->activity_type;
                                $activityClasses = match($activityType) {
                                    'Login' => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-gray3 !bg-gray3/20 !rounded-full dark:!text-gray3',
                                    'View'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-primary-500 !bg-primary-500/20 !rounded-full dark:!text-purple-700',
                                    'Download'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-secondary !bg-secondary/20 !rounded-full dark:!text-secondary',
                                    'Print'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-green-700 !bg-green-500/20 !rounded-full dark:!text-green-400',
                                    'Add'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-sky-700 !bg-sky-500/20 !rounded-full dark:!text-sky-400',
                                    'Edit'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-blue-700 !bg-blue-500/20 !rounded-full dark:!text-blue-400',
                                    'Reset'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-cyan-700 !bg-cyan-500/20 !rounded-full dark:!text-cyan-400',
                                    'Delete'  => '!inline-block !mx-6 !my-4 !px-2 !py-1 !text-xs !text-red-700 !bg-red-500/20 !rounded-full dark:!text-red-400',
                                    default => '',
                                };
                            @endphp
                            <div class="{{ $activityClasses }}">{{ $row->activity_type }}</div>
                        </td>
                        <td class="px-2 py-1 font-normal">
                            <div class="text-center">{{ $row->activity_count }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>