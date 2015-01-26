<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('cart_products', function($table)
		{
		  $table->integer('user_id')->unsigned();
			$table->integer('product_id')->unsigned();
			$table->integer('quantity');

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::table('cart_products', function($table){
			$table->dropForeign('cart_products_user_id_foreign');
			$table->dropForeign('cart_products_product_id_foreign');
		});
		Schema::drop('cart_products');
	}

}
