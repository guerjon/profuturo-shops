<?

class DivisionalSeeder extends Seeder
{

	public function run()
	{
			for ($i=0; $i < 4; $i++) { 
				$divisional =	Divisional::create(['id' => $i]);
				$divisional->save();
			}

		
	}

}