<?php

namespace Wsmallnews\Shopcore\Http\Requests;

use Route;

class SmCategoryRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                $route = Route::currentRouteName();
                if ($route == 'adminapi.shopProductCategorys.store') {
                    return [
                        'name' => "required",
                        "icon" => "required",
                    ];
                }
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $route = Route::currentRouteName();
                if ($route == 'adminapi.shopProductCategorys.update') {
                    return [
                        'name' => "required",
                        "icon" => "required",
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
            'name.required' => "请填写分类名称",
            'icon.required' => "请上传分类图标",
        ];
    }
}
