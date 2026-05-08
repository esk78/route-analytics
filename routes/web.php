<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DailyRouteController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/daily-routes', [DailyRouteController::class, 'index'])
    ->middleware(['auth'])
    ->name('daily-routes.index');

Route::get('/daily-routes/{dailyRoute}', [DailyRouteController::class, 'show'])
    ->middleware(['auth'])
    ->name('daily-routes.show');

Route::get('/reports', [ReportController::class, 'index'])
    ->middleware(['auth'])
    ->name('reports.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
