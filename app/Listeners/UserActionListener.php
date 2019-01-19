<?php

namespace App\Listeners;

use Log;
use App\Events\UserActionEvent;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserActionListener implements ShouldQueue
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
     * @param  UserActionEvent $event
     *
     * @return void
     */
    public function handle(UserActionEvent $event)
    {
        $str = '管理员:' . $event->adminName . '(id:' . $event->uid . ')' . $event->content;

        Log::info($str);
    }
}
