<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmProductSpecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_product_specs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->comment('所属商品');
            $table->string('spec_name_one')->comment('规格项 1');
            $table->string('spec_name_two')->comment('规格项 2');
            $table->string('spec_name_three')->comment('规格项 3');
            $table->decimal('origin_price', 10, 2)->comment('原价格');
            $table->decimal('price', 10, 2)->comment('价格');
            $table->integer('stock')->comment('库存');
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
        Schema::dropIfExists('sm_product_specs');
    }
}
