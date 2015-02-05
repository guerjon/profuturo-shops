<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('users', function($table)
		{
		  $table->increments('id');
			$table->string('email');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('password');
			$table->enum('role', ['admin', 'manager', 'user_requests', 'user_paper'])->default('user_paper');
			$table->integer('ccosto')->unsigned();

			$table->string('nomenclatura');
			$table->string('regional');
			$table->string('gerencia');
			$table->string('asistente');


			$table->string('address');
			$table->string('phone');
			$table->integer('profuturo_assigned_number');

			$table->rememberToken();
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
		Schema::drop('users');
	}

}
