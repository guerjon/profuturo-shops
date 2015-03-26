<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBcOrderBusinessCardTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('bc_order_business_card', function($table)
		{
			$table->integer('bc_order_id')->unsigned();
			$table->integer('business_card_id')->unsigned();
			$table->integer('quantity');
			$table->tinyInteger('status')->nullable();
			$table->string('comments')->nullable();

			$table->foreign('bc_order_id')->references('id')->on('bc_orders')->onDelete('cascade');
			$table->foreign('business_card_id')->references('id')->on('business_cards')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bc_order_business_card', function($table){
			$table->dropForeign('bc_order_business_card_bc_order_id_foreign');
			$table->dropForeign('bc_order_business_card_business_card_id_foreign');
		});
		Schema::drop('bc_order_business_card');
	}

}
