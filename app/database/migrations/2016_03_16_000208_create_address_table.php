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
			$table->text('inmueble');
			$table->text('domicilio');
			$table->text('posible_cambio');
			$table->string('gerencia');
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


		Schema::drop('address');
	}

}
