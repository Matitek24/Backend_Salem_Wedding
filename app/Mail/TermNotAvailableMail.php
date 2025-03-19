<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TermNotAvailableMail extends Mailable
{
    use Queueable, SerializesModels;

    public $date;
    public $unavailablePackages;
    public $availableRequestedPackages;
    public $alternativePackages;

    /**
     * Create a new message instance.
     */
    public function __construct($date, $unavailablePackages, $availableRequestedPackages, $alternativePackages)
    {
        $this->date = $date;
        $this->unavailablePackages = $unavailablePackages;
        $this->availableRequestedPackages = $availableRequestedPackages;
        $this->alternativePackages = $alternativePackages;
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
