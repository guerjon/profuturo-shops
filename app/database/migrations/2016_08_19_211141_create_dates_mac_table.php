<?php
//Esta tabla es usada para definir las fechas en las que se pueden hacer pedidos de corporativo.
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatesMacTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('dates_mac',function($table){
			$table->increments('id');
			$table->date('since');
			$table->date('until');
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
		Schema::drop('dates_mac');
	}

}
