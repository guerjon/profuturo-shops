<?php

class LoadsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		return View::make('loads.index');
	}


  public function store()
  { 
  	set_time_limit (300);
    ini_set('auto_detect_line_endings', 1);
	if(Input::file('file') == NULL){
      return Redirect::to(action('LoadsController@index'))->withErrors(new MessageBag([
        'file' => 'El archivo es requerido',
      ]));
    }

    $file = Input::file('file');
    $created = 0;
    $updated = 0;
    $users_updated = 0;
     
    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated,&$users_updated) {

      $reader->each(function($sheet)use(&$created, &$updated,&$users_updated){

        $sheet->each(function($row)use(&$created, &$updated,&$users_updated){

		$address = Address::where(['gerencia' => $row->gerencia])->first();
		$user = User::where('ccosto',$row->ccostos)->first();
		if($user){
			if($address){
				$user->address_id = $address->id;
			    $address->update(['domicilio' => $row->domicilio,'inmueble' => $row->inmueble,'gerencia' => $row->gerencia]);

			    if($address->isDirty()){
				    $address->save();
				  	$updated++;
			    }
			}else{
				$address = new Address([
				  'domicilio' => $row->domicilio,
				  'inmueble' => $row->inmueble,
				  'gerencia' => $row->gerencia,
				]);
				if($address->save()){
					$user->address_id = $address->id;
					if($user->save()){
						$users_updated++;
					}
					$created++;	
				}
			}   
		}       	
        else{
        	Log::debug("No se encontro al usuario con el CCOSTOS" + $row->ccostos);
        }

        });
      });
    });

    return Redirect::to(action('LoadsController@index'))->withSuccess("Se agregaron $created registros. Se actualizaron $updated direcciones y se actualizo la direccion de $users_updated usuarios");
  }


}
