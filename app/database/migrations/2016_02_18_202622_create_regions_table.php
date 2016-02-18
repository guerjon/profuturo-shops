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
		Schema::create('regions',function($table){
			$table->increments('id');
			$table->string('name');
		});

		Schema::table('users',function($table){
			$table->foreign('region_id')->references('id')->on('regions')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{	
		Schema::table('users', function($table){
			$table->dropForeign('users_region_id_foreign');
		});
		Schema::drop('regions');
	}

}
