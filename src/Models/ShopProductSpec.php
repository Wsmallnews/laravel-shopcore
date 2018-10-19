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

use Illuminate\Database\Eloquent\Model;

class ShopProductSpec extends Model
{
    protected $appends = [
    ];

    /**
     * 相应规格属性下的产品信息.
     *
     * @param [type] $info [description]
     *
     * @return [type] [description]
     */
    public function shopProductSpec($info)
    {
        $shopProduct = ShopProduct::findOrFail($info->product_id);
        $shopProductSpec = self::where('product_id', $shopProduct->id);

        if ($info->spec_name_one) {
            $shopProductSpec = $shopProductSpec->where('spec_name_one', $info->spec_name_one);
        }

        if ($info->spec_name_two) {
            $shopProductSpec = $shopProductSpec->where('spec_name_two', $info->spec_name_two);
        }

        if ($info->spec_name_three) {
            $shopProductSpec = $shopProductSpec->where('spec_name_three', $info->spec_name_three);
        }

        $shopProductSpec = $shopProductSpec->first();

        return $shopProductSpec;
    }

    /**********访问器结束***********/
}
