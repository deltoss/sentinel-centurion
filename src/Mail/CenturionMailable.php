<?php

namespace Deltoss\Centurion\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CenturionMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        // If the mail from configuration is defined,
        // use those settings. Otherwise,
        // it'd default to using the settings
        // specified in config/mail.php
        if (config('centurion.mails.from.address'))
        {
            $name = config('centurion.mails.from.name') ? config('centurion.mails.from.name') : null;
            $this->from(config('centurion.mails.from.address'), $name);
        }
    }
}
