<?php

namespace Deltoss\Centurion\Mail\Auth;

use Illuminate\Bus\Queueable;
use Deltoss\Centurion\Mail\CenturionMailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends CenturionMailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $reminder;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $reminder)
    {
        parent::__construct();

        $this->user = $user;
        $this->reminder = $reminder;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('centurion::mails/forgot_password')
            ->subject(trans('centurion::mails.forgot_password.subject'))
            ->with(['user' => $this->user, 'reminder' => $this->reminder]);
    }
}
