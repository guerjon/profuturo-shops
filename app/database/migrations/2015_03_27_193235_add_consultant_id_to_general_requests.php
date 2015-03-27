<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConsultantIdToGeneralRequests extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('general_requests', function($table){
			// $table->integer('consultant_id')->unsigned()->nullable();
			$table->integer('rating')->default(1);
			// $table->foreign('consultant_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('general_requests', function($table){
			// $table->dropForeign('general_requests_consultant_id_foreign');
			// $table->dropColumn('consultant_id');
			$table->dropColumn('rating');
		});
	}

}
