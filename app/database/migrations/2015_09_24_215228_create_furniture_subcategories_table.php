<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnitureSubcategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('furniture_subcategories', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->string('image_file_name')->nullable();
			$table->integer('image_file_size')->nullable();
			$table->string('image_content_type')->nullable();
			$table->timestamp('image_updated_at')->nullable();
			$table->timestamps();

			$table->integer('furniture_category_id')->unsigned();
			$table->foreign('furniture_category_id')->references('id')->on('furniture_categories')->onDelete('cascade');
		});


		Schema::table('furnitures', function($table){
			$table->integer('furniture_subcategory_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('furniture_subcategory_id')->references('id')->on('furniture_subcategories')->onDelete('SET NULL');
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
			$table->dropForeign('furnitures_furniture_subcategory_id_foreign');
		});

		Schema::drop('furniture_subcategories');
	}
	
}
