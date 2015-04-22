<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnitureOrderComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('furniture_order_complains', function($table)
		{
		    $table->increments('id');
			$table->integer('furniture_order_id')->unsigned();
			$table->text('complain');
		    $table->timestamps();

			$table->foreign('furniture_order_id')->references('id')->on('furniture_orders')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('furniture_order_complains');
	}

}
