<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToBcOrderBussinessCard extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bc_order_business_card',function($table){
			$table->string('telefono')->nullable();
			$table->string('direccion')->nullable();
			$table->string('celular')->nullable();
			$table->string('email')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bc_order_business_card',function($table){
			$table->dropColumn('telefono');
			$table->dropColumn('direccion');
			$table->dropColumn('celular');
			$table->dropColumn('email');
		});
	}

}
