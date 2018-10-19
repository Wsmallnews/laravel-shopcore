<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmshopProductTypeAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smshop_product_type_attrs', function (Blueprint $table) {
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
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('smshop_product_type_attrs');
    }
}
