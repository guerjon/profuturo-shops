<?php
class AdminMacAddressController extends AdminBaseController
{

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
				
				return Redirect::action('AdminMacOrdersController@index')->withSuccess('Se actualizo correctamente la dirección');
			}else{
				return Redirect::back()->withErrors('Surgio algun error al querer guardar la dirección');
			}
		}else{
			return Redirect::back()->withErrors('No se encontro la dirección');
		}
	}


}