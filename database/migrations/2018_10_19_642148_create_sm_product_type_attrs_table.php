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

class CreateSmProductTypeAttrsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('sm_product_type_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('type_id')->comment('所属商品类型');
            $table->string('name')->comment('属性名称');
            $table->string('type')->comment('属性输入类型，select,text,radio 等');
            $table->string('store_range')->nullable()->comment('select，radio,选项');
            $table->smallInteger('sort_order')->comment('排序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sm_product_type_attrs');
    }
}
