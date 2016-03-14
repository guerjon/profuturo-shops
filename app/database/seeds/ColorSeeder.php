<?

class ColorSeeder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/colores.csv', 'r')){
			
		    while($row = fgetcsv($h)){
		    	$color = Color::create(['color' => $row[0]]);
		    }
    	}
	}
}