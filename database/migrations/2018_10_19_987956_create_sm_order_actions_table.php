<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmOrderActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_order_actions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('order_id', 60)->comment("订单id");
            $table->integer('user_id')->comment("用户id");
            $table->integer('item_id')->comment("付款方式对应的 项目id ,如 coupon_id");
            $table->string('pay_type')->comment('支付类型，wechat,alipay,wallet,bonus,coupon');
            $table->decimal('pay_fee', 10, 2)->comment("支付金额");
            $table->dateTime('payed_at')->nullable()->comment("支付时间");
            $table->string('payment_trade_no')->nullable();
            $table->string('payment_trade_status')->nullable();
            $table->string('payment_notify_id')->nullable();
            $table->string('payment_notify_time')->nullable();
            $table->string('payment_buyer_email')->nullable();
            $table->string('payment_total_fee')->nullable();
            $table->text('payment_json')->nullable();
            $table->tinyInteger('back_status')->default(0)->nullable();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_order_actions');
    }
}
