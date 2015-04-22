<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnitureCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
			Schema::create('furniture_categories', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->string('image_file_name')->nullable();
			$table->integer('image_file_size')->nullable();
			$table->string('image_content_type')->nullable();
			$table->timestamp('image_updated_at')->nullable();
			$table->timestamps();
		});

		Schema::table('furnitures', function($table){
			$table->integer('category_id')->unsigned()->nullable()->default(NULL);

			$table->foreign('category_id')->references('id')->on('furniture_categories')->onDelete('SET NULL');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
	Schema::table('furnitures', function($table){
			$table->dropForeign('furnitures_category_id_foreign');
			$table->dropColumn('category_id');
		});
		Schema::drop('furniture_categories');
	}

}
