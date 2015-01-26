<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('business_cards', function($table)
		{
		  $table->increments('id');
			$table->integer('no_emp');
			$table->string('nombre');
			$table->string('ccosto');
			$table->string('nombre_ccosto');
			$table->string('nombre_puesto');
			$table->date('fecha_ingreso');
			$table->string('rfc')->nullable();
			$table->string('web');
			$table->string('gerencia');
			$table->string('direccion');
			$table->string('telefono');
			$table->string('celular');
			$table->string('email');
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
		Schema::drop('business_cards');
	}

}
