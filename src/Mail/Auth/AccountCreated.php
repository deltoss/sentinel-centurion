<?php

namespace Deltoss\Centurion\Mail\Auth;

use Illuminate\Bus\Queueable;
use Deltoss\Centurion\Mail\CenturionMailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccountCreated extends CenturionMailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $activation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $activation)
    {
        parent::__construct();
        
        $this->user = $user;
        $this->activation = $activation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('centurion::mails/account_created')
            ->subject(trans('centurion::mails.account_created.subject'))
            ->with(['user' => $this->user, 'activation' => $this->activation]);
    }
}
