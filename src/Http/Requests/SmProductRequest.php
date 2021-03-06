<?php

namespace Wsmallnews\Shopcore\Http\Requests;
use Route;

class SmProductRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            case 'POST':
            {
                $route = Route::currentRouteName();
                if ($route == 'merchapi.shopProducts.store') {
                    return [
                        'name' => "required",
                        "category_id" => "required",
                        "type" => "required",
                        "images" => "required",
                        "origin_price" => 'required|min:1',
                        "price" => 'required|min:1',
                    ];
                }
            }
            case 'PUT':
            case 'PATCH':
            {
                $route = Route::currentRouteName();

                if($route == 'admin.shopProducts.noCheck'){
                    return [
                        "status_msg" => "required"
                    ];
                }
                if ($route == 'merchapi.shopProducts.update') {
                    return [
                        'name' => "required",
                        "category_id" => "required",
                        "type" => "required",
                        "images" => "required",
                        "origin_price" => 'required|min:1',
                        "price" => 'required|min:1',
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
            'name.required' => "请填写产品名称",
            'category_id.required' => "请选择分类",
            'type.required' => "请选择产品类型",
            'images.required' => "请上传产品相册",
            'origin_price.required' => "请填写产品原价",
            'origin_price.min' => "原价必须大于 0",
            'price.required' => "请填写产品现价",
            'price.min' => "价格必须大于 0",
            'status_msg' => "请填写驳回原因"
        ];
    }
}
