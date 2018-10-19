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

class ShopProductTypeRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            // CREATE
            case 'POST':
            {
                return [
                    'name' => 'required|unique:shop_product_types,name',
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $id = $this->route('user');

                return [
                    'name' => 'required|unique:shop_product_types,name,'.$id,
                ];
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
            'name.required' => '请填写类型名称',
            'name.unique' => '类型名称已存在',
        ];
    }
}
