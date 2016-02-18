<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartMacProductsTable extends Migration {


	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_mac_products', function($table)
		{
		    $table->integer('user_id')->unsigned();
			$table->integer('mac_product_id')->unsigned();
			$table->integer('quantity');

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('cart_mac_products');
	}

}
