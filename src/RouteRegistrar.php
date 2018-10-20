<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore;

use Illuminate\Contracts\Routing\Registrar as Router;

class RouteRegistrar
{
    /**
     * The router implementation.
     *
     * @var \Illuminate\Contracts\Routing\Registrar
     */
    protected $router;

    /**
     * Create a new route registrar instance.
     *
     * @param \Illuminate\Contracts\Routing\Registrar $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     */
    public function all()
    {
        $this->forShopRoutes();
    }

    /**
     * Register the routes shopcores.
     */
    public function forShopRoutes()
    {
        $this->router->group(['prefix' => 'smadmin', 'namespace' => 'Admin'], function ($router) {
            // 产品分类
            $router->resource('smCategorys', 'SmCategorysController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'smadmin.smCategorys',
            ]);

            // 产品路由
            $router->get('smProducts/all', 'SmProductsController@all')->name('smadmin.smProducts.all');
            $router->get('smProducts/trashIndex', 'SmProductsController@trashIndex')->name('smadmin.smProducts.trashIndex');
            $router->patch('smProducts/{shopProduct}/setOnSale', 'SmProductsController@setOnSale')->name('smadmin.smProducts.setOnSale');
            $router->patch('smProducts/{shopProduct}/setRecommend', 'SmProductsController@setRecommend')->name('smadmin.smProducts.setRecommend');
            $router->patch('smProducts/{shopProduct}/setSpecial', 'SmProductsController@setSpecial')->name('smadmin.smProducts.setSpecial');
            $router->patch('smProducts/{shopProduct}/restore', 'SmProductsController@restore')->name('smadmin.smProducts.restore');
            $router->delete('smProducts/{shopProduct}/forceDelete', 'SmProductsController@forceDelete')->name('smadmin.smProducts.forceDelete');
            $router->resource('smProducts', 'SmProductsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'smadmin.smProducts',
            ]);
        });
    }
}
