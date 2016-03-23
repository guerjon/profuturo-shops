<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMessagesColumn extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('messages', function($table) {
     		$table->enum('type',['orders','furnitures-orders','orders-mac','loader','personal']);       
     		$table->string('body',400);
        });
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		
		Schema::table('messages',function($table){
			$table->dropColumn('body');
			$table->dropColumn('type');
		});
	}

}
