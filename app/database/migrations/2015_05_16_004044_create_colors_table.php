<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('colors',function($table){
			$table->increments('id');
			$table->string('color');
			$table->timestamps();
		});

		Schema::table('users',function($table){
		$table->integer('color_id')->unsigned()->nullable()->default(NULL);	
		$table->foreign('color_id')->references('id')->on('colors')->onDelete('SET NULL');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users',function($table){
			$table->dropForeign('users_color_id_foreign');
			$table->dropColumn('color_id');
		});
		Schema::drop('colors');
	}

}
