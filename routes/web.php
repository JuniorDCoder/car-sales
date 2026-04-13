<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\CarController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/cars', [CarController::class, 'index'])->name('cars.index');
Route::get('/cars/{car:slug}', [CarController::class, 'show'])->name('cars.show');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/about', fn () => Inertia::render('About'))->name('about');
Route::get('/contact', fn () => Inertia::render('Contact'))->name('contact.index');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::redirect('dashboard', '/admin')->name('dashboard');
});

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('cars', Admin\CarController::class);
    Route::get('settings', [Admin\SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [Admin\SiteSettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/settings.php';
