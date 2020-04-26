<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ErrorNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $content;

    public $user;

    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($content, $user, $subject)
    {
        $this->content = $content;
        $this->user = $user;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $emails = config('constants.EXCEPTION_MAIL_DEV_EMAILS');

        // we need to cc client email
        if (!empty($this->user->email)) {
            $emails[] = $this->user->email;
        }

        return $this->view('emails.error-notification', ['content' => $this->content, 'name' => $this->user->name])
            ->from('webmaster@fusedsoftware.com')
            ->cc($emails)
            ->subject($this->subject);
    }
}
