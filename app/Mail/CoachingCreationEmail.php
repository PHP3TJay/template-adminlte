<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\CoachingLog;


class CoachingCreationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $coaching;
    /**
     * Create a new message instance.
     */
    public function __construct(CoachingLog $coaching)
    {
        $this->coaching = $coaching;
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this
            ->subject('MySteps Coaching Assignment')
            ->markdown('emails.coaching_assignment')
            ->with([
                'coaching' => $this->coaching,
            ]);
    }
}
