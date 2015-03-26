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
			$table->integer('ccosto')->unsigned();
			$table->string('gerencia');
			$table->string('linea_negocio');
			$table->string('password');

			$table->boolean('has_limit')->default(true);
			$table->enum('role', ['admin', 'manager', 'user_requests', 'user_paper'])->default('user_paper');

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
