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
			$table->integer('product_request_selected');
		});

		Schema::table('furniture_furniture_order',function($table){
			$table->string('request_description')->nullable();
			$table->float('request_price')->nullable();
			$table->string('request_quantiy_product')->nullable();
			$table->text('request_comments')->nullable();
			$table->increments('request_product_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('furniture_orders',function($table){
			$table->dropColumn(['request']);
		});

		Schema::table('furniture_furniture_order',function($table){
			$table->dropColumn(['request_description','request_quantiy_product','request_price','request_comments']);
		});
	}

}
