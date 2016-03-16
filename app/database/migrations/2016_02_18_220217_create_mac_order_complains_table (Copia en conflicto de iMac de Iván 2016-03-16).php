<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacOrderComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mac_order_complains', function($table)
		{
		  	$table->increments('id');
			$table->integer('mac_order_id')->unsigned();
			$table->text('complain');
		  	$table->timestamps();
			$table->foreign('mac_order_id')->references('id')->on('mac_orders')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('mac_order_complains');
	}

}
