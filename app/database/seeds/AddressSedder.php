<?

class AddressSedder extends Seeder
{

	public function run()
	{
		if($h = fopen('app/database/csvs/Address.csv', 'r')){
			$saved = 0;
				
		    while($row = fgetcsv($h)){
		    $user = User::where('ccosto',$row[0])->first();
		    $address = Address::firstOrNew(['domicilio' => $row[5],'inmueble' => $row[6]]);	
		    	if($address->save()){
		    		if($user){
			    		$user->address_id = $address->id;
						if($user->save()){
							$saved++;
						}else{
							Log::debug("no se pudo actualizar al usuario" + $user->ccosto);
						}			    			
		    		}
		    	}else{
		    		Log::debug("no se pudo guardar la dirección" + $row[0]);
		    	}
		    }
    	}
	}
}