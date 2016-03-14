<?

class RegionSeeder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/regiones.csv', 'r')){
		    while($row = fgetcsv($h)){
	    		$divisional = Region::create(['name' => $row[0]]);
		    }
    	}
	}
}