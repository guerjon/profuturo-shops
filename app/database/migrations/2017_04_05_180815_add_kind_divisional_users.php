<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKindDivisionalUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('divisionals_users', function(Blueprint $table)
		{
			$table->string('kind')->default('papeleria');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('divisionals_users', function(Blueprint $table)
		{
			$table->dropColumn('kind');
		});
	}

}
