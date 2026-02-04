<?php

namespace App\Listeners;

use App\Events\BookCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\Jobs\UpdateAuthorBookCountJob;

class TriggerBookCountJob
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
     * @param  BookCreated  $event
     * @return void
     */
    public function handle(BookCreated $event)
    {
        dispatch(new UpdateAuthorBookCountJob($event->book->author_id));
    }
}
