<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\HouseholdController;
use App\Http\Controllers\ClearanceController;
use App\Http\Controllers\BlotterController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Profile Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Resident/User Clearance Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/clearances', [ClearanceController::class, 'index'])->name('clearances.index');
    Route::get('/clearances/create', [ClearanceController::class, 'create'])->name('clearances.create');
    Route::post('/clearances', [ClearanceController::class, 'store'])->name('clearances.store');
    Route::get('/clearances/{clearance}', [ClearanceController::class, 'show'])->name('clearances.show');

    /*
    |--------------------------------------------------------------------------
    | Announcements View
    |--------------------------------------------------------------------------
    */
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('announcements.index');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin')->group(function () {

        Route::resource('residents', ResidentController::class);
        Route::resource('households', HouseholdController::class);
        Route::resource('blotters', BlotterController::class);

        /*
        |--------------------------------------------------------------------------
        | Admin Clearance Management
        |--------------------------------------------------------------------------
        */
        Route::get('/admin/clearances', [ClearanceController::class, 'adminIndex'])->name('clearances.admin');
        Route::put('/clearances/{clearance}/approve', [ClearanceController::class, 'approve'])->name('clearances.approve');
        Route::put('/clearances/{clearance}/reject', [ClearanceController::class, 'reject'])->name('clearances.reject');

        /*
        |--------------------------------------------------------------------------
        | Announcement Management
        |--------------------------------------------------------------------------
        */
        Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('/', [ReportController::class, 'index'])->name('index');
            Route::get('/residents', [ReportController::class, 'residents'])->name('residents');
            Route::get('/blotters', [ReportController::class, 'blotters'])->name('blotters');
            Route::get('/clearances', [ReportController::class, 'clearances'])->name('clearances');
            Route::get('/export', [ReportController::class, 'export'])->name('export');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Fallback Route
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});