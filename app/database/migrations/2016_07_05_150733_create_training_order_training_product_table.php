<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingOrderTrainingProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_order_training_product', function($table)
		{
		  	$table->integer('training_order_id')->unsigned();
			$table->integer('training_product_id')->unsigned();
			$table->integer('quantity');
			$table->text('status');
			$table->text('comments');
			$table->foreign('training_order_id')->references('id')->on('training_orders')->onDelete('cascade');
			$table->foreign('training_product_id')->references('id')->on('training_products')->onDelete('cascade');
			$table->string('description');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('training_order_training_product');
	}

}
