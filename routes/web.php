<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthLoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/bgc/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/lang/{locale}', function (string $locale) {
    if (in_array($locale, ['en', 'bg'], true)) {
        session()->put('locale', $locale);
    }

    return redirect()->back();
})->name('lang.switch');

Route::middleware(['auth', 'admin'])->group(function () {
    Route::put('/bgc/category/change-status', [CategoryController::class, 'changeStatus'])->name('admin.category.change-status');
    Route::resource('/bgc/category', CategoryController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::put('/bgc/contact/change-status', [ContactController::class, 'changeStatus'])->name('admin.contact.change-status');
    Route::resource('/bgc/contact', ContactController::class);
});

Route::middleware(['auth', 'admin'])->group(function () {
    Route::put('/bgc/users/change-status', [UserController::class, 'changeStatus'])
        ->name('admin.users.change-status');
    Route::get('/bgc/users/deactivated', [UserController::class, 'deactivated'])
        ->name('users.deactivated');
    Route::patch('/bgc/users/{id}/restore', [UserController::class, 'restore'])
        ->name('users.restore');
    Route::resource('/bgc/users', UserController::class)
        ->except(['show']);
});

// Socialite routes
Route::get('/auth/google/redirect', [SocialAuthLoginController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('/auth/google/callback', [SocialAuthLoginController::class, 'handleGoogleCallback'])->name('google.callback');

require __DIR__.'/auth.php';
