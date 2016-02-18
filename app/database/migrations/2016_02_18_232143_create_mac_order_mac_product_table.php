<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacOrderMacProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mac_order_mac_product', function($table)
		{
		  $table->integer('order_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('quantity');
			$table->foreign('order_id')->references('id')->on('mac_orders')->onDelete('cascade');
			$table->foreign('product_id')->references('id')->on('mac_products')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mac_order_mac_product', function($table){
			$table->dropForeign('mac_order_product_order_id_foreign');
			$table->dropForeign('mac_order_product_product_id_foreign');
		});
		Schema::drop('mac_order_product');
	}

}
