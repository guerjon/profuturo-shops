<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralRequestsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::create('general_requests', function($table)
		{
		  $table->increments('id');
			$table->string('employee_name');
			$table->integer('employee_number');
			$table->string('employee_email');
			$table->integer('employee_ext');
			$table->string('employee_cellphone');

			$table->string('project_title');
			$table->string('project_dest');
			$table->date('project_date');

			$table->tinyInteger('kind');
			$table->integer('quantity');
			$table->integer('unit_price');
			$table->date('deliver_date');

			$table->tinyInteger('distribution_list');
			$table->text('comments');

			$table->integer('user_id')->unsigned();
		  $table->timestamps();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('general_requests');
	}

}
