<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatisfactionSurveyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('satisfaction_surveys',function($table){
			$table->increments('id');
			$table->integer('question_one');
			$table->integer('question_two');
			$table->integer('question_three');
			$table->integer('question_four');
			$table->integer('question_five');
			$table->integer('question_six');
			$table->integer('general_request_id')->unsigned();
			$table->longText('comments')->nullable();
			$table->foreign('general_request_id')->references('id')->on('general_requests')->onDelete('cascade');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('satisfaction_surveys');
	}

}
