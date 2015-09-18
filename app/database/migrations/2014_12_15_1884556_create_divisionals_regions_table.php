<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionalsRegionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('divisionals_regions', function($table)
		{
		  $table->increments('id');
			$table->integer('divisional_id')->unsigned();
			$table->integer('regions_id')->unsigned();
			$table->date('from');
			$table->date('until');
			
			$table->foreign('divisional_id')->references('id')->on('divisionals')->onDelete('cascade');
			$table->foreign('regions_id')->references('id')->on('regions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('divisionals_regions');
	}

}
