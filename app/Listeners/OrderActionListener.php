<?php

namespace App\Listeners;

use App\Events\OrderActionEvent;
use App\Models\Orm\UserOrderOperationLog;

class OrderActionListener
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
     * 存储 log
     *
     * @param \App\Events\OrderActionEvent $event
     */
    public function handle(OrderActionEvent $event)
    {
        UserOrderOperationLog::create([
            'order_id' => $event->orderId,
            'type' => $event->type,
            'operator_id' => $event->operatorId,
            'operator_name' => $event->operatorName,
            'content' => $event->content,
            'model' => $event->model
        ]);
    }
}
