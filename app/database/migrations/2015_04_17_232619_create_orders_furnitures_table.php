<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersFurnituresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders_furnitures', function($table)
		{
		  	$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->text('comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('orders_furnitures', function($table){
			$table->dropForeign('orders_furnitures_user_id_foreign');
		});
		Schema::drop('orders_furnitures');
	}

}
