<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUsersprojectColumnsToUser extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('nombre')->nullable();
			$table->integer('num_empleado')->nullable();
			$table->string('email')->nullable();
			$table->string('extension')->nullable();
			$table->string('celular')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->dropColumn('nombre');
			$table->dropColumn('num_empleado');
			$table->dropColumn('email');
			$table->dropColumn('extension');
			$table->dropColumn('celular');
		});
	}

}
