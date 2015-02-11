<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		User::create([
			'password' => 'admin',
			'role' => 'admin',
			'gerencia' => 'Iván Gutiérrez',
			'ccosto' => 1
			]);

		$this->call('PaperProductSeeder');
		$this->call('UserSeeder');
	}

}
