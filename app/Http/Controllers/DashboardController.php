<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\User;
use App\Models\AuditLog;
use Carbon\Carbon;

class DashboardController extends Controller
{
    use PasswordValidationRules;

    /**
     * Display the dashboard main page.
     *
     * @return \Illuminate\View\View
     */
    public function index() {
        return view('pages.dashboard.dashboard', $this->dashboardMainData());
    }

    public function forcePasswordChange(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->must_change_password) {
            return response()->json([
                'success' => false,
                'message' => 'Password change is not required for this account.',
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => array_merge($this->passwordRules(), ['different:current_password']),
        ], [
            'current_password.current_password' => 'The current password does not match the temporary password provided by the administrator.',
            'password.different' => 'Your new password must be different from the temporary password provided by the administrator.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            $toastMessage = $errors->first('password') ?: 'Please correct the highlighted fields.';

            return response()->json([
                'success' => false,
                'message' => $toastMessage,
                'errors' => $errors,
            ], 422);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
            'must_change_password' => false,
            'date_time_updated' => now(),
        ])->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.',
        ]);
    }

    public function dashboardMainData() {
        return array_merge(
            $this->studentStats(),
            $this->userStats(),
            $this->activityStats(),
            ['mostActiveUsers' => $this->mostActiveUsersStats()],
            ['recentActivities' => $this->recentActivities()],
            ['recentlyAddedStudents' => $this->recentlyAddedStudents()],
        );
    }

    public function studentStats() {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        $totalStudents = Student::count();
        $thisYear = Student::where('year_graduated', $currentYear)->count();
        $previousYear = Student::where('year_graduated', $lastYear)->count();

        $studentPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

        return [
            'totalStudents' => $totalStudents,
            'studentPercentage' => $studentPercentage,
        ];
    }

    public function userStats() {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        $totalUsers = User::count();
        $thisYear = User::whereYear('date_time_created', $currentYear)->count();
        $previousYear = User::whereYear('date_time_created', $lastYear)->count();

        $userPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

        return [
            'totalUsers' => $totalUsers,
            'userPercentage' => $userPercentage,
        ];
    }

    public function activityStats() {
        $currentYear = now()->year;
        $lastYear = $currentYear - 1;

        $totalActivities = AuditLog::count();
        $thisYear = AuditLog::whereYear('date_time', $currentYear)->count();
        $previousYear = AuditLog::whereYear('date_time', $lastYear)->count();

        $activityPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

        return [
            'totalActivities' => $totalActivities,
            'activityPercentage' => $activityPercentage,
        ];
    }

    public function mostActiveUsersStats() {
        $logs = AuditLog::selectRaw('user, activity_type, COUNT(*) as activity_count')
            ->groupBy('user', 'activity_type')
            ->orderByDesc('activity_count')
            ->limit(5)
            ->get();

        // Get all user names in the logs
        $userNames = $logs->pluck('user')->unique()->toArray();

        // Fetch users and their roles by full_name
        $users = User::whereIn('full_name', $userNames)->pluck('role', 'full_name');

        // Optionally, fetch role names if you have a Role model
        // $roleNames = Role::pluck('name', 'id');

        return $logs->map(function($log) use ($users) {
            $role = $users[$log->user] ?? 'N/A';
            return (object)[
                'user_name' => $log->user ?? 'Unknown',
                'role' => $role,
                'activity_type' => $log->activity_type,
                'activity_count' => $log->activity_count,
            ];
        });
    }

    public function recentActivities() {
        $today = now()->startOfDay();
        $yesterday = now()->subDay()->startOfDay();
        $endYesterday = $yesterday->copy()->endOfDay();

        // Get up to 5 activities from today
        $todayActivities = AuditLog::where('date_time', '>=', $today)
            ->orderByDesc('date_time')
            ->limit(5)
            ->get();

        $todayCount = $todayActivities->count();
        $remaining = 7 - $todayCount;

        // Get up to 5 from yesterday, but only as many as needed to reach 7 total
        $yesterdayLimit = min(5, $remaining);
        $yesterdayActivities = collect();
        if ($yesterdayLimit > 0) {
            $yesterdayActivities = AuditLog::whereBetween('date_time', [$yesterday, $endYesterday])
                ->orderByDesc('date_time')
                ->limit($yesterdayLimit)
                ->get();
        }

        return [
            'today' => $todayActivities,
            'yesterday' => $yesterdayActivities,
        ];
    }

    public function recentlyAddedStudents() {
        return Student::orderByDesc('date_time_created')
            ->limit(10)
            ->get();
    }

    /**
     * Display the analytics page.
     *
     * @return \Illuminate\View\View
     */
    // public function analytics() {
    //     return view('pages.dashboard.analytics', $this->dashboardAnalyticsData());
    // }

    // public function dashboardAnalyticsData() {
    //     return array_merge(
    //         $this->studentStats(),
    //         $this->userStats(),
    //         $this->activityStats(),
    //     );
    // }

    // public function studentStats() {
    //     $currentYear = now()->year;
    //     $lastYear = $currentYear - 1;

    //     $totalStudents = Student::count();
    //     $thisYear = Student::where('year_graduated', $currentYear)->count();
    //     $previousYear = Student::where('year_graduated', $lastYear)->count();

    //     $studentPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

    //     return [
    //         'totalStudents' => $totalStudents,
    //         'studentPercentage' => $studentPercentage,
    //     ];
    // }

    // public function userStats() {
    //     $currentYear = now()->year;
    //     $lastYear = $currentYear - 1;

    //     $totalUsers = User::count();
    //     $thisYear = User::whereYear('date_time_created', $currentYear)->count();
    //     $previousYear = User::whereYear('date_time_created', $lastYear)->count();

    //     $userPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

    //     return [
    //         'totalUsers' => $totalUsers,
    //         'userPercentage' => $userPercentage,
    //     ];
    // }

    // public function activityStats() {
    //     $currentYear = now()->year;
    //     $lastYear = $currentYear - 1;

    //     $totalActivities = AuditLog::count();
    //     $thisYear = AuditLog::whereYear('date_time', $currentYear)->count();
    //     $previousYear = AuditLog::whereYear('date_time', $lastYear)->count();

    //     $activityPercentage = $previousYear > 0 ? round((($thisYear - $previousYear) / $previousYear) * 100) : 100;

    //     return [
    //         'totalActivities' => $totalActivities,
    //         'activityPercentage' => $activityPercentage,
    //     ];
    // }

    /**
     * Handle the JSON data feed for main and analytics.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function jsonDataFeed(Request $request) {
        $datatype = $request->get('datatype', 1);

        if ($datatype == 1) {
            $graduationData = Student::selectRaw('year_graduated, COUNT(*) as count')
                ->whereNotNull('year_graduated')
                ->whereBetween('year_graduated', [1904, now()->year])
                ->groupBy('year_graduated')
                ->orderBy('year_graduated')
                ->get();

            $labels = [];
            $data = [];
            foreach ($graduationData as $yearData) {
                $labels[] = (string)$yearData->year_graduated;
                $data[] = $yearData->count;
            }
            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        }

        if ($datatype == 2) {
            $userData = User::selectRaw('DATE(date_time_created) as date_created, COUNT(*) as count')
                ->whereNotNull('date_time_created')
                ->whereBetween('date_time_created', ['1904-01-01', now()])
                ->groupBy('date_created')
                ->orderBy('date_created')
                ->get();

            $labels = [];
            $data = [];
            foreach ($userData as $dateData) {
                $labels[] = (string)$dateData->date_created;
                $data[] = $dateData->count;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        }

        if ($datatype == 3) {
            $activityData = AuditLog::selectRaw('DATE(date_time) as date_logged, COUNT(*) as count')
                ->whereNotNull('date_time')
                ->whereBetween('date_time', ['1904-01-01', now()])
                ->groupBy('date_logged')
                ->orderBy('date_logged')
                ->get();

            $labels = [];
            $data = [];
            foreach ($activityData as $dateData) {
                $labels[] = (string)$dateData->date_logged;
                $data[] = $dateData->count;
            }
            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        }

        if ($datatype == 6) {
            $roleData = User::selectRaw('role, COUNT(*) as count')
                ->groupBy('role')
                ->orderBy('role')
                ->get();

            $labels = [];
            $data = [];
            foreach ($roleData as $row) {
                $labels[] = $row->role;
                $data[] = $row->count;
            }

            return response()->json([
                'labels' => $labels,
                'data' => $data
            ]);
        }

        return response()->json(['labels' => [], 'data' => []]);
    }

    /**
     * Display the user settings page.
     *
     * @return \Illuminate\View\View
     */
    public function settings() {
        return view('profile.show');
    }
}
