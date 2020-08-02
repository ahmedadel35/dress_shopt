<?php

namespace App\Listeners;

use CartSet;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MergeUserCartAfterLogIn
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
     * @param  Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function handle(Login $event)
    {
        CartSet::afterLoggedIn($event->user);
    }
}
