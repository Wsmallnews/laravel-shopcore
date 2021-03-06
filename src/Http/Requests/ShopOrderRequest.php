<?php
namespace Wsmallnews\Shopcore\Http\Requests;

use Route;

class ShopOrderRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                $route = Route::currentRouteName();
                if($route == 'deskapi.shopOrders.orderAdd'){
                    return [
                        "product_id" => 'required',
                        "num" => "required"
                    ];
                }
                return [
                    "product_ids" => 'required'
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
            "product_id.required" => "产品参数传输错误",
            "num.required" => "数量不能少于1",
            "product_ids.required" => "产品参数传输错误"
        ];
    }
}
