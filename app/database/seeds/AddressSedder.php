<?

class AddressSedder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/Address.csv', 'r')){
			$saved = 0;
		    while($row = fgetcsv($h)){
		    	
		    	$user = User::where('ccosto',$row[0])->first();
		    	if($user){
		    		$user->inmueble = $row[5];
		    		$user->domicilio = $row[6];
					
					if($user->save()){
						$saved++;
					}else{
						Log::debug("no se pudo actualizar al usuario" + $user->ccosto);
					}
		    	}else{
		    		Log::debug("no se encontro al usuario con el ccosto" + $row[0]);
		    	}
		    }
    	}
	}
}