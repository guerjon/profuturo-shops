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
			$table->integer('ccosto')->unsigned();
			$table->string('nombre_ccosto');
			$table->string('nombre_puesto');
			$table->date('fecha_ingreso')->nullable();
			$table->string('rfc')->nullable();
			$table->string('web')->nullable();
			$table->string('gerencia')->nullable();
			$table->string('direccion')->nullable();
			$table->string('telefono')->nullable();
			$table->string('celular')->nullable();
			$table->string('email')->nullable();
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
