<?

class Unless extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/unless.csv', 'r')){
			$saved = 0;
				
		    while($row = fgetcsv($h)){
		    $user = User::find($row[0]);
		   		if($user){
		   			$user->address_id = $row[1];
		   			if($user->save()){
		   				$saved++;
		   			}
		   		}
		    	else{
		    		Log::debug("no se pudo guardar al usuario" + $row[0]);
		    	}

		    }
		    Log::debug("tantos usuarios salvados" + $saved);
    	}
	}
}