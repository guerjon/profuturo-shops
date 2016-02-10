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
			$table->string('num_empleado_1');
			$table->string('num_empleado_2');
			$table->string('linea_negocio');

			$table->string('password');
			$table->boolean('has_limit')->default(true);
			$table->enum('role', ['admin', 'manager', 'user_requests', 'user_paper','user_furnitures'])->default('user_paper');
			$table->integer('divisional_id')->unsigned()->nullable();
			$table->integer('region_id')->unsigned()->nullable();
			
			$table->foreign('divisional_id')->references('id')->on('divisional_id')->onDelete('cascade');
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
