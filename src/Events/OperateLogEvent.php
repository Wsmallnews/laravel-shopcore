<?php

namespace Wsmallnews\Shopcore\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OperateLogEvent extends Event
{
    use SerializesModels;

    public $operateLogData = [];

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($operateLogData)
    {
        $this->operateLogData = $operateLogData;
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
