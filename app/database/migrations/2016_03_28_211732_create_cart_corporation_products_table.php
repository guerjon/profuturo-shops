<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartCorporationProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_corporation_products', function($table)
		{
		    $table->integer('user_id')->unsigned();
			$table->integer('corporation_product_id')->unsigned();
			$table->integer('quantity');

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('corporation_product_id')->references('id')->on('corporation_products')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cart_corporation_products');
	}

}
