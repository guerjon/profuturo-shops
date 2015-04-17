<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePropertiesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('properties', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->integer('description');
			$table->string('image_file_name');
			$table->string('image_file_size');
			$table->string('image_content_type');
			$table->string('image_updated_at');
		  $table->timestamps();
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('properties');
	}

}
