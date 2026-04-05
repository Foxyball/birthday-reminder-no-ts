<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Password;

class AccountCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $resetToken;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public User $user
    ) {
        $this->resetToken = Password::createToken($this->user);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('messages.account_created_subject'),
            from: config('mail.from.address'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-created',
            with: [
                'user' => $this->user,
                'resetUrl' => url(route('password.reset', ['token' => $this->resetToken, 'email' => $this->user->email], false)),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
