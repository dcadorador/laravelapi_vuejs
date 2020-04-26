<?php

namespace App\Listeners;

use App\Events\ErrorProcessing;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\ErrorNotification;
use Illuminate\Support\Facades\Mail;

class SendErrorNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ErrorProcessing  $event
     * @return void
     */
    public function handle(ErrorProcessing $event)
    {
        $email = env('EMAIL_ERRORS');
        if (empty($email)) {
            \Log::error('[ERR_EMAIL] no EMAIL_ERRORS in your env.');
            return;
        }

        Mail::to($email)
            ->send(new ErrorNotification($event->content, $event->user, $event->subject));
    }
}
