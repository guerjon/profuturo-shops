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
      	Address::where('id','>','0')->delete();
    
        $sheet->each(function($row)use(&$created, &$updated,&$users_updated){
			$address = Address::withTrashed()->where(['ccostos' => $row->ccostos])->first();
			$divisional = Divisional::where('name',$row->divisional)->first();
			$region = Region::where('name',$row->regional)->first();


			if($address){

				if ($divisional and $region) {
					$address->fill(
						[
							"inmueble" => $row->inmueble, 
							"domicilio" => $row->domicilio,
							"gerencia" => $row->gerencia,
							"ccostos" => $row->ccostos,
							"region_id" => $region->id,
							"divisional_id" => $divisional->id,
							"linea_negocio" => $row->linea_de_negocio
						]
					);
					
					if ($address->isDirty()) {
						if ($address->save()) {
							$updated++;
						}else{
							Log::debug($address->getErrors());
						}
					}
				}

			}else{
				
				if ($divisional and $region) {
					$address = new Address();

					$address->fill(
						[
							"inmueble" => $row->inmueble, 
							"domicilio" => $row->domicilio,
							"gerencia" => $row->gerencia,
							"ccostos" => $row->ccostos,
							"region_id" => $region->id,
							"divisional_id" => $divisional->id,
							"linea_negocio" => $row->linea_de_negocio
						]
					);

					//dd($address->save());	

					if ($address->save()) {
						$created++;
					}else{
						Log::debug("adios");
					}
				}
			}
        });
      });
    });

    return Redirect::to(action('AddressController@index'))->withSuccess("Se agregaron $created registros. Se actualizaron $updated direcciones y se actualizo la direccion de $users_updated usuarios");
  }


}
