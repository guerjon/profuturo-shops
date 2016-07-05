<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingOrderComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_order_complains', function($table)
		{
		  	$table->increments('id');
			$table->integer('training_order_id')->unsigned();
			$table->text('complain');
		  	$table->timestamps();
			$table->foreign('training_order_id')->references('id')->on('training_orders')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('training_order_complains');
	}

}
