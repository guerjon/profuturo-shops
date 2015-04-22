<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnitureOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('furniture_orders', function($table)
		{
		  $table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('receive_comments');
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
		Schema::drop('furniture_orders');
	}

}
