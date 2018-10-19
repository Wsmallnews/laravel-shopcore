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

class ShopProductRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                $route = Route::currentRouteName();
                if ('merchapi.shopProducts.store' == $route) {
                    return [
                        'name' => 'required',
                        // "cat_id" => "required",
                        'category_id' => 'required',
                        'type' => 'required',
                        'images' => 'required',
                        'origin_price' => 'required|min:1',
                        'price' => 'required|min:1',
                    ];
                }
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $route = Route::currentRouteName();

                if ('admin.shopProducts.noCheck' == $route) {
                    return [
                        'status_msg' => 'required',
                    ];
                }
                if ('merchapi.shopProducts.update' == $route) {
                    return [
                        'name' => 'required',
                        // "cat_id" => "required",
                        'category_id' => 'required',
                        'type' => 'required',
                        'images' => 'required',
                        'origin_price' => 'required|min:1',
                        'price' => 'required|min:1',
                    ];
                }
            }
            case 'GET':
            {
            }
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
            'name.required' => '请填写产品名称',
            'category_id.required' => '请选择分类',
            // 'cat_id.required' => "请选择产品分类",
            'type.required' => '请选择产品类型',
            'images.required' => '请上传产品相册',
            'origin_price.required' => '请填写产品原价',
            'origin_price.min' => '原价必须大于 0',
            'price.required' => '请填写产品现价',
            'price.min' => '价格必须大于 0',
            'status_msg' => '请填写驳回原因',
        ];
    }
}
