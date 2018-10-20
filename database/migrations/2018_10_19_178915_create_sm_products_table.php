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

class CreateSmProductsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sm_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->comment('商品名称');
            $table->integer('category_id')->default(0)->comment('商城总后台添加的产品分类id');
            $table->integer('region_id')->default(0)->comment('所属地域');
            $table->string('type')->comment('商品类型，coupon');
            $table->text('images')->comment('商品图片');
            $table->decimal('origin_price', 10, 2)->comment('原价格');
            $table->decimal('price', 10, 2)->comment('价格');
            $table->integer('sale_num')->comment('销量');
            $table->integer('stock')->comment('库存');
            $table->string('stock_time')->nullable()->comment('减库存时机，down:下单，payed:支付成功，no:不减');
            $table->integer('type_id')->default(0)->comment('商品类型 id,和 type没有直接关系');
            $table->string('spec_name_one')->nullable()->comment('规格项 1');
            $table->string('spec_name_two')->nullable()->comment('规格项 2');
            $table->string('spec_name_three')->nullable()->comment('规格项 3');
            $table->text('desc')->nullable()->comment('描述');
            $table->text('content')->nullable()->comment('详情 图片列表');
            $table->tinyInteger('is_virtual')->default(0)->comment('是否为虚拟产品， 0实体产品 1虚拟产品');
            $table->tinyInteger('is_on_sale')->comment('是否上架');
            $table->tinyInteger('is_recommend')->comment('是否推荐');
            $table->integer('is_special')->default(0)->comment('特价产品');
            $table->smallInteger('sort_order')->comment('排序');
            $table->tinyInteger('check_status')->default(0)->comment('产品审核状态，0 审核中，1 审核通过，-1 审核失败');
            $table->text('status_msg')->nullable()->comment('产品审核状态，0 审核中，1 审核通过，-1 审核失败');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sm_products');
    }
}
