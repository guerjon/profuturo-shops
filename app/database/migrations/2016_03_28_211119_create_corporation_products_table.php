<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCorporationProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('corporation_products',function($table){
			$table->increments('id');
			$table->text('name');
			$table->integer('max_stock');
			$table->text('measure_unit');
			$table->integer('id_people_corporation');
			$table->text('description');
			$table->float('price');
			$table->string('image_file_name')->nullable();
			$table->integer('image_file_size')->nullable();
			$table->string('image_content_type')->nullable();
			$table->timestamp('image_updated_at')->nullable();
			$table->timestamps();

			$table->integer('corporation_category_id')->unsigned()->nullable()->default(NULL);
			$table->foreign('corporation_category_id')->references('id')->on('corporation_categories')->onDelete('SET NULL');

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
		Schema::drop('corporation_products');
	}

}
