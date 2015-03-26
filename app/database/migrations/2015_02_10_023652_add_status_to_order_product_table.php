<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusToOrderProductTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('order_product', function($table){
			$table->tinyInteger('status')->nullable();
			$table->string('comments')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('order_product', function($table){
			$table->dropColumn('status');
			$table->dropColumn('comments');
		});
	}

}
