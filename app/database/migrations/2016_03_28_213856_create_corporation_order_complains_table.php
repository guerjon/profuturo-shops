<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporationOrderComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('corporation_order_complains', function($table)
		{
		  	$table->increments('id');
			$table->integer('corporation_order_id')->unsigned();
			$table->text('complain');
		  	$table->timestamps();
			$table->foreign('corporation_order_id')->references('id')->on('corporation_orders')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('corporation_order_complains');
	}

}
