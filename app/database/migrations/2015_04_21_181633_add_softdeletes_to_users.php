<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftdeletesToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */

	static $related_tables = ['users', 'orders', 'bc_orders', 'general_requests', 'furniture_orders'];
	public function up()
	{
		foreach(self::$related_tables as $table_name)
			Schema::table($table_name, function($table)
			{
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
		foreach(self::$related_tables as $table_name)
			Schema::table($table_name, function($table)
			{
				$table->dropColumn('deleted_at');
			});
	}

}
