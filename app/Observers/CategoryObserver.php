<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class CategoryObserver
{
    /**
     * Handle the Category "created" event.
     */
    public function created(Category $category): void
    {
        // Only create notification if there's an authenticated user
        if (Auth::check()) {
            Notification::createSuccessNotification(
                Auth::id(),
                __('messages.observer_category_added', ['name' => $category->name]),
                $category,
                __('messages.observer_category_added_message'),
                "/bgc/category/{$category->id}/edit"
            );
        }
    }
}
