<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Wsmallnews\Shopcore\Models;

class ShopOrderItem extends Model
{
    protected $appends = [
    ];

    /* =======================模型关联=======================*/
    //关联用户表
    public function shopProduct()
    {
        return $this->belongsTo('App\Models\ShopProduct', 'product_id');
    }

    /* =======================模型关联 end=======================*/
}
