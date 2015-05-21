<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralRequestProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('general_request_products',function($table){
			$table->increments('id');
			$table->integer('general_request_id')->unsigned();
			$table->text('name');
			$table->integer('quantity');
			$table->integer('unit_price');
			$table->timestamps();
		});

		Schema::table('general_request_products',function($table){
			$table->foreign('general_request_id')->references('id')->on('general_requests')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_request_products');
	}

}
