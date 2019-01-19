<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class PermChangeEvent extends Event
{
    use SerializesModels;

    /**
     * PermChangeEvent constructor.
     *
     * @param array $permChange
     */
    public function __construct($permChange = [])
    {
        $this->permChange = $permChange;
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
