<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToBusinessCards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('business_cards',function($table){
			$table->string('type')->default('paper');
			$table->string('linea_negocio');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('business_cards',function($table){
			$table->dropColumn('type');
			$table->dropColumn('linea_negocio');
		});
	}

}
