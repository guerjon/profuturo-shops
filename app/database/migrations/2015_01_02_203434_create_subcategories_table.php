<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubcategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('subcategories', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->integer('category_id')->unsigned();
			$table->timestamps();

			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
		});

		Schema::table('products', function($table)
		{
			$table->integer('subcategory_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('subcategory_id')->references('id')->on('subcategories');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('products', function($table)
		{
			$table->dropColumn('subcategory_id');
		});
		Schema::drop('subcategories');
	}

}
