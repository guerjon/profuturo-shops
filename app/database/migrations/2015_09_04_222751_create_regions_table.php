<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('regions',function($table){
			$table->autoincrements('id');
			$table->string('name');
		});

		Schema::table('users',function($table){
			$table->integer('region_id')->unique()->unsigned();
			$table->foreign('region_id')->refereces('id')->on('regions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('regions');
	}

}
