<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnitureOrdersColumns extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('furniture_orders',function($table){
			$table->char('request',1)->default(0);
		});

		Schema::table('furniture_furniture_order',function($table){
			$table->string('request_description')->nullable();
			$table->float('request_price')->nullable();
			$table->string('request_quantiy_product')->nullable();
			$table->text('request_comments')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{

	}

}
