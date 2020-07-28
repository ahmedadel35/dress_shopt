<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $userName;
    public $userMail;
    public $userMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        object $mail
    ) {
        $this->userName = $mail->userName;
        $this->userMail = $mail->userMail;
        $this->userMessage = $mail->userMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Contact Us Mail
        return $this->from($this->userMail, $this->userName)
            ->subject(__('contact.mail.subject') . ': ' . $this->userName)
            ->markdown('emails.contact');
    }
}
