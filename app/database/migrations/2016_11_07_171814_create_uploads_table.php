<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('uploads',function($table){
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('file_file_name')->nullable();
			$table->integer('file_file_size')->nullable();
			$table->string('file_content_type')->nullable();
			$table->timestamp('file_updated_at')->nullable();
			$table->integer('cards_created');
			$table->integer('cards_updated');
		  	$table->timestamps();

		  	$table->foreign('user_id')->references('id')->on('users');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('uploads');
	}

}
