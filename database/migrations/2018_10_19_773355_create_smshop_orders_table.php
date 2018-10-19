<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmshopOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smshop_orders', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('public_code')->nullable()->comment("商城订单拆单情况公共订单号");
            $table->string('order_code', 60)->unique()->comment("订单号");
            $table->integer('user_id')->comment("用户id");
            $table->integer('merch_id')->comment("商户id");
            $table->string('type')->comment("订单类型 shop_coupon商城优惠券");
            $table->string('province')->nullable()->comment('位置 id');
            $table->string('city')->nullable()->comment('城市名称');
            $table->string('area')->nullable()->comment('区名称');
            $table->string('address')->nullable()->comment('详细地址');
            $table->string('consignee')->nullable()->comment('收货人');
            $table->string('phone')->nullable()->comment('收货人联系电话');
            $table->string('postcode')->nullable()->comment('邮编');
            $table->string('region_id')->nullable()->comment('行政区划代码');

            $table->string('express_name')->nullable()->comment('快递公司');
            $table->string('express_no')->nullable()->comment('快递单号');
            $table->tinyInteger('is_virtual')->default(0)->comment("是否为虚拟产品， 0实体产品 1虚拟产品");
            $table->decimal('product_fee', 10, 2)->comment('商品总金额');
            $table->decimal('total_fee', 10, 2)->comment('订单总金额，（(product_fee + shipping_fee) = total_fee = (wechat_fee + wallet_fee + ...)）');
            $table->tinyInteger('is_pay')->default(0)->comment("是否支付，0 未支付，1 支付中，2 支付完成");
            $table->dateTime('payed_at')->nullable();
            $table->string('pay_type')->comment('支付类型，wechat,alipay,wallet,bonus,coupon 等,混合支付 “ ,wechat,wallet, ”');
            $table->decimal('wechat_fee', 10, 2)->comment('微信支付金额');
            $table->decimal('alipay_fee', 10, 2)->comment('支付宝支付金额');
            $table->decimal('wallet_fee', 10, 2)->comment('余额支付金额');
            $table->decimal('bonus_fee', 10, 2)->comment('红包支付金额');
            $table->decimal('coupon_fee', 10, 2)->comment('优惠券支付金额');
            $table->decimal('silver_fee', 10, 2)->comment('银币支付金额');
            $table->decimal('discount_fee', 10, 2)->comment('折扣支付金额');
            $table->tinyInteger('is_send')->default(0)->comment("是否发货");
            $table->dateTime('send_at')->nullable()->comment('发货时间');
            $table->tinyInteger('is_get')->default(0)->comment("是否收货");
            $table->dateTime('get_at')->nullable()->comment('收货时间');
            $table->tinyInteger('is_evaluate')->default(0)->comment("是否评价");
            $table->dateTime('evaluate_at')->nullable();
            $table->tinyInteger('is_cancel')->default(0)->comment("是否为取消订单， 0否 1是");
            $table->dateTime('cancel_at')->nullable()->comment('订单取消时间');
            $table->tinyInteger('is_return')->default(0)->comment('是否申请退款');
            $table->string('return_msg')->nullable()->comment('退款原因');
            $table->dateTime('return_at')->nullable()->comment('退款时间');
            $table->text('note')->nullable()->comment("买家留言");
            $table->string('merch_profit_scale')->comment('商家服务费比例');
            $table->string('merch_discount')->comment('商家折扣');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down()
     {
         Schema::dropIfExists('smshop_orders');
     }
}
