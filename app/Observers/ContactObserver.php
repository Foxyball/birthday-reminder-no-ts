<?php

namespace App\Observers;

use App\Models\Contact;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class ContactObserver
{
    /**
     * Handle the Contact "created" event.
     */
    public function created(Contact $contact): void
    {
        if (Auth::check()) {
            Notification::createSuccessNotification(
                Auth::id(),
                __('messages.observer_contact_added', ['name' => $contact->name]),
                $contact,
                __('messages.observer_contact_added_message'),
                "/bgc/contact/{$contact->id}/edit"
            );
        }
    }
}
