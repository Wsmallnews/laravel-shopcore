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

class ShopProductEvaluateRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                return [
                    // "shop_order_id" => "required",
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
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
            // 'shop_order_id.required' => "订单获取失败",
        ];
    }
}
