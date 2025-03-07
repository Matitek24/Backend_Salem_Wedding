<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TermNotAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public $weddingDate;

    /**
     * Create a new message instance.
     */
    public function __construct($weddingDate)
    {
        $this->weddingDate = $weddingDate;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Termin zajęty')
                    ->from('no-reply@dpoczta.pl', 'Salem Wedding')
                    ->view('emails.term_not_available');
    }
}
