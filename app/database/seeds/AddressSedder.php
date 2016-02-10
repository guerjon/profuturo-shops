<?

class AddressSeeder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/Address.csv', 'r')){

		    while($row = fgetcsv($h)){

		        $address = new Address([
		          'ccostos' => $row[0],
		          'gerencia' => $row[1],
		          'regional' => $row[2],
		          'divisional' => $row[3],
		          'linea_de_negocio' => $row[4],
		          'inmueble' => $row[5],
		          'domicilio' => $row[6],
		          ]);
		        try{
		          $address->save();
		        }catch(Exception $e){
		          Log::error($e->getMessage());
		          continue;
		        }

		    }
    	}
	}
}