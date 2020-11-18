<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Event;
use App\Events\WebsocketEvent;
use Illuminate\Support\Facades\Auth;

class UserLogedout
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $user = Auth::user();
        $user_event = $user->events()->create(['type' => 3, 'title' => $user->name]);
        WebsocketEvent::dispatch($user_event);
    }
}
