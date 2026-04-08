<?php

use App\Http\Controllers\CalendarController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SocialAuthLoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/bgc/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/bgc/calendar', [CalendarController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('calendar.index');

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

Route::middleware(['auth'])->group(function () {
    Route::get('/bgc/import/{import}/details', [ImportController::class, 'details'])->name('admin.import.details');
    Route::get('/bgc/import', [ImportController::class, 'show'])->name('admin.import.show');
    Route::post('/bgc/import', [ImportController::class, 'store'])->name('admin.import.store');
    Route::get('/bgc/import/template', [ImportController::class, 'template'])->name('admin.import.template');
});

Route::middleware(['auth'])->group(function () {
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

Route::middleware(['auth'])->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [\App\Http\Controllers\NotificationController::class, 'index'])->name('index');
    Route::get('/unread-count', [\App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('unread-count');
    Route::post('/{notification}/mark-as-read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-as-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
    Route::delete('/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('destroy');
    Route::post('/clear-read', [\App\Http\Controllers\NotificationController::class, 'clearRead'])->name('clear-read');
});

require __DIR__.'/auth.php';
