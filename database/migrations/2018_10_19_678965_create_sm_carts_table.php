<?php

/*
 * This file is part of the smallnews/laravel-shopcore.
 *
 * (c) smallnews <1371606921@qq.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmCartsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sm_carts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->comment('所属用户');
            $table->integer('merch_id')->comment('商户id');
            $table->integer('product_id')->comment('商品id');
            $table->string('product_name')->comment('商品名称');
            $table->integer('product_num')->comment('商品数量');
            $table->string('product_type')->default('shop_normal')->comment('产品类型 shop_coupon商城优惠券 shop_normal实体产品');
            $table->decimal('product_origin_price', 10, 2)->comment('商品原价');
            $table->decimal('product_price', 10, 2)->comment('商品价格');
            $table->string('spec_name_one')->nullable()->comment('规格1');
            $table->string('spec_name_two')->nullable()->comment('规格2');
            $table->string('spec_name_three')->nullable()->comment('规格3');
            $table->string('from')->default('shopCart')->comment('记录来源 detail商品详情 shopCart购物车');
            $table->string('product_image')->nullable()->comment('商品图片');
            $table->string('merch_name')->nullable()->comment('店铺名称');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sm_carts');
    }
}
