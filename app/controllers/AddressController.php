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
		$user = User::where('ccosto',Input::get('ccostos'))->first();

		if($user){
			if($user->address_id == 0){
				$address = new Address(Input::only(['inmueble','domicilio','gerencia']));		

				if($address->save()){
					$user->address_id = $address->id;
					if($user->save()){
						return Redirect::action('AddressController@index')->withSuccess('Se ha guardado y asociado la dirección al usuario');	
					}else{Log::debug('f');
						return Redirect::back()->withErrors(["Se guardo la dirección pero no se asocio al usuario"]);	
					}
				}
			}else{
				$address = Address::find($user->address_id);
				if($address){
					Message::sendMessage(Auth::user()->id,1,"Se solicita un cambio de domicilio para el usuario con CCOSTO :". $user->ccosto);
					$address->update(['posible_cambio' => Input::get('domicilio')]);

					if($address->save()){
						return Redirect::action('AddressController@index')->withSuccess('Se ha guardado y asociado la dirección al usuario, se enviara un mensaje al administrador para su aprobación.');
					}
				}else{
					Log::debug('s');
					return Redirect::back()->withErrors(["No se pudo guardar la dirección"]);	
				}				
			}

		}else{
			Log::debug('f');
			return Redirect::back()->withErrors(["No se encontro al usuario."]);
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
