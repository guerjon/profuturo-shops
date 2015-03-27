<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcOrdersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('bc_orders', function($table)
		{
		  $table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('comments')->nullable();
			$table->string('receive_comments')->nullable();
			$table->tinyInteger('status')->unsigned()->default(0);
			$table->boolean('confirmed')->default(false);
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bc_orders', function($table){
			$table->dropForeign('bc_orders_user_id_foreign');
		});
		Schema::drop('bc_orders');
	}

}
