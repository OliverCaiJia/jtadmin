<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class OrderActionEvent extends Event
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $operatorId;
    public $operatorName;
    public $model;
    public $orderId;
    public $type;
    public $content;

    /**
     * OrderActionEvent constructor.
     *
     * @param $model
     * @param $orderId
     * @param $type
     * @param $content
     */
    public function __construct($model, $orderId, $type, $content)
    {
        $this->operatorId = auth('admin')->user()->id ?? '系统';
        $this->operatorName = auth('admin')->user()->name ?? '系统';
        $this->model = $model;
        $this->orderId = $orderId;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
