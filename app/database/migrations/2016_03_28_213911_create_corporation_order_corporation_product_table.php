<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporationOrderCorporationProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('corporation_order_corporation_product', function($table)
		{
		  	$table->integer('corp_order_id')->unsigned();
			$table->integer('corp_product_id')->unsigned();
			$table->integer('quantity');
			$table->text('status');
			$table->text('comments');
			$table->foreign('corp_order_id')->references('id')->on('corporation_orders')->onDelete('cascade');
			$table->foreign('corp_product_id')->references('id')->on('corporation_products')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('corporation_order_corporation_product');
	}

}
