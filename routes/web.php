<?php

use App\Http\Controllers\ProfileController;
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

    Route::get('/daily_hours', function () {
        return view('daily_hours'); // Asegúrate de tener esta vista creada
    })->name('daily_hours');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});