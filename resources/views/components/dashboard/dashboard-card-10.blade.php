@props(['title', 'recentlyAddedStudents'])

<div class="col-span-full xl:col-span-6 bg-white dark:bg-gray-800 shadow-sm rounded-xl h-full flex flex-col">
    <header class="px-5 py-4 border-b border-gray-100 dark:border-gray-700/60">
        <h2 class="font-semibold text-gray-800 dark:text-gray-100">{{ $title }}</h2>
    </header>
    <div class="flex-1 p-3 flex flex-col">
        <!-- Table -->
        <div class="overflow-x-auto flex-1">
            <table class="table-fixed w-full h-full min-h-full">
                <!-- Table header -->
                <thead class="text-xs font-semibold uppercase text-gray-400 dark:text-gray-500 bg-gray-50 dark:bg-gray-700 dark:bg-opacity-50">
                    <tr>
                        <th class="p-2 whitespace-nowrap w-2/5">
                            <div class="font-semibold text-left">Full Name</div>
                        </th>
                        <th class="p-2 whitespace-nowrap w-1/4">
                            <div class="font-semibold text-left">Student Number</div>
                        </th>
                        <th class="p-2 whitespace-nowrap w-1/3">
                            <div class="font-semibold text-left">Program</div>
                        </th>
                    </tr>
                </thead>
                <!-- Table body -->
                <tbody class="text-sm divide-y divide-gray-100 dark:divide-gray-700/60">
                    @forelse($recentlyAddedStudents as $student)
                    <tr>
                        <td class="p-2 whitespace-nowrap w-2/5">
                            <div class="font-medium text-gray-800 dark:text-gray-100 truncate">
                                {{ $student->first_name }}
                                @if($student->middle_name)
                                    @if(strlen(trim($student->middle_name)) === 1)
                                        {{ ' ' . $student->middle_name . '.' }}
                                    @else
                                        {{ ' ' . $student->middle_name }}
                                    @endif
                                @endif
                                {{ ' ' . $student->last_name }}
                            </div>
                        </td>
                        <td class="p-2 whitespace-nowrap w-1/4">
                            <div class="font-medium text-left text-blue-400 hover:underline truncate">{{ $student->student_number }}</div>
                        </td>
                        <td class="p-2 whitespace-nowrap w-1/3">
                            <div class="text-left truncate">{{ $student->program }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-2 text-center text-gray-400">No students found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>