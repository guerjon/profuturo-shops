<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('order_product', function($table)
		{
		  $table->integer('order_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('quantity');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_product', function($table){
			$table->dropForeign('order_product_order_id_foreign');
			$table->dropForeign('order_product_product_id_foreign');
		});
		Schema::drop('order_product');
	}

}
