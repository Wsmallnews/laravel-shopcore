<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmshopProductAttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smshop_product_attrs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('商品 id');
            $table->integer('attr_id')->comment('属性 id');
            $table->string('attr_name')->nullable()->comment('属性名称');
            $table->string('value')->nullable()->comment('属性值');
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
        Schema::dropIfExists('smshop_product_attrs');
    }
}
