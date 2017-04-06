<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKindToDatesCorporation extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('dates_corporation', function(Blueprint $table)
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
		Schema::table('dates_corporation', function(Blueprint $table)
		{
			$table->dropColumn('kind');
		});
	}

}
