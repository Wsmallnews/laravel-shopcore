<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Events;

use Illuminate\Queue\SerializesModels;

class OperateLogEvent extends Event
{
    use SerializesModels;

    public $operateLogData = [];

    /**
     * Create a new event instance.
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
