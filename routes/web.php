<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CriterionController;
use App\Http\Controllers\ScoringController;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JudgeController;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::get('/leaderboard', [App\Http\Controllers\AudienceController::class , 'index'])->name('audience.leaderboard');
Route::get('/leaderboard/status', [App\Http\Controllers\AudienceController::class , 'status'])->name('audience.leaderboard.status');

Route::get('/login', [AuthController::class , 'showLogin'])->name('login')->middleware('throttle:10,1');
Route::post('/login', [AuthController::class , 'authenticate'])->name('login.post')->middleware('throttle:5,1');
Route::post('/logout', [AuthController::class , 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class , 'index'])->name('dashboard');
    Route::get('/dashboard/data', [DashboardController::class , 'data'])->name('dashboard.data');

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('users', UserController::class);

        // Settings
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/batch', [App\Http\Controllers\SettingController::class, 'batchUpdate'])->name('settings.batch');

        // Archives
        Route::get('/archives', [App\Http\Controllers\ArchiveController::class, 'index'])->name('archives.index');
        Route::post('/archives/store', [App\Http\Controllers\ArchiveController::class, 'store'])->name('archives.store');
        Route::get('/archives/{id}', [App\Http\Controllers\ArchiveController::class, 'show'])->name('archives.show');
        Route::get('/archives/{id}/download', [App\Http\Controllers\ArchiveController::class, 'download'])->name('archives.download');
        Route::delete('/archives/{id}', [App\Http\Controllers\ArchiveController::class, 'destroy'])->name('archives.destroy');
    });

    Route::middleware(['role:admin|committee'])->group(function () {
        Route::resource('judges', JudgeController::class);
        Route::get('/contestants/template', [ContestantController::class , 'downloadTemplate'])->name('contestants.template');
        Route::post('/contestants/import', [ContestantController::class , 'import'])->name('contestants.import');
        Route::resource('contestants', ContestantController::class);
        Route::get('/criteria/template', [CriterionController::class, 'downloadTemplate'])->name('criteria.template');
        Route::post('/criteria/import', [CriterionController::class, 'import'])->name('criteria.import');
        Route::resource('criteria', CriterionController::class);

        // Reports
        Route::get('/reports', [App\Http\Controllers\ReportController::class , 'index'])->name('reports.index');
        Route::get('/reports/export', [App\Http\Controllers\ReportController::class , 'export'])->name('reports.export');
        Route::get('/reports/{contestant}', [App\Http\Controllers\ReportController::class , 'show'])->name('reports.show');

        // Events
        Route::get('/events', [App\Http\Controllers\EventController::class, 'index'])->name('events.index');
        Route::post('/events', [App\Http\Controllers\EventController::class, 'store'])->name('events.store');
        Route::post('/events/{event}/start', [App\Http\Controllers\EventController::class, 'start'])->name('events.start');
        Route::delete('/events/{event}', [App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
    });

        Route::middleware(['role:judge'])->group(function () {
            Route::get('/scoring', [ScoringController::class , 'index'])->name('scoring.index');
            Route::get('/scoring/{contestant}', [ScoringController::class , 'create'])->name('scoring.create');
            Route::post('/scoring/{contestant}', [ScoringController::class , 'store'])->name('scoring.store');
        }
        );
    });
