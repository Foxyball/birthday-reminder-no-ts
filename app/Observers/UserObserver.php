<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Only create notification if there's an authenticated user, and not for the user being created
        if (Auth::check() && Auth::id() !== $user->id) {
            Notification::createSuccessNotification(
                Auth::id(),
                "User created: {$user->name}",
                $user,
                "A new user has been created.",
                "/users/{$user->id}/edit"
            );
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if (Auth::check()) {
            Notification::createInfoNotification(
                Auth::id(),
                "User updated: {$user->name}",
                $user,
                "User details have been updated.",
                "/users/{$user->id}/edit"
            );
        }
    }
}
