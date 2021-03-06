<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlankCardsBcOrderTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('blank_cards_bc_order', function($table)
		{
			$table->integer('bc_order_id')->unsigned();
			$table->integer('quantity');
			$table->string('nombre_puesto');
			$table->string('direccion_alternativa_tarjetas');
			$table->string('telefono_tarjetas');
			$table->tinyInteger('status')->default(0);
			$table->string('comments');
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
		
		Schema::drop('blank_cards_bc_order');
	}

}
