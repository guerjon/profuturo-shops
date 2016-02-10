<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('address',function($table){
			$table->increments('id');
			$table->string('ccostos');
			$table->string('gerencia');
			$table->string('regional');
			$table->string('divisional');
			$table->string('inmueble');
			$table->text('domicilio');
			$table->text('posible_cambio')->default('null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('address');
	}

}
