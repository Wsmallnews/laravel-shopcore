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

class CreateSmOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sm_order_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('order_id')->comment('所属订单id');
            $table->integer('product_id')->comment('产品id');
            $table->string('product_name')->comment('商品名称');
            $table->integer('product_num')->comment('购买数量');
            $table->decimal('origin_price', 10, 2)->comment('商品原价');
            $table->decimal('price', 10, 2)->comment('商品价格');
            $table->string('product_image')->comment('商品图片，只存一张');
            $table->tinyInteger('is_evaluate')->default(0)->comment('是否评价， 0未评价 1已评价');
            $table->dateTime('evaluate_at')->nullable();
            $table->string('type')->comment('商品类型 例：coupon');
            $table->string('spec_name_one')->nullable()->comment('规格1');
            $table->string('spec_name_two')->nullable()->comment('规格2');
            $table->string('spec_name_three')->nullable()->comment('规格3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sm_order_items');
    }
}
