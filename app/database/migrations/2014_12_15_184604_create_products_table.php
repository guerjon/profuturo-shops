<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

			Schema::create('products', function($table)
			{
			  $table->increments('id');
				$table->string('name');
				$table->string('model');
				$table->text('description');
				$table->integer('max_stock');
				$table->string('measure_unit');
				$table->string('id_people','18');
				$table->string('price','10');
				$table->string('sku');
				$table->string('image_file_name')->nullable();
				$table->integer('image_file_size')->nullable();
				$table->string('image_content_type')->nullable();
				$table->timestamp('image_updated_at')->nullable();
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
		Schema::drop('products');
	}

}
