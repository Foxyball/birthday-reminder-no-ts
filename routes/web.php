<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
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
    Route::put('/bgc/users/change-status', [UserController::class, 'changeStatus'])
        ->name('admin.users.change-status');
    Route::resource('/bgc/users', UserController::class)
        ->except(['show']);
});

require __DIR__.'/auth.php';
