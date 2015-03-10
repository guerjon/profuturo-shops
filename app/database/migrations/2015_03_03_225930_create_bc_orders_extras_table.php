<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcOrdersExtrasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bc_orders_extras', function($table){
		$table->increments('id');
		$table->string('talento_nombre');
		$table->string('talento_direccion');
		$table->string('talento_direccion_alternativa');
		$table->string('talento_tel');
		$table->string('talento_cel');
		$table->string('talento_email');
		$table->string('talento_comentarios');
		$table->string('talento_estatus');

		$table->string('gerente_nombre');
		$table->string('gerente_direccion');
		$table->string('gerente_direccion_alternativa');
		$table->string('gerente_tel');
		$table->string('gerente_cel');
		$table->string('gerente_email');
		$table->string('gerente_comentarios');
		$table->string('gerente_estatus');
		
		$table->integer('bc_order_id')->unsigned();		
		$table->timestamps();
		$table->foreign('bc_order_id')->references('id')->on('bc_orders')->onDelete('cascade');

	});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bc_orders_extras');
	}

}
