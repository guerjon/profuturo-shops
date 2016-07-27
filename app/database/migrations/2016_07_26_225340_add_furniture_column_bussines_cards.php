<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFurnitureColumnBussinesCards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_cards',function($table){
			$table->string('inmueble');
		});

		Schema::table('divisionals_users',function($table){
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
		Schema::table('divisionals_users',function($table){
			$table->dropColumn(['created_at','updated_at']);
		});

		Schema::table('business_cards',function($table){
			$table->dropColumn('inmueble');
		});

	}

}
