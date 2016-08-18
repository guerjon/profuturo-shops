<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInmuebleColumnBussinesCards extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		

		if (Schema::hasColumn('business_cards', 'inmueble'))
		{
			Schema::table('business_cards',function($table){
				$table->dropColumn('inmueble');
			});    
		}

		Schema::table('bc_order_business_card',function($table){
			$table->string('inmueble')->nullable();
		});
		Schema::table('bc_orders_extras',function($table){
			$table->string('inmueble_talento')->nullable();
			$table->string('inmueble_gerente')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('bc_order_business_card',function($table){
			$table->dropColumn('inmueble');
		});

		Schema::table('bc_orders_extras',function($table){
			$table->dropColumn('inmueble_talento');
			$table->dropColumn('inmueble_gerente');
		});
	}

}
