<?php
class AdminAddressController extends AdminBaseController
{

	public function index()
	{
		$addresses = Address::orderBy('ccostos')->get();
		return View::make('address.index')->withAddresses($addresses);
	}


	public function create()
	{
		return View::make('address.create')->with(new Address);
	}

	public function store()
	{
		
	}


	public function update($id)
	{
		
		$address = Address::find($id);
		if($address){
			if(Input::get('aprobado') == 1){
				$address->domicilio = $address->posible_cambio;
				$address->posible_cambio = null;
			}
			else{
				$address->posible_cambio = null;
			}
			
			if($address->save()){
				
				return Redirect::action('AdminOrdersController@index')->withSuccess('Se actualizo correctamente la dirección');
			}else{
				return Redirect::back()->withErrors('Surgio algun error al querer guardar la dirección');
			}
		}else{
			return Redirect::back()->withErrors('No se encontro la dirección');
		}
	}

	public function delete($id)
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