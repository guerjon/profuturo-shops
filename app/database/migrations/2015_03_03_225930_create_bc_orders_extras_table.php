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
		$table->string('talento_direccion')->nullable();
		$table->string('talento_direccion_alternativa')->nullable();
		$table->string('talento_tel')->nullable();
		$table->string('talento_cel')->nullable();
		$table->string('talento_email')->nullable();
		$table->string('talento_comentarios')->nullable();
		$table->string('talento_estatus')->nullable();

		$table->string('gerente_nombre');
		$table->string('gerente_direccion')->nullable();
		$table->string('gerente_direccion_alternativa')->nullable();
		$table->string('gerente_tel')->nullable();
		$table->string('gerente_cel')->nullable();
		$table->string('gerente_email')->nullable();
		$table->string('gerente_comentarios')->nullable();
		$table->string('gerente_estatus')->nullable();
		
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
