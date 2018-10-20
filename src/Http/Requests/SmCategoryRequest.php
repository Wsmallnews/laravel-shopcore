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

class SmCategoryRequest extends Request
{
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                $route = Route::currentRouteName();
                if ('adminapi.shopProductCategorys.store' == $route) {
                    return [
                        'name' => 'required',
                        'icon' => 'required',
                    ];
                }
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $route = Route::currentRouteName();
                if ('adminapi.shopProductCategorys.update' == $route) {
                    return [
                        'name' => 'required',
                        'icon' => 'required',
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
            'name.required' => '请填写分类名称',
            'icon.required' => '请上传分类图标',
        ];
    }
}
