<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientEmail;
    public $date;
    public $packages;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($clientEmail, $date, $packages)
    {
        $this->clientEmail = $clientEmail;
        $this->date = $date;
        $this->packages = $packages;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Nowe zapytanie o termin - ' . $this->date)
                    ->from('no-reply@dpoczta.pl', 'SalemWedding')
                    ->view('emails.admin-notification');
    }
}