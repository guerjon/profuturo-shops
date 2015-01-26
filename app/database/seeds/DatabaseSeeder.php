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
			'email' => 'i.gutierrez@soriano-ariza.com',
			'password' => 'admin',
			'first_name' => 'Iván',
			'last_name' => 'Gutierrez',
			'role' => 'admin',
			'ccosto' => 0
			]);

		$this->call('PaperProductSeeder');
		$this->call('UserSeeder');
	}

}
