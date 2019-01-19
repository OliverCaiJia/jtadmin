<?php

namespace App\Mail;

class EventMail extends AppMail
{

    /**
     * EventMail constructor.
     */
    public function __construct()
    {
    }

    /** * Build the message.
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.test')
            ->with('');
    }
}
