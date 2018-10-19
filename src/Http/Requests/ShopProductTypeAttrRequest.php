<?php

namespace Wsmallnews\Shopcore\Http\Requests;

class ShopProductTypeAttrRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch($this->method())
        {
            // CREATE
            case 'POST':
            {
                return [
                    'name' => "required",
                    "type" => "required",
                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                $id = $this->route('user');
                return [
                    'name' => "required",
                    "type" => "required",
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
            'name.required' => "请填写类型名称",
            'type.required' => "请填写类型属性",
        ];
    }
}
