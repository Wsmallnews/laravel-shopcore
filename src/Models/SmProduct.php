<?php

namespace Wsmallnews\Shopcore\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Exceptions\MyException;

class SmProduct extends Model
{
    use SoftDeletes;

    protected $appends = [
        'cart_num', 'type_name', 'images_arr', 'image', 'content_arr',
        'special_name', 'recommend_name', 'check_status_name',
        'category_id_arr'
    ];


    /**********通用模型操作方法***********/
    public function getSpecName($product) {
        $spec_name = [
            [ 'spec_name' => '', 'item' => [] ],
            [ 'spec_name' => '', 'item' => [] ],
            [ 'spec_name' => '', 'item' => [] ]
        ];

        if (!$product->specItem->isEmpty()) {
            $specItem = $product->specItem;

            $spec_name[0]['spec_name'] = $product->spec_name_one;
            foreach ($specItem->groupBy('spec_name_one') as $key => $value) {
                if ($key != '') {
                    $spec_name[0]['item'][] = $key;
                }
            }

            $spec_name[1]['spec_name'] = $product->spec_name_two;
            foreach ($specItem->groupBy('spec_name_two') as $key => $value) {
                if ($key != '') {
                    $spec_name[1]['item'][] = $key;
                }
            }

            $spec_name[2]['spec_name'] = $product->spec_name_three;
            foreach ($specItem->groupBy('spec_name_three') as $key => $value) {
                if ($key != '') {
                    $spec_name[2]['item'][] = $key;
                }
            }
        }
        return $spec_name;
    }
    /**********通用模型操作方法***********/


    /**********访问器开始***********/

    // 产品被加入购物车数量
    public function getCartNumAttribute(){
        $cart_num = 0;
        if (!empty($this->cart)) {     //
            $cart_num = $this->cart->product_num;
        }

        return $cart_num;
    }


    // 产品类型
    public function getTypeNameAttribute(){
        switch ($this->type) {
            case 'show' :
                $type_name = "普通";
                break;
            default:
                $type_name = "普通";
        }

        return $type_name;
    }


    public function getImageAttribute(){
        $images_arr = empty($this->images) ? [] : json_decode($this->images, true) ;

        return !empty($images_arr) ? $images_arr[0] : '';
    }

    public function getImagesArrAttribute(){
        $images_arr = empty($this->images) ? [] : json_decode($this->images, true) ;

        return $images_arr;
    }

    public function getContentArrAttribute(){
        $content_arr = empty($this->content) ? [] : json_decode($this->content, true) ;

        return $content_arr;
    }


    public function getCategoryIdArrAttribute(){
        $cagetory_ids = [];
        if ($this->category_id) {
            if (isset($this->category) && !is_null($this->category)) {
                $cagetory_ids = $this->category->ancestorsAndSelf($this->category_id)->pluck('id');
            } else {
                $category = new SmCategory();
                $cagetory_ids = $category->ancestorsAndSelf($this->category_id)->pluck('id');
            }

            foreach ($cagetory_ids as $k => $v) {
                $cagetory_ids[$k] = strval($v);
            }
        }

        return $cagetory_ids;
    }

    // 产品审核状态
    public function getCheckStatusNameAttribute(){
        switch ($this->check_status) {
            case -1:
                $status_name = '已驳回';
                break;
            case 1:
                $status_name = '已通过';
                break;
            case '0':
                $status_name = '待审核';
                break;
            default:
                $status_name = '';
                break;
        }
        return $status_name;
    }

    public function getSpecialNameAttribute() {
        return $this->is_special ? "特价" : "";
    }

    public function getRecommendNameAttribute() {
        return $this->is_recommend ? "推荐" : "";
    }

    public function getOriginPriceAttribute($value) {
        return floatVal($value);
    }

    public function getPriceAttribute($value) {
        return floatVal($value);
    }

    /**********访问器结束***********/


    /**********模型关联***********/
    public function category() {
        return $this->belongsTo('Wsmallnews\Shopcore\Models\SmCategory', "category_id");
    }

    // 关联规格
    public function specItem() {
        return $this->hasMany('Wsmallnews\Shopcore\Models\SmProductSpec', 'product_id');
    }

    // 关联属性
    public function productAttr() {
        return $this->hasMany('Wsmallnews\Shopcore\Models\SmProductAttr', 'product_id');
    }

    // 关联收藏
    public function collection() {
        return $this->hasOne('Wsmallnews\Shopcore\Models\Collection', 'item_id');
    }
    //=====================模型关联结束=========================//

    /**
     * 商品详情进入确认订单获取产品信息
     * @param  string $product_id      [description]
     * @param  string $spec_name_one   [description]
     * @param  string $spec_name_two   [description]
     * @param  string $spec_name_three [description]
     * @return [type]                  [description]
     */
    // public function shopProductInfo($product_id = "", $spec_name_one = "", $spec_name_two = "", $spec_name_three = ""){
    //     $shopProduct = $this->with("merch")->findOrFail($product_id);
    //
    //     $shopProductSpec = ShopProductSpec::where("product_id", $product_id);
    //     if($spec_name_one){
    //         $shopProductSpec = $shopProductSpec->where("spec_name_one", $spec_name_one);
    //     }
    //     if($spec_name_two){
    //         $shopProductSpec = $shopProductSpec->where("spec_name_two", $spec_name_two);
    //     }
    //     if($spec_name_three){
    //         $shopProductSpec = $shopProductSpec->where("spec_name_three", $spec_name_three);
    //     }
    //     $shopProductSpec = $shopProductSpec->first();
    //     if($shopProductSpec){
    //         $shopProduct->spec_name_one = $shopProductSpec->spec_name_one;
    //         $shopProduct->spec_name_two = $shopProductSpec->spec_name_two;
    //         $shopProduct->spec_name_three = $shopProductSpec->spec_name_three;
    //         $shopProduct->origin_price = $shopProductSpec->origin_price;
    //         $shopProduct->price = $shopProductSpec->price;
    //     }
    //
    //     return $shopProduct;
    // }
    //
    // /**
    //  * 减库存
    //  * @param  [type]  $info [description]
    //  * @param  integer $num  [description]
    //  * @return [type]        [description]
    //  */
    // public function stockChange($info){
    //     $shopProduct = $this->find($info->product_id);
    //     if($shopProduct->is_virtual){
    //         if($shopProduct->stock >= $info->product_num){
    //             $shopProduct->stock = $shopProduct->stock - $info->product_num;
    //             $shopProduct->save();
    //         }else{
    //             throw (new MyException)->setMessage($cartInfo->product_name."库存不足，下单失败", 2911);
    //         }
    //     }else{
    //         $shopProductSpec = new ShopProductSpec();
    //         $shopProductSpec = $shopProductSpec->shopProductSpec($info);
    //         if($shopProductSpec){
    //             if($shopProductSpec->stock >= $info->product_num){
    //                 $shopProductSpec->stock = $shopProductSpec->stock - $info->product_num;
    //                 $shopProductSpec->save();
    //             }else{
    //                 throw (new MyException)->setMessage($cartInfo->product_name."库存不足，下单失败", 2912);
    //             }
    //         }
    //     }
    // }


}
