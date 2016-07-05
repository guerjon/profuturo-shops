<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrainingOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('training_orders', function($table)
		{
		  	$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->text('comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('receive_comments');
			$table->string('ccosto');
			$table->boolean('extra_order')->default(false);
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('training_orders');
	}

}
