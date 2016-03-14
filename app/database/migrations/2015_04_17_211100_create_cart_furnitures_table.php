<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartFurnituresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_furnitures', function($table)
		{
		    $table->integer('user_id')->unsigned();
			$table->integer('furniture_id')->unsigned();
			$table->integer('quantity');
			$table->string('assets');
			$table->string('ccostos');
			$table->string('company');
			$table->integer('id_active');
			$table->integer('color');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
		Schema::drop('cart_furnitures');
	}

}
