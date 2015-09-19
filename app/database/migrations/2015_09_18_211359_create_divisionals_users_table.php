<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionalsUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('divisionals_users', function($table)
		{
		  $table->increments('id');
			$table->integer('divisional_id')->unsigned();
			$table->date('from');
			$table->date('until');
			
			$table->foreign('divisional_id')->references('id')->on('divisionals')->onDelete('cascade');
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
		Schema::drop('divisionals_users');
	}

}
