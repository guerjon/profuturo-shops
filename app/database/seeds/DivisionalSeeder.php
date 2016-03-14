<?

class DivisionalSeeder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/divisionales.csv', 'r')){
			
		    while($row = fgetcsv($h)){
		    	$divisional = Divisional::create(['name' => $row[0]]);
		    }
    	}
	}
}