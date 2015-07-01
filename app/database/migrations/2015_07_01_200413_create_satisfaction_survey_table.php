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
		Schema::create('satisfaction_survey',function($table){
			$table->increments('id');
			$table->integer('question_one');
			$table->integer('question_two');
			$table->integer('question_three');
			$table->integer('question_four');
			$table->integer('question_five');
			$table->integer('question_six');
			$table->longText('comments')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schemm::drop('satisfaction_survey');
	}

}
