<?php



class AddressController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$addresses = Address::orderBy('inmueble');

		if(Input::has('ccosto'))
			$addresses->where('ccosto','LIKE','%'.Input::get('ccosto').'%');

		if(Input::has('divisional_id'))
			$addresses->where('divisional_id',Input::get('divisional_id'));

		if(Input::has('gerencia'))
			$addresses->where('gerencia',Input::get('gerencia'));

		if(Input::has('region_id'))
			$addresses->where('region_id',Input::get('region_id'));

		if(Input::has('inmueble'))
			$addresses->where('inmueble',Input::get('inmueble'));

		if(Input::has('domicilio'))
			$addresses->where('domicilio','LIKE','%'.Input::get('domicilio').'%');

		
		if(Input::has('excel')){
			$headers = 
			[
			  'CCOSTOS',
			  'GERENCIA',
			  'INMUEBLE',
			  'DOMICILIO',
			  'DIVISIONAL',
			  'REGION',
			];

			$datetime = \Carbon\Carbon::now()->format('d-m-Y');
			
			Excel::create('DIRECCIONES_'.$datetime, function($excel) use($addresses,$headers){
				$excel->sheet('productos',function($sheet)use($addresses,$headers){
					$sheet->appendRow($headers);
						$addresses = $addresses->get(); 

						foreach ($addresses as $address) {
						  
							$sheet->appendRow([
								$address->ccostos,
								$address->gerencia,
								$address->inmueble,
								$address->domicilio,
								$address->divisional ? $address->divisional->name : 'N/A',
								$address->region ? $address->region->name : 'N/A'
							]); 
						}
					});
			})->download('xlsx');
		}

		return View::make('address.index')->withAddresses($addresses->paginate(20));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('address.create')->withAddress(new Address);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{

		$user = Address::find($id);
		if($user){
			return View::make('address.edit')->withUser($user);
		}else{
			return Redirect::back()->withErrors(["No se encontro la dirección"]);
		}
	}

	public function store()
	{
		$user = User::where('ccosto',Input::get('ccostos'))->whereNull('deleted_at')->first();

		if($user){
			Log::debug($user);

			if($user->address_id == 0){
				$address =  new Address(Input::only(['inmueble','domicilio','gerencia']));		

				if($address->save()){
					DB::update('update users set address_id = '.$address->id.' where ccosto = '.Input::get('ccostos'));
						return Redirect::action('AddressController@index')->withSuccess('Se ha guardado y asociado la dirección al usuario');	
					
				}
			}else{
				$address = Address::find($user->address_id);
				if($address){
					Message::sendMessage(Auth::user()->id,1,"Se solicita un cambio de domicilio para el usuario con CCOSTO :". $user->ccosto);

					
					$address->update(['posible_cambio' => Input::get('domicilio')]);

					if($address->save()){

						Mail::send('admin::email_templates.address_change',['address' => $address,'user' => $user],function($message){
        					$message->to("karina.ascencionhernandez@profuturo.com.mx")->subject('Se solicita aprobación un cambio de domicilio');
        				});  
						
						return Redirect::action('AddressController@index')->with(['errors' => ['Se ha guardado y asociado la dirección al usuario, se enviara un mensaje al administrador para su aprobación.']]);
						
					}
				}else{
					return Redirect::back()->with(['errors' => ["No se pudo guardar la dirección"]]);	
				}				
			}

		}else{
			return Redirect::back()->with(['errors' => ["No se encontro al usuario."]]);
		}

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = Address::find($id);
		if($user){
			if($user->update(Input::only('inmueble','posible_cambio'))){
				return Redirect::action('AddressController@index')->withInfo('La dirección se enviara al administrador para su aprobación.');
			}else{
				return Redirect::back()->withErrors('Ha ocurrido un error al actualizar la dirección');
			}	
		}else{
			return Redirect::back()->withErrors('No sé ha encontrado la dirección');
		}
	}
}
