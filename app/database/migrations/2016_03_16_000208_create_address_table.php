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
			$table->timestamps();
		});

		Schema::table('users',function($table){
		
			$table->integer('address_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('address_id')->references('id')->on('address')->onDelete('SET NULL');
	
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
			$table->dropForeign('users_address_id_foreign');
		});
		Schema::drop('address');
	}

}
