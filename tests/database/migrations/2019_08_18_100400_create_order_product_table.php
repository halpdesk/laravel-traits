<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('order_product', function (Blueprint $table) {

            $table->unsignedInteger('order_id')->index();
            $table->unsignedInteger('product_id')->index();

            // Foreign keys
            $table->foreign('order_id')->references('id')->on('orders')
				->onDelete('no action')->onUpdate('no action');
            $table->foreign('product_id')->references('id')->on('products')
				->onDelete('no action')->onUpdate('no action');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('order_product');
    }
}
