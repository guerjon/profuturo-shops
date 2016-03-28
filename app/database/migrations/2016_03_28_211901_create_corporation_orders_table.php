<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporationOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('corporation_orders', function($table)
		{
		  	$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->text('comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->string('receive_comments');
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
		Schema::table('corporation_orders', function($table){
			$table->dropForeign('corporation_orders_user_id_foreign');
		});
		Schema::drop('corporation_orders');
	}

}
