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
                __('messages.observer_user_created', ['name' => $user->name]),
                $user,
                __('messages.observer_user_created_message'),
                "/bgc/users/{$user->id}/edit"
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
                __('messages.observer_user_updated', ['name' => $user->name]),
                $user,
                __('messages.observer_user_updated_message'),
                "/bgc/users/{$user->id}/edit"
            );
        }
    }
}
