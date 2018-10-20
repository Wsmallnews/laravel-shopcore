<?php

namespace Wsmallnews\Shopcore\Models;

use Illuminate\Database\Eloquent\Model;

class SmCart extends Model
{

    public function shopCartData($user_id = "", $cart_ids = array()){
        $shopCarts = $this->with('shopProduct')
                    ->where("user_id", $user_id)
                    ->whereIn('id', $cart_ids)
                    ->get();

        return $shopCarts;
    }

    public function shopCartDelete($user_id = "", $cart_ids = array()){
        $shopCarts = $this->shopCartData($user_id, $cart_ids);
        foreach ($shopCarts as $key => $shopCart) {
            $this->where('id', $shopCart->id)->delete();
        }

        return true;
    }


    /* =======================访问器=======================*/



    /* =======================访问器 end=======================*/

    /* =======================模型关联=======================*/
    //关联商品表
    public function shopProduct(){
        return $this->belongsTo('App\Models\ShopProduct', 'product_id');
    }

    //关联用户表
    public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    //关联店铺表
    public function merch(){
        return $this->belongsTo('App\Models\Merch', 'merch_id');
    }
    /* =======================模型关联 end=======================*/

}
