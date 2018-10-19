<?php

/*
 * This file is part of the overtrue/laravel-wechat.
 *
 * (c) overtrue <i@overtrue.me>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Illuminate\Routing\Router;

/**
 * Class Facade.
 *
 * @author overtrue <i@overtrue.me>
 */
class Facade extends LaravelFacade
{
    /**
     * 默认为 Server.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'shopcore';
    }

    /**
     * register routes
     */
    public static function routes(Router $router)
    {
        return app('shopcore')->routes($router);
    }

}
