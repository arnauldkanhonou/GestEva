<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;
    public $viewData = '';


    /**
     * Create a new message instance.
     *
     * @param array $datas
     */
    public function __construct(Array $datas)
    {
        $this->viewData=$datas;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('NOTIFICATION ASSMANAGER')
            ->markdown('emails.notification');
    }
}
