<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderFurnitureTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('furniture_furniture_order', function($table)
		{
		  $table->integer('furniture_order_id')->unsigned();
			$table->integer('furniture_id')->unsigned();
			$table->integer('quantity');
			$table->text('company');
			$table->text('assets');
			$table->text('ccostos');
			$table->text('color');
			$table->integer('id_active');
			$table->integer('status');
			$table->string('comments');
			$table->foreign('furniture_order_id')->references('id')->on('furniture_orders')->onDelete('cascade');
			$table->foreign('furniture_id')->references('id')->on('furnitures')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('furniture_furniture_order');
	}

}
