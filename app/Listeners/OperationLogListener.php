<?php

namespace App\Listeners;

use App\Events\OperationLogEvent;
use App\Models\Factory\Admin\Logs\AdminOperationLogFactory;

class OperationLogListener
{
    /**
     * OperationLogListener constructor.
     */
    public function __construct()
    {
    }

    /**
     * 存储 log
     * @param OperationLogEvent $event
     */
    public function handle(OperationLogEvent $event)
    {
        AdminOperationLogFactory::createLog($event->type, $event->content);
    }
}
