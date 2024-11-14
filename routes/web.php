<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationController;
use App\Http\Controllers\WorkdayController;
use App\Http\Controllers\Admin\WorkerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // WORKDAY ROUTES AND FUNCTIONS
    Route::get('/workdays/{year?}/{month?}', [WorkdayController::class, 'index'])->name('workdays.index');
    Route::post('/workday/start', [WorkdayController::class, 'startWork'])->name('workday.start');
    Route::post('/workday/end', [WorkdayController::class, 'endWork'])->name('workday.end');
    Route::post('/workday/break', [WorkdayController::class, 'applyBreak'])->name('workday.break');
    Route::get('/workday/status', [WorkdayController::class, 'checkWorkStatus'])->name('workday.status');

    // VACATIONS ROUTES AND FUNCTIONS
    Route::get('/vacations', [VacationController::class, 'index'])->name('vacations.index');
    Route::post('/vacations', [VacationController::class, 'store'])->name('vacations.store');

    // ADMIN ROUTES AND FUNCTIONS
    Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('workers', [WorkerController::class, 'index'])->name('workers.index');
        Route::get('create_worker', [WorkerController::class, 'createWorker'])->name('create_worker');
        Route::get('workers/{worker}/edit', [WorkerController::class, 'edit'])->name('workers.edit');
        Route::delete('workers/{worker}', [WorkerController::class, 'destroy'])->name('workers.destroy');
        Route::post('register-worker', [WorkerController::class, 'registerWorker'])->name('registerWorker');
    });


});