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
use Kalnoy\Nestedset\NodeTrait;

class ShopProductCategory extends Model
{
    use NodeTrait;

    protected $table = 'shop_product_categorys';

    protected $appends = [
        'value', 'label', 'expand', 'parent_ids',
    ];

    /**********访问器开始***********/
    // cascader
    public function getLabelAttribute()
    {
        return $this->name;
    }

    public function getValueAttribute()
    {
        return strval($this->id);
    }

    public function getExpandAttribute()
    {
        return true;
    }

    public function getParentIdsAttribute()
    {
        $parent_ids = $this->ancestorsOf($this->id)->pluck('id');
        foreach ($parent_ids as $k => $v) {
            $parent_ids[$k] = strval($v);
        }

        return $parent_ids;
    }

    /**********访问器结束***********/

    /* =======================模型关联=======================*/
    //关联类别表
    public function parent()
    {
        return $this->belongsTo('App\Models\ShopProductCategory', 'parent_id');
    }

    //关联类别表
    public function children()
    {
        return $this->hasMany('App\Models\ShopProductCategory', 'parent_id');
    }

    //关联类别表
    public function product()
    {
        return $this->hasMany('App\Models\ShopProduct', 'category_id');
    }

    /* =======================模型关联 end=======================*/
}
