<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TwoFactorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kod;

    public function __construct($kod)
    {
        $this->kod = $kod;
    }

    public function build()
    {
        return $this->subject('Kurumsal Portal - Güvenlik Kodunuz (2FA)')
                    ->view('emails.2fa');
    }
}