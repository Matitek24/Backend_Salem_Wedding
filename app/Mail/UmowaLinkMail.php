<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UmowaLinkMail extends Mailable
{
    use Queueable, SerializesModels;

    public $link;

    public function __construct($link)
    {
        $this->link = $link;
    }

    public function build()
    {
        return $this->subject('Twoja umowa do podpisania')
                    ->view('emails.umowa-link')
                    ->with(['link' => $this->link]);
    }
}
