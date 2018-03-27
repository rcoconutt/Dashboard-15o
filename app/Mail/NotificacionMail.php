<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificacionMail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $name;
    public $title;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $name, $title)
    {
        $this->message = $message;
        $this->name = $name;
        $this->title = $title;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.notification')->subject($this->title)->with([
            'nombre' => $this->name,
            'notification' => $this->message,
        ]);
    }
}
