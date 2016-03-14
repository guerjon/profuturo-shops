<?

class CategorySeeder extends Seeder
{
	public function run()
	{
		if($h = fopen('app/database/csvs/categorias.csv', 'r')){
			
		    while($row = fgetcsv($h)){
		    	$category = Category::create(['name' => $row[0]]);
		    }
    	}
	}
}