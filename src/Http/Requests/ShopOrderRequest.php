<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Http\Requests;

use Route;

class ShopOrderRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                $route = Route::currentRouteName();
                if ('deskapi.shopOrders.orderAdd' == $route) {
                    return [
                        'product_id' => 'required',
                        'num' => 'required',
                    ];
                }

                return [
                    'product_ids' => 'required',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }
    }

    public function messages()
    {
        return [
            'product_id.required' => '产品参数传输错误',
            'num.required' => '数量不能少于1',
            'product_ids.required' => '产品参数传输错误',
        ];
    }
}
