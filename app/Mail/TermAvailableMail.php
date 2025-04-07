<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TermAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $requestedPackages;

    /**
     * Create a new message instance.
     */
    public function __construct($date, $requestedPackages)
    {
        $this->date = $date;
        $this->requestedPackages = $requestedPackages;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Termin dostępny - oferta')
                    ->from('no-reply@dpoczta.pl', 'SalemWedding')
                    ->view('emails.term_available')
                    ->with([
                        'date' => $this->date
                    ]);
    }
}
