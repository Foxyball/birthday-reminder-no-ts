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
                "Contact added: {$contact->name}",
                $contact,
                "You've successfully added a new contact.",
                "/contact/{$contact->id}/edit"
            );
        }
    }
}
