<?php 

class AdminCorporationCardsController extends AdminBaseController
{
	
	public function index()
	{
		$active_tab = Input::get('active_tab','untrashed');
		$gerencias = BusinessCard::withTrashed()->where('type','corporation')->orderBy('gerencia')->groupBy('ccosto')->lists('gerencia', 'gerencia');
		$cards = BusinessCard::where('type','corporation');

		if ($active_tab != 'untrashed') {;
			$cards->where('deleted_at','!=',"null");
		}

		if(Input::has('no_emp')){
		  $cards->where('no_emp','like','%'.Input::get('no_emp').'%');
		}

		if(Input::has('gerencia')){
		  $cards->where('gerencia', Input::get('gerencia'));
		}

		if(Input::has('ccosto')){
		  $cards->where('ccosto','like','%'.Input::get('ccosto').'%');
		}
		if($active_tab == 'trashed')
		  $cards->onlyTrashed();
		else{
		  $cards->whereNull('deleted_at');
		}

		return View::make('admin::corporation_cards.index')
			->withCards($cards->paginate(10))
			->withActiveTab($active_tab)
     		->withGerencias($gerencias);
	}

	public function importCards()
	{
		return View::make('admin::corporation_cards.import');
	}

	public function store()
	{ 
		set_time_limit (300);
	    ini_set('auto_detect_line_endings', 1);

	    if(Input::file('file') == NULL){
	      return Redirect::to(action('AdminBusinessCardsController@create'))->withErrors(new MessageBag([
	        'file' => 'El archivo es requerido',
	      ]));
	    }

	    $file = Input::file('file');
	    $created = 0;
	    $updated = 0;
	    
	    $business_cards =  BusinessCard::where('id','>','0')->where('type','=','corporation')->delete();
	    
	    $excel = Excel::load($file->getRealPath(), function($reader)use(&$created, &$updated) {

	      $reader->each(function($sheet)use(&$created, &$updated){
	        $sheet->each(function($row)use(&$created, &$updated){
	          $card = BusinessCard::withTrashed()->where('no_emp',$row->numero_empleado)->first();
	          Log::debug($row);

	          if(!$card){
	    		
	            $card = BusinessCard::create([
	              	'no_emp' => $row->numero_empleado ? $row->numero_empleado : 'N/A',
	              	'nombre' => $row->nombre_empleado ? $row->nombre_empleado : 'N/A',
	              	'ccosto' => $row->ccosto ? $row->ccosto : 'N/A',
	              	'nombre_puesto' => $row->nombre_puesto ? $row->nombre_puesto : 'N/A',
	              	'linea_negocio' => $row->linea_negocio,
	              	'web' => $row->web ? $row->web : 'N/A',
	              	'gerencia' => $row->gerencia ? $row->gerencia : 'N/A',
	              	'direccion' => $row->direccion ? $row->direccion : 'N/A',	
	              	'type' => 'corporation'
	            ]);
	            if($card){
	              $created++;
	            }
	            
	          }else{
	         
	            if($card->trashed())
	              $card->restore();

	            $card->fill([
	              	'no_emp' => $row->numero_empleado,
	              	'nombre' => $row->nombre_empleado,
	              	'ccosto' => $row->ccosto,
	              	'nombre_puesto' => $row->nombre_puesto,
	              	'web' => $row->web,
	              	'gerencia' => $row->gerencia,
	              	'direccion' => $row->direccion,
	              	'linea_negocio' => $row->linea_negocio,
	              	'type' => 'corporation'
	            ]);

	            if($card->save()){
	              $updated++;
	            }
	          }
	        });
	      });
    });

    return Redirect::to(action('AdminCorporationCardsController@index'))->withSuccess("Se agregaron $created registros. Se actualizaron $updated");
  }


  public function destroy($card_id)
  {
    $card = BusinessCard::withTrashed()->find($card_id);
    if(!$card)
    {
      return Redirect::to('/')->withErrors('No se encontró la tarjeta de presentación');
    }
    else{
      if($card->trashed()){
        $card->restore();
        return Redirect::to(action('AdminCorporationCardsController@index'))->withSuccess('Se ha restaurado la tarjeta de presentación');
      }else{
        $card->delete();
        return Redirect::to(action('AdminCorporationCardsController@index'))->withInfo('Se ha eliminado la tarjeta de presentación');
      }
    }
  }





}

