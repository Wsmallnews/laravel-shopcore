<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Auth;

class CommonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 获取 auth 驱动，因为 config/auth.php 中default 为 web,此方法可以不予使用， 可直接 Auth::user(); 即为 web 驱动.
     *
     * @author @smallnews 2017-06-03
     *
     * @return [type] [description]
     */
    protected function guard()
    {
        $guards = config('shopcore.gurads');

        return Auth::guard($guards['admin']);
    }
}
