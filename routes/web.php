<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkdayController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {

    Route::get('/vacation', function () {
        return view('vacation'); // Asegúrate de tener esta vista creada
    })->name('vacation');

    // PROFILE ROUTES
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // WORKDAY ROUTES AND FUNCTIONS
    Route::get('/workdays/{year?}/{month?}', [WorkdayController::class, 'index'])->name('workdays.index');
    Route::post('/workday/start', [WorkdayController::class, 'startWork'])->name('workday.start');
    Route::post('/workday/end', [WorkdayController::class, 'endWork'])->name('workday.end');
    Route::post('/workday/break', [WorkdayController::class, 'applyBreak'])->name('workday.break');
    Route::get('/workday/status', [WorkdayController::class, 'checkWorkStatus'])->name('workday.status');


});