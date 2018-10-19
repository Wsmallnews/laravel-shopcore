<?php

namespace Wsmallnews\Shopcore\Models;


class ShopOrderItem extends Model
{
    protected $appends = [

    ];

    /* =======================模型关联=======================*/
    //关联用户表
    public function shopProduct(){
        return $this->belongsTo('App\Models\ShopProduct', 'product_id');
    }
    /* =======================模型关联 end=======================*/
}
