<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderComplainsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('order_complains', function($table)
		{
		  $table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->text('complain');
		  $table->timestamps();

			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_complains', function($table){
			$table->dropForeign('order_complains_order_id_foreign');
		});
		Schema::drop('order_complains');
	}

}
