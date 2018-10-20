<?php

namespace App\Listeners;

use Wsmallnews\shopcore\OperateLogEvent;
use App\Models\AdminLog;
use Request;
use Auth;

class OperateLogListener
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
     * @param  OperateLogEvent  $event
     * @return boolean
     */
    public function handle(OperateLogEvent $event)
    {
        $guards = config('shopcore.gurads');

        $data = $event->operateLogData;
        $data['type'] =empty( $data['type']) ? "admin" : $data['type'];

        if($data['type'] == "admin"){
            $operateLog = new AdminLog();
            $operateLog->admin_id = isset($data['admin_id']) ? $data['admin_id'] : Auth::guard($guards['admin'])->id();
        }

		$operateLog->log_info = $data['log_info'];
		$operateLog->ip_address = Request::ip();
		$operateLog->save();
		return true;
    }
}
