<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TermAvailableMail extends Mailable
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
        return $this->subject('Termin dostępny - oferta')
                    ->from('no-reply@dpoczta.pl', 'SalemWedding')
                    ->view('emails.term_available');
    }
}
