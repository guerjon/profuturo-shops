<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartTrainingProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_training_products', function($table)
		{
		    $table->integer('user_id')->unsigned();
			$table->integer('training_product_id')->unsigned();
			$table->integer('quantity');
			$table->string('description');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('training_product_id')->references('id')->on('training_products')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cart_training_products');
	}

}
