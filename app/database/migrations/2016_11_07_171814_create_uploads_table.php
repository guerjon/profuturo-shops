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
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size');
            $table->string('file_mime')->nullable();
            $table->string('file_extension')->nullable();
            $table->string('kind');

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
