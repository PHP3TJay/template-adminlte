<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class RecoverUsernameEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
       $this->user = $user;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this
            ->subject('MyStep Username Recovery ')
            ->markdown('emails.forgotUsername')
            ->with([
                'user' => $this->user,
            ]);
    }
}
