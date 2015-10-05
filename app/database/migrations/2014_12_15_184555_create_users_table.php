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
			$table->string('ccosto');
			$table->string('gerencia');
			$table->string('linea_negocio');
			$table->string('password');
			$table->boolean('has_limit')->default(true);
			$table->enum('role', ['admin', 'manager', 'user_requests', 'user_paper','user_furnitures'])->default('user_paper');
			$table->integer('region_id')->unsigned()->nullable();
			$table->integer('divisional_id')->unsigned()->nullable();

			$table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
			
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
