<?php

namespace Wsmallnews\Shopcore\Http\Requests;
use Route;

class ShopProductEvaluateRequest extends Request
{
    public function rules()
    {
        switch($this->method())
        {
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
