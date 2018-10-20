<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmCategorysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_categorys', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->comment('总管理后台添加商城商品分类');
            $table->string('name')->comment('商品分类名称');
            $table->string('icon')->comment('分类图标');
            $table->smallInteger('sort_order')->comment('排序');
            $table->nestedSet();
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
         Schema::dropIfExists('sm_categorys');
     }
}
