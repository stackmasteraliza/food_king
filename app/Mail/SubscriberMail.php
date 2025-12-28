<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public string $title;
    public mixed $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject("Subscriber Notification")->markdown('emails.subscriber');
    }
}
