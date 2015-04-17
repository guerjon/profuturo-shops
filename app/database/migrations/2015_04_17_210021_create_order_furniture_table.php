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
		
		Schema::create('order_furniture', function($table)
		{
		  $table->integer('order_id')->unsigned();
			$table->integer('furniture_id')->unsigned();
			$table->integer('quantity');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
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
		Schema::table('order_furniture', function($table){
			$table->dropForeign('order_furniture_order_id_foreign');
			$table->dropForeign('order_furniture_furniture_id_foreign');
		});
		Schema::drop('order_furniture');
	}

}
