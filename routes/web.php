<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AccessControlController;
use App\Http\Controllers\AuditLogsController;
use App\Http\Controllers\ImportStudentController;
use App\Http\Controllers\AddStudentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'dashboard');

// Opening this URL directly should take visitors to the login page, where the
// demo login form can submit its CSRF-protected POST request.
Route::get('/demo-login', function () {
    abort_unless(config('app.demo_login_enabled'), 404);

    return redirect()->route('login')->with('status', __('Use the demo login button to continue.'));
})->middleware('guest');

Route::post('/demo-login', function (Request $request) {
    abort_unless(config('app.demo_login_enabled'), 404);

    $user = User::where('email', config('app.demo_admin_email'))
        ->where('role', 'Admin')
        ->where('status', 'Active')
        ->firstOrFail();

    Auth::login($user);
    $request->session()->regenerate();

    return redirect()->intended(route('dashboard'));
})->middleware(['guest', 'throttle:10,1'])->name('demo.login');

Route::middleware(['auth:sanctum', 'verified', 'force.password.change'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DashboardController::class, 'jsonDataFeed'])->name('json_data_feed');

    // Error 404
    Route::fallback(function() {
        return view('pages/utility/404');
    });  

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/force-password-change', [DashboardController::class, 'forcePasswordChange'])->name('force-password-change.update');
    // Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('analytics');

    // Document
    Route::get('/document', [DocumentController::class, 'index'])->name('document');
    Route::get('/document/view/{selected?}', [DocumentController::class, 'view'])->name('document-view');
    Route::get('/document/download/{selected?}', [DocumentController::class, 'download'])->name('document-download');
    Route::get('/documents/download-bulk', [DocumentController::class, 'downloadBulk'])->name('documents-download-bulk');
    Route::get('/document/print/{selected?}', [DocumentController::class, 'print'])->name('document-print');

    Route::get('/access-control/manage-users', [AccessControlController::class, 'manageUsers'])->name('manage-users');
    Route::get('/access-control/manage-users/reset/{selected?}', [AccessControlController::class, 'reset'])->name('manage-users-reset');
    Route::put('/access-control/manage-users/reset/submit/{selected?}', [AccessControlController::class, 'updateUserPassword'])->name('manage-users-reset-submit');

    Route::middleware(['admin'])->group(function () {
        // Access Control
        Route::get('/access-control/add-user', [AccessControlController::class, 'index'])->name('add-user');
        Route::post('/access-control/add-user-store', [AccessControlController::class, 'store'])->name('add-user-store');
        Route::post('/access-control/add-user/check-username-email', [AccessControlController::class, 'checkUsernameEmail'])->name('access-control.check-username-email');

        Route::get('/access-control/manage-users/edit/{selected?}', [AccessControlController::class, 'edit'])->name('manage-users-edit');
        Route::put('/access-control/manage-users/edit/submit/{selected?}', [AccessControlController::class, 'updateUserDetails'])->name('manage-users-edit-submit');

        // Audit logs
        Route::get('/audit-logs', [AuditLogsController::class, 'index'])->name('audit-logs');

        // Import
        Route::post('/document/import-users', [ImportStudentController::class, 'import'])->name('import-users');
        Route::get('/document/import-progress', [ImportStudentController::class, 'getImportProgress'])->name('import-progress');

        // Add Student
        Route::post('/document/add-student', [AddStudentController::class, 'store'])->name('add-student');
        Route::post('/document/add-student/check-student-number', [AddStudentController::class, 'checkStudentNumber'])->name('document.check-student-number');

    });

    // Settings
    Route::get('/settings', [DashboardController::class, 'settings'])->name('settings');
});
