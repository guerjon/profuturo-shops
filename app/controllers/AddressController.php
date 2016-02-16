<?php

class AddressController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$addresses = Address::orderBy('ccostos');
		if(Input::has('ccostos'))
			$addresses->where('ccostos','LIKE','%'.Input::get('ccostos').'%');
		if(Input::has('regional'))
			$addresses->where('regional','LIKE','%'.Input::get('regional').'%');
		if(Input::has('divisional'))
			$addresses->where('divisional','LIKE','%'.Input::get('divisional').'%');
		if(Input::has('linea_de_negocio'))
			$addresses->where('linea_de_negocio','LIKE','%'.Input::get('linea_de_negocio').'%');


		return View::make('address.index')->withAddresses($addresses->get());
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
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$address = new Address(Input::all());
		if($address->save())
			return Redirect::action('AddressController@index')->withSuccess('Se añadio la dirección con exito');
		else
			return Redirect::back()->withErrors($addresses->getErrors());
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$address = Address::find($id);
		return View::make('address.edit')->withAddress($address);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$address = Address::find($id);
		if($address){
			if($address->update(Input::all())){
				return Redirect::action('AddressController@index')->withSuccess('La dirección se ha guardado correctamente');
			}else{
				return Redirect::back()->withErrors('Ha ocurrido un error al actualizar la dirección');
			}	
		}else{
			return Redirect::back()->withErrors('No sé ha encontrado la dirección');
		}
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$address = Address::find($id);
		if($address){
			if($address->delete()){
				return Redirect::action('AdminAddressController@index')->withSuccess('Se ha eliminado la dirección para este centro de costos');
			}else{
				return Redirect::back()->withErrors('Sucedio un error al tratar de eliminar la dirección');
			}
		}else{
			return Redirect::back()->withErrors('No se encontro la dirección');
		}
	}

}
