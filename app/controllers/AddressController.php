<?php

class AddressController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = Address::orderBy('inmueble');

		if(Input::has('inmueble'))
			$users->where('inmueble',Input::get('inmueble'));

		if(Input::has('codigo_postal'))
			$users->where('domicilio','LIKE','%'.Input::get('codigo_postal').'%');
		if(Input::has('calle'))
			$users->where('domicilio','LIKE','%'.Input::get('calle').'%');

		return View::make('address.index')->withUsers($users->get());
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
