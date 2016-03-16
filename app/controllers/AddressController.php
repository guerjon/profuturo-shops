<?php

class AddressController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = User::orderBy('address_id','desc');

		if(Input::has('ccostos'))
			$users->where('ccosto','LIKE','%'.Input::get('ccostos').'%');
		if(Input::has('regional'))
			$users->where('region_id',Input::get('regional'));
		if(Input::has('divisional'))
			$users->where('divisional_id',Input::get('divisional'));
		if(Input::has('linea_de_negocio'))
			$users->where('linea_negocio','LIKE','%'.Input::get('linea_de_negocio').'%');

		return View::make('address.index')->withUsers($users->get());
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
		$regions = Region::lists('name');
		$divisionals = Divisional::lists('name');
		$ccosto = User::where('role','!=','admin')->orderBy('ccosto')->lists('ccosto','ccosto');
		return View::make('address.create')->withAddress(new Address)->withRegions($regions)->withDivisionals($divisionals)->withCcostos($ccosto);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = User::find($id);
		return View::make('address.edit')->withUser($user);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$user = User::find($id);
		if($user){
			if($user->update(Input::only('inmueble','domicilio'))){
				return Redirect::action('AddressController@index')->withInfo('La dirección se enviara al administrador para su aprobación.');
			}else{
				return Redirect::back()->withErrors('Ha ocurrido un error al actualizar la dirección');
			}	
		}else{
			return Redirect::back()->withErrors('No sé ha encontrado la dirección');
		}
	}
}
