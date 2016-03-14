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

		$this->call('DivisionalSeeder');
		$this->call('RegionSeeder');
		$this->call('ColorSeeder');
		$this->call('CategorySeeder');
		$this->call('FurnitureCategorySeeder');
		
		// $this->call('PaperProductSeeder');
		// $this->call('UserSeeder');
		// $this->call('ColorSeeder');
		
	}

}
