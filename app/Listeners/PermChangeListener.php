<?php

namespace App\Listeners;

use App\Events\PermChangeEvent;
use Cache;

class PermChangeListener
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
     * @param  PermChangeEvent $event
     *
     * @return void
     */
    public function handle(PermChangeEvent $event)
    {
        Cache::store('file')->forget('menus');//清理缓存
    }
}
