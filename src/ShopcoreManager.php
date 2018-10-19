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

use Illuminate\Routing\Router;

class ShopcoreManager
{
    public static function routes(Router $router)
    {
        Route::group(['prefix' => 'sm', 'namespace' => '\Wsmallnews\Shopcore\Http\Controllers\Admin'], function ($router) {
            $router->get('login', 'LoginController@login')->name('adminapi.login');    // 获取 行政区划代码
        });

        Route::group(['prefix' => 'sm', 'namespace' => '\Wsmallnews\Shopcore\Http\Controllers\Admin'], function ($router) {
            // 产品分类
            $router->resource('shopProductCategorys', 'ShopProductCategorysController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.shopProductCategorys',
            ]);

            // 产品路由
            $router->get('shopProducts/all', 'ShopProductsController@all')->name('adminapi.shopProducts.all');
            $router->get('shopProducts/trashIndex', 'ShopProductsController@trashIndex')->name('adminapi.shopProducts.trashIndex');
            $router->patch('shopProducts/{shopProduct}/setOnSale', 'ShopProductsController@setOnSale')->name('adminapi.shopProducts.setOnSale');
            $router->patch('shopProducts/{shopProduct}/setRecommend', 'ShopProductsController@setRecommend')->name('adminapi.shopProducts.setRecommend');
            $router->patch('shopProducts/{shopProduct}/setSpecial', 'ShopProductsController@setSpecial')->name('adminapi.shopProducts.setSpecial');
            $router->patch('shopProducts/{shopProduct}/restore', 'ShopProductsController@restore')->name('adminapi.shopProducts.restore');
            $router->delete('shopProducts/{shopProduct}/forceDelete', 'ShopProductsController@forceDelete')->name('adminapi.shopProducts.forceDelete');
            $router->resource('shopProducts', 'ShopProductsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.shopProducts',
            ]);

            // 地域
            $router->resource('regions', 'RegionsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.regions',
            ]);

            //  销售管理
            $router->get('sellers/all', 'SellersController@all')->name('adminapi.sellers.all');
            $router->patch('sellers/resetPassword/{id}', 'SellersController@resetPassword')->name('adminapi.sellers.resetPassword');
            $router->resource('sellers', 'SellersController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.sellers',
            ]);

            // 机构管理
            $router->get('institutions/all', 'InstitutionsController@all')->name('adminapi.institutions.all');
            $router->resource('institutions', 'InstitutionsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.institutions',
            ]);

            // 客户管理
            $router->get('customers/info', 'CustomersController@info')->name('adminapi.customers.info');
            $router->resource('customers', 'CustomersController', [
                'only' => ['index', 'store', 'destroy'],
                'names' => 'adminapi.customers',
            ]);

            // 业绩管理
            $router->resource('achievements', 'AchievementsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.achievements',
            ]);

            $router->get('admins/info', 'AdminsController@info')->name('adminapi.admin.info');
            $router->post('admins/setRole', 'AdminsController@setRole')->name('adminapi.admins.setRole');
            $router->patch('admins/resetPassword/{id}', 'AdminsController@resetPassword')->name('adminapi.admins.resetPassword');
            $router->patch('admins/modifySelfPassword', 'AdminsController@modifySelfPassword')->name('adminapi.admins.modifySelfPassword');
            $router->resource('admins', 'AdminsController', [
                'only' => ['index', 'show', 'store', 'update', 'destroy'],
                'names' => 'adminapi.admins',
            ]);

            $router->resource('adminLogs', 'AdminLogsController', [
                'only' => ['index'],
                'names' => 'admin.adminLogs',
            ]);

            $router->get('roles/roleAll', 'RolesController@roleAll')->name('adminapi.roles.roleAll');
            $router->post('roles/{role}/givePermissions', 'RolesController@givePermissions')->name('adminapi.roles.givePermissions');
            $router->resource('roles', 'RolesController', [
                'only' => ['store', 'index', 'show', 'update', 'destroy'],
                'names' => 'adminapi.roles',
            ]);

            $router->get('permissions/adminIndex', 'PermissionsController@adminIndex')->name('adminapi.permissions.adminIndex');
            $router->get('permissions/index', 'PermissionsController@index')->name('adminapi.permissions.index');
            $router->get('permissions/givePermissions', 'PermissionsController@givePermissions')->name('adminapi.permissions.givePermissions');
            $router->get('permissions/create/{permission}', 'PermissionsController@create')->name('adminapi.permissions.create')->where('permission', '(admin|apimerchacc)');
            $router->resource('permissions', 'PermissionsController', [
                'only' => ['store', 'edit', 'update', 'destroy'],
                'names' => 'adminapi.permissions',
            ]);
        });
    }
}
