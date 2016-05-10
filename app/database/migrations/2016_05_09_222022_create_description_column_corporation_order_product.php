<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDescriptionColumnCorporationOrderProduct extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('corporation_order_corporation_product',function($table){
			$table->string('description');
		});
		Schema::table('cart_corporation_products',function($table){
			$table->string('description');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('corporation_order_corporation_product',function($table){
			$table->dropColumn('description');
		});
		Schema::table('cart_corporation_products',function($table){
			$table->dropColumn('description');
		});
	}

}
