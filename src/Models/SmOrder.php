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
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SmOrder extends Model
{
    use SoftDeletes;

    protected $appends = [
        'order_status_name', 'pay_name', 'pay_type_name', 'surplus_fee', 'type_name', 'virtual_name',
        'oper_btns',        // 订单可操作按钮数组
        'product_num',      // 订单产品数
        'real_fee',          // 实付款
    ];

    protected $dates = ['payed_at', 'created_at', 'evaluate_at', 'updated_at', 'send_at', 'return_at'];

    public $order_action = \App\Models\ShopOrderAction::class;

    /* =======================访问器=======================*/

    public function getPayNameAttribute()
    {
        switch ($this->is_pay) {
            case 0:
                $pay_name = '未支付';

                break;
            case 1:
                $pay_name = '支付中';

                break;
            case 2:
                $pay_name = '已支付';

                break;
            default:
                $pay_name = '';

                break;
        }

        return $pay_name;
    }

    public function getVirtualNameAttribute()
    {
        return $this->is_virtual ? '虚拟' : '实物';
    }

    public function getTypeNameAttribute()
    {
        switch ($this->type) {
            case 'shop_coupon':
                $type_name = '优惠券订单';

                break;
            default:
                $type_name = '普通';

                break;
        }

        return $type_name;
    }

    // 订单状态
    public function getOrderStatusNameAttribute()
    {
        if ($this->is_pay < 2 && 0 == $this->is_cancel) {
            $order_status_name = '待付款';
        } elseif (2 == $this->is_pay && 0 == $this->is_send) {
            $order_status_name = '待发货';
        } elseif (2 == $this->is_pay && 1 == $this->is_send && 0 == $this->is_get) {
            $order_status_name = '待收货';
        } elseif (2 == $this->is_pay && 1 == $this->is_send && 1 == $this->is_get) {
            $order_status_name = '已完成';
        } elseif ($this->is_pay < 2 && 1 == $this->is_cancel) {
            $order_status_name = '已取消';
        }

        return $order_status_name;
    }

    // 订单状态
    public function getPayTypeNameAttribute()
    {
        $pay_type_arr = array_values(array_filter(explode(',', $this->pay_type)));
        $pay_type_name = '';
        foreach ($pay_type_arr as $key => $type) {
            $pay_type_name .= $this->pay_type_all[$type];
        }

        return $pay_type_name;
    }

    // 剩余 需要付款金额
    public function getSurplusFeeAttribute()
    {
        $pay_total_fee = 0;     // 累计支付总金额
        foreach ($this->fee_field as $value) {
            $pay_total_fee += $this->{$value};
        }

        $surplus_fee = $this->total_fee - $pay_total_fee;

        return $surplus_fee ? $surplus_fee : 0;
    }

    // 获取订单可操作按钮
    public function getOperBtnsAttribute()
    {
        return $this->getOperBtn($this);
    }

    /* =======================访问器 end=======================*/

    /* =======================模型关联=======================*/
    //关联用户表
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    //关联商户表
    public function merch()
    {
        return $this->belongsTo('App\Models\Merch', 'merch_id');
    }

    //关联订单item表
    public function orderItem()
    {
        return $this->hasMany('App\Models\ShopOrderItem', 'order_id');
    }

    //关联订单item表
    public function orderAction()
    {
        return $this->hasMany('App\Models\ShopOrderAction', 'order_id', 'order_code');
    }

    /* =======================模型关联 end=======================*/

    //生成订单号
    public function createShopOrderCode()
    {
        do {
            $rand = zstr_rand_char(5, 'ABCDEFGHJKLMNPQRSTUVWXYZ');
            $shopOrder = $this->orderby('created_at', 'desc')->first();

            $code = 1;
            if ($shopOrder) {
                $number = $shopOrder->order_code;
                $code = substr($number, 17) + 1;
            }

            $number = 'CYSHOP'.date('ymd').$rand.$code;
            $result = $this->where('order_code', $number)->first();
        } while ($result);

        return $number;
    }

    //购物车内涉及拆单，多个订单公用一个订单号 public_code
    public function createPubCode()
    {
        do {
            $first_zimu = rand(2, 4);
            $code = zstr_rand_char($first_zimu, 'ABCDEFGHJKLMNPQRSTUVWXYZ');

            $code = 'CYSPUB'.date('ymd').$code.rand(100, 999);
            $code .= zstr_rand_char(3, '0123456789');

            $number = str_pad($code, 25, '0', STR_PAD_RIGHT);

            $result = $this->where('public_code', $code)->first();
        } while ($result);

        return $number;
    }

    /**
     * 商城下单成功 给商户打款
     * 购买优惠券给用户发布优惠券
     * 购物实体产品
     *
     * @param [type] $order [description]
     *
     * @return [type] [description]
     */
    public function shopOrderPayed($order)
    {
        if (1 == $order->is_virtual) {
            $order->is_send = 1;
            $order->send_at = Carbon::now();
            $order->is_get = 1;
            $order->get_at = Carbon::now();
            $order->save();

            // 购买虚拟产品 1、优惠券  2 其他
            if ('shop_coupon' == $order->type) {
                $this->shopCoupon($order);
            }
        } else {
            //购买实体产品流程 加销量 减库存
            //购买实体产品流程
            $this->shopOrderNormal($order);
        }

        // 通知用户付款成功
        User::find($order->user_id)->notify(new \App\Notifications\Users\OrderPayed($order));
    }

    /**
     * 商城下单成功购买优惠券给用户发布优惠券.
     *
     * @param [type] $order [description]
     *
     * @return [type] [description]
     */
    private function shopCoupon($order)
    {
        $this->merchWalletAdd($order);

        $user = User::find($order->user_id);
        $shopOrderItem = ShopOrderItem::where('order_id', $order->id)
                        ->first();

        $shopProduct = ShopProduct::find($shopOrderItem->product_id);
        $shopProduct->sale_num = $shopProduct->sale_num + $shopOrderItem->product_num;
        $shopProduct->save();

        $couponType = CouponType::where('sended_num', '<', \DB::raw('send_num'))
                    ->where('merch_id', $order->merch_id)
                    ->sending()
                    // ->where('start_send_at', "<", Carbon::now())
                    // ->where('end_send_at', ">", Carbon::now())
                    ->find($shopProduct->coupon_type_id);

        if ($couponType) {
            for ($i = 0; $i < $shopOrderItem->product_num; ++$i) {
                //发放优惠券
                $data = array(
                    'item_id' => $order->id,
                );
                \Event::fire(new \App\Events\SendCouponEvent($couponType->id, $user->id, $data));
            }
        } else {
            errorLog('shop_coupon', $order);
        }

        \Event::fire(new \App\Events\ConsumeEvent($order));

        // 通知商家收款成功
        Merch::find($order->merch_id)->notify(new \App\Notifications\Merchs\Receive($order));
    }

    /**
     * 商城下单成功购买优惠券给用户发布优惠券
     * 商城下单成功购买实体产品加销量.
     *
     * @param [type] $order [description]
     *
     * @return [type] [description]
     */
    private function shopOrderNormal($order)
    {
        $shopOrderItems = ShopOrderItem::where('order_id', $order->id)
                        ->get();

        foreach ($shopOrderItems as $key => $shopOrderItem) {
            $shopProduct = ShopProduct::find($shopOrderItem->product_id);
            $shopProduct->sale_num = $shopProduct->sale_num + $shopOrderItem->product_num;
            $shopProduct->save();
        }
    }

    /**
     * 实体产品确认收货.
     *
     * @param [type] $order [description]
     *
     * @return [type] [description]
     */
    public function shopOrderNormalGet($order)
    {
        $this->merchWalletAdd($order);

        $order->is_get = 1;
        $order->get_at = Carbon::now();
        $order->save();

        \Event::fire(new \App\Events\ConsumeEvent($order));

        // 通知商家收款成功
        Merch::find($order->merch_id)->notify(new \App\Notifications\Merchs\Receive($order));
    }
}
