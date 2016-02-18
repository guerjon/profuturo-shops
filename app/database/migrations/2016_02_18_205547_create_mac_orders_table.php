<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMacOrdersTable extends Migration {
/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('mac_orders', function($table)
		{
		  	$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->text('comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('mac_orders', function($table){
			$table->dropForeign('mac_orders_user_id_foreign');
		});
		Schema::drop('mac_orders');
	}

}
