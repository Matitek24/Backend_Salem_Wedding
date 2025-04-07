<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientEmail;
    public $clientName;
    public $clientAddress;
    public $date;
    public $packages;

    /**
     * Create a new message instance.
     */
    public function __construct($clientEmail, $clientName, $clientAddress, $date, $packages)
    {
        $this->clientEmail   = $clientEmail;
        $this->clientName    = $clientName;
        $this->clientAddress = $clientAddress;
        $this->date          = $date;
        $this->packages      = $packages;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Nowe zapytanie o termin - ' . $this->date)
                    ->from('no-reply@dpoczta.pl', 'SalemWedding')
                    ->view('emails.admin-notification')
                    ->with([
                        'clientEmail'   => $this->clientEmail,
                        'clientName'    => $this->clientName,
                        'clientAddress' => $this->clientAddress,
                        'date'          => $this->date,
                        'packages'      => $this->packages,
                    ]);
    }
}
