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
			'linea_negocio' => 'AFORE',
			'ccosto' => 1
			]);

		$this->call('PaperProductSeeder');
		$this->call('UserSeeder');
	}

}
