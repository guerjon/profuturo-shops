<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('categories', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->string('image_file_name')->nullable();
			$table->integer('image_file_size')->nullable();
			$table->string('image_content_type')->nullable();
			$table->timestamp('image_updated_at')->nullable();
			$table->timestamps();
		});

		Schema::table('products', function($table){
			$table->integer('category_id')->unsigned()->nullable()->default(NULL);

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('SET NULL');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function($table){
			$table->dropForeign('products_category_id_foreign');
			$table->dropColumn('category_id');
		});
		Schema::drop('categories');
	}

}
