<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('message_user', function($table)
		{
		    $table->integer('message_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::table('messages',function($table){
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
		Schema::table('message_user', function($table){
			$table->dropForeign('message_user_message_id_foreign');
			$table->dropForeign('message_user_user_id_foreign');
		});
		Schema::drop('message_user');
	}

}
