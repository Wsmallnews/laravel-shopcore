<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace App\Listeners;

use Wsmallnews\shopcore\OperateLogEvent;
use App\Models\AdminLog;
use Request;
use Auth;

class OperateLogListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param OperateLogEvent $event
     *
     * @return bool
     */
    public function handle(OperateLogEvent $event)
    {
        $guards = config('shopcore.gurads');

        $data = $event->operateLogData;
        $data['type'] = empty($data['type']) ? 'admin' : $data['type'];

        if ('admin' == $data['type']) {
            $operateLog = new AdminLog();
            $operateLog->admin_id = isset($data['admin_id']) ? $data['admin_id'] : Auth::guard($guards['admin'])->id();
        }

        $operateLog->log_info = $data['log_info'];
        $operateLog->ip_address = Request::ip();
        $operateLog->save();

        return true;
    }
}
