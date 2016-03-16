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
		  	$table->integer('mac_order_id')->unsigned();
			$table->integer('mac_product_id')->unsigned();
			$table->integer('quantity');
			$table->text('status');
			$table->text('comments');
			$table->foreign('mac_order_id')->references('id')->on('mac_orders')->onDelete('cascade');
			$table->foreign('mac_product_id')->references('id')->on('mac_products')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mac_order_mac_product');
	}

}
