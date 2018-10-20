<?php

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
     * @param  \Illuminate\Contracts\Routing\Registrar  $router
     * @return void
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    /**
     * Register routes for transient tokens, clients, and personal access tokens.
     *
     * @return void
     */
    public function all()
    {
        $this->forShopRoutes();
    }

    /**
     * Register the routes shopcores.
     *
     * @return void
     */
    public function forShopRoutes()
    {
        $this->router->group(['prefix' => 'smadmin', 'namespace' => 'Admin'], function($router){
            // 产品分类
            $router->resource('shopProductCategorys', 'ShopProductCategorysController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'smadmin.shopProductCategorys'
            ]);

            // 产品路由
            $router->get('shopProducts/all', 'ShopProductsController@all')->name('smadmin.shopProducts.all');
            $router->get('shopProducts/trashIndex', 'ShopProductsController@trashIndex')->name('smadmin.shopProducts.trashIndex');
            $router->patch('shopProducts/{shopProduct}/setOnSale', 'ShopProductsController@setOnSale')->name('smadmin.shopProducts.setOnSale');
            $router->patch('shopProducts/{shopProduct}/setRecommend', 'ShopProductsController@setRecommend')->name('smadmin.shopProducts.setRecommend');
            $router->patch('shopProducts/{shopProduct}/setSpecial', 'ShopProductsController@setSpecial')->name('smadmin.shopProducts.setSpecial');
            $router->patch('shopProducts/{shopProduct}/restore', 'ShopProductsController@restore')->name('smadmin.shopProducts.restore');
            $router->delete('shopProducts/{shopProduct}/forceDelete', 'ShopProductsController@forceDelete')->name('smadmin.shopProducts.forceDelete');
            $router->resource("shopProducts", 'ShopProductsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                "names" => "smadmin.shopProducts"
            ]);
        });
    }

}
