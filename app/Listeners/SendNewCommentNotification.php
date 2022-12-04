<?php

namespace App\Listeners;

use App\Events\CommentAdded;
use App\Jobs\SendNewCommentEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewCommentNotification
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
     * @param  \App\Events\CommentAdded  $event
     * @return void
     */
    public function handle(CommentAdded $event)
    {
        SendNewCommentEmail::dispatch($event->email, $event->article_name);
        // SendNewCommentEmail::dispatch();
    }
}
