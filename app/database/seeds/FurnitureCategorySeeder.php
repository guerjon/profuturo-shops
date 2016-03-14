<?

class FurnitureCategorySeeder extends Seeder
{
	public function run()
	{
		if($h = fopen('app/database/csvs/categorias_muebles.csv', 'r')){
			
		    while($row = fgetcsv($h)){
		    	$category = FurnitureCategory::create(['name' => $row[0]]);
		    }
    	}
	}
}