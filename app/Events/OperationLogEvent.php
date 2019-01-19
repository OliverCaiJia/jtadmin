<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class OperationLogEvent extends Event
{
    use SerializesModels;

    public $type;
    public $content;

    /**
     * OperationLogEvent constructor.
     * @param $type
     * @param $content
     */
    public function __construct($type, $content = '')
    {
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
