<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mac_products',function($table){
			$table->increments('id');
			$table->text('name');
			$table->integer('max_stock');
			$table->text('measure_unit');
			$table->integer('id_people_mac');
			$table->float('price');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mac_products');
	}

}
