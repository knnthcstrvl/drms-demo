@section('title','| Dashboard')

<x-app-layout>

    <x-bladewind.notification />

    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        <!-- Dashboard actions -->
        <div class="sm:flex sm:justify-between sm:items-center mb-8">

            <!-- Left: Title -->
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard</h1>
            </div>

            <!-- Right: Actions -->
            <div class="grid grid-flow-col sm:auto-cols-max justify-start sm:justify-end gap-2">

                <!-- Filter button -->
                <x-dropdown-filter align="right" />

                <!-- Datepicker built with flatpickr -->
                <x-datepicker />

                <!-- Add view button -->
                <button class="btn bg-primary-500/90 text-white hover:bg-primary-500 dark:bg-gray1 dark:text-dafault dark:hover:bg-gray1/90">
                    <svg class="fill-current shrink-0 xs:hidden" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
                    </svg>
                    <span class="max-xs:sr-only">Add View</span>
                </button>
                
            </div>

        </div>
        
        <!-- Cards -->
        <div class="grid grid-cols-12 gap-6">

            <x-dashboard.dashboard-card-welcome  />

            <x-dashboard.dashboard-card-01
                title="Total Student Records" 
                :count="$totalStudents" 
                :percent="$studentPercentage" 
            />

            <x-dashboard.dashboard-card-02
                title="Total Users"
                :count="$totalUsers"
                :percent="$userPercentage"
            />

            <x-dashboard.dashboard-card-03
                title="Total Activities Logged"
                :count="$totalActivities"
                :percent="$activityPercentage"
            />

            <x-dashboard.dashboard-card-06
                title="Role Breakdown"
            />

            <x-dashboard.dashboard-card-07
                title="Most Active Users (Per Activity Type)"
                :mostActiveUsers="$mostActiveUsers"
            />

            <x-dashboard.dashboard-card-12
                title="Recent Activity"
                :recentActivities="$recentActivities"
            />

            <x-dashboard.dashboard-card-10
                title="Recently Added Students"
                :recentlyAddedStudents="$recentlyAddedStudents"
            />

        </div>
    </div>
</x-app-layout>

@if(session('error_notification') && session('redirected_from_restriction'))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            showNotification('Error', '{{ session('error_notification') }}', 'error');
        });
    </script>
@endif
