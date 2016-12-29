<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCcostosToAddress extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('address',function($table){
			$table->string('ccostos');
			$table->string('region_id');
			$table->string('divisional_id');
			$table->string('linea_negocio');

			$table->foreign('divisional_id')->references('id')->on('divisionals');
			$table->foreign('region_id')->references('id')->on('regions');
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('address',function($table){
			$table->dropColumn('ccostos');
			$table->dropColumn('divisional_id');
			$table->dropColumn('region_id');
			$table->dropColumn('linea_negocio');
			$table->dropColumn('deleted_at');
		});
	}

}
