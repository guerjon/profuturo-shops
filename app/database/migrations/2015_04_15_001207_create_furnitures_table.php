<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFurnituresTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('furnitures', function($table)
		{
		  $table->increments('id');
			$table->string('name');
			$table->integer('max_stock');
			$table->string('measure_unit');
			$table->string('sku');
			$table->string('image_updated_at');
			$table->string('id_peole_soft')->nullable();
			$table->string('description')->nullable();
			$table->string('specific_description');
			$table->string('unitary')->nullable();
			$table->integer('key')->nullable();
			$table->string('image_file_name')->nullable();
			$table->integer('image_file_size')->nullable();
			$table->string('image_content_type')->nullable();
			$table->string('tela_image_file_name')->nullable();
			$table->integer('tela_image_file_size')->nullable();
			$table->string('tela_image_content_type')->nullable();
			$table->string('delivery_time')->nullable();
			$table->string('business_conditions')->nullable();
		  $table->timestamps();
		});


		Schema::table('furnitures', function($table){
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
		Schema::drop('furnitures');
	}

}
